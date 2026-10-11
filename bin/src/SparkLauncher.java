import java.io.File;
import java.io.FileInputStream;
import java.io.FileOutputStream;
import java.io.IOException;
import java.io.InputStream;
import java.io.OutputStream;
import java.net.URISyntaxException;
import java.nio.charset.StandardCharsets;
import java.nio.file.Files;
import java.nio.file.Path;
import java.nio.file.Paths;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;
import java.util.ArrayList;
import java.util.Collections;
import java.util.Comparator;
import java.util.Enumeration;
import java.util.List;
import java.util.Locale;
import java.util.zip.ZipEntry;
import java.util.zip.ZipFile;

/**
 * Тонкий лаунчер Spark Studio для launch4j.
 *
 * Exe (launch4j, dontWrapJar) запускает этот класс, а он:
 *   1) берёт binary.jar из своей папки (рядом с spark-launcher.jar / exe),
 *   2) выбирает каталог данных пользователя (spark.data):
 *        Windows: %APPDATA%/SparkStudio
 *        macOS:   ~/Library/Application Support/SparkStudio
 *        Linux:   $XDG_DATA_HOME/spark-studio (или ~/.local/share/spark-studio)
 *      (переопределяется свойством -Dspark.data),
 *   3) при необходимости распаковывает binary.jar рядом с exe
 *      (<installDir>/app-<hash12> — проектный layout, рабочий корень
 *      spark.path). Если каталог установки недоступен для записи (например,
 *      Program Files), распаковка уходит в <data>/app-<hash12>,
 *   4) хранит не более MAX_VERSIONS распакованных версий: лишние app-*
 *      удаляются, чтобы кэш не разрастался,
 *   5) запускает настоящую IDE отдельным JVM:
 *        java -Dspark.path=<app> -Dspark.data=<data> -cp <appDir-каталоги>
 *             org.develnext.jphp.ext.javafx.FXLauncher
 *
 * Данные пользователя (config, log, cache, library, projects) всегда пишутся
 * в spark.data, а не рядом с exe.
 */
public class SparkLauncher {

    static final String MAIN_CLASS = "org.develnext.jphp.ext.javafx.FXLauncher";
    static final String PAYLOAD = "binary.jar";

    /** Максимум одновременно хранимых распакованных версий (каталогов app-*). */
    static final int MAX_VERSIONS = 3;

    /**
     * «Переносимые» ресурсы: если такой файл/каталог лежит рядом с exe
     * (в папке сборки), он копируется в распакованный рабочий корень и
     * перекрывает версию из binary.jar. Так logo.png можно заменить, не
     * пересобирая jar. Путь — относительно рабочего корня ("./").
     */
    static final String[] PORTABLE_ASSETS = {
            "logo.png",
    };

    static final String[] ALL_DIRS = {
            "runtime/jphp-core",
            "runtime/jphp-runtime",
            "gui/jphp-gui-ext",
            "gui/jphp-gui-richtext-ext",
            "gui/jphp-desktop-ext",
            "gui/jphp-systemtray-ext",
            "gui/spark-designer",
            "gui/reactfx-M5",
            "gui/richtextfx",
            "gui/undofx",
            "gui/wellbehavedfx",
            "parser/antlr4-runtime",
            "parser/spark-lexer",
            "parser/jphp-parser",
            "extensions/jphp-json-ext",
            "extensions/jphp-xml-ext",
            "extensions/jphp-zend-ext",
            "extensions/jphp-zip-ext",
            "utils/asm-all",
            "utils/commons-codec",
            "utils/gson",
            "utils/highlights",
            "utils/javassist-GA",
            "utils/slf4j-api",
            "utils/zt-zip",
            "framework/SparkStudio",
            "framework/jphp-app-framework",
            "database/HikariCP-java6",
            "database/jphp-sql-ext",
            "debug/jphp-debugger",
            "network/flowless",
            "network/spark-httpclient-bundle",
            "network/update-service",
            "languages/en",
            "languages/ru",
            "ide/spark-doc",
            "ide/spark-js-platform",
            "ide/spark-java-platform",
            "ide/spark-store",
            "php-sdk/dn-php-sdk",
            "php-sdk/dn-zend-php-sdk",
    };

    public static void main(String[] args) {
        boolean printEnv = false;
        boolean extractOnly = false;
        List<String> appArgs = new ArrayList<String>();

        for (String a : args) {
            if ("--print-env".equals(a)) printEnv = true;
            else if ("--extract-only".equals(a)) extractOnly = true;
            else appArgs.add(a);
        }

        try {
            Path launcherJar = Paths.get(
                    SparkLauncher.class.getProtectionDomain().getCodeSource().getLocation().toURI());
            Path installDir = launcherJar.toAbsolutePath().getParent();
            Path payload = installDir.resolve(PAYLOAD);

            Path dataDir = dataDir();

            // Распаковываем рядом с exe; если писать туда нельзя — в каталог данных.
            Path appRoot = chooseAppRoot(installDir, dataDir);

            String hash = Files.exists(payload) ? sha12(payload) : "";
            Path appDir = appRoot.resolve("app-" + hash);

            if (printEnv) {
                List<String> lines = new ArrayList<String>();
                lines.add("launcher   = " + launcherJar);
                lines.add("installDir = " + installDir);
                lines.add("payload    = " + payload + "  (exists=" + Files.exists(payload) + ")");
                lines.add("spark.data = " + dataDir);
                lines.add("app.root   = " + appRoot);
                lines.add("spark.path = " + appDir);
                lines.add("java       = " + javaBin());
                for (String line : lines) {
                    printLn(line);
                }
                try {
                    Files.createDirectories(dataDir);
                    Files.write(dataDir.resolve("launcher-env.txt"),
                            join(lines, System.lineSeparator()).getBytes(StandardCharsets.UTF_8));
                } catch (IOException ignored) {
                    // режим диагностики: файл записать не удалось — не критично
                }
                return;
            }

            if (!Files.exists(payload)) {
                fatal("Не найден " + PAYLOAD + " рядом с " + installDir + ".\n\n"
                        + "Положите SparkStudio.exe, spark-launcher.jar и " + PAYLOAD
                        + " в одну папку и запустите снова.");
                return;
            }

            extractIfNeeded(payload, appDir, hash);
            overlayAssets(installDir, appDir);
            pruneOldVersions(appRoot, appDir, MAX_VERSIONS);
            if (!appRoot.equals(dataDir)) {
                // подчищаем версии, оставшиеся от прежней схемы (распаковка в spark.data)
                pruneOldVersions(dataDir, null, 0);
            }

            if (extractOnly) {
                printLn("Распаковано в " + appDir);
                return;
            }

            String classpath = buildClasspath(appDir);

            List<String> cmd = new ArrayList<String>();
            cmd.add(javaBin());
            cmd.add("-Xms256M");
            cmd.add("-Dfile.encoding=UTF-8");
            cmd.add("-Dspark.launcher=root");
            cmd.add("-Dspark.path=" + appDir);
            cmd.add("-Dspark.data=" + dataDir);
            cmd.add("-Dglass.disableGrab=true");
            cmd.add("-cp");
            cmd.add(classpath);
            cmd.add(MAIN_CLASS);
            cmd.addAll(appArgs);

            ProcessBuilder pb = new ProcessBuilder(cmd);
            pb.directory(appDir.toFile());
            pb.inheritIO();

            try {
                Process p = pb.start();
                System.exit(p.waitFor());
            } catch (IOException e) {
                fatal("Не удалось запустить java: " + e.getMessage()
                        + "\n\nИсполняемый файл: " + javaBin() + "\nclasspath: " + classpath);
            }
        } catch (Exception e) {
            fatal("Ошибка лаунчера Spark Studio:\n" + e);
        }
    }

    // ------------------------------------------------------------------

    /** Каталог данных пользователя (spark.data). */
    static Path dataDir() {
        String prop = System.getProperty("spark.data");
        if (prop != null && !prop.trim().isEmpty()) {
            return Paths.get(prop.trim()).toAbsolutePath();
        }

        String env = System.getenv("SPARK_DATA");
        if (env != null && !env.trim().isEmpty()) {
            return Paths.get(env.trim()).toAbsolutePath();
        }

        String home = System.getProperty("user.home", ".");
        String os = System.getProperty("os.name", "").toLowerCase(Locale.ROOT);

        if (os.contains("win")) {
            String appdata = System.getenv("APPDATA");
            Path base = (appdata != null && !appdata.isEmpty())
                    ? Paths.get(appdata) : Paths.get(home, ".SparkStudio");
            return base.resolve("SparkStudio");
        }

        if (os.contains("mac")) {
            return Paths.get(home, "Library", "Application Support", "SparkStudio");
        }

        String xdg = System.getenv("XDG_DATA_HOME");
        Path base = (xdg != null && !xdg.isEmpty())
                ? Paths.get(xdg) : Paths.get(home, ".local", "share");
        return base.resolve("spark-studio");
    }

    /**
     * Каталог для распаковки: рядом с exe (installDir). Если писать туда
     * нельзя — откатываемся к каталогу данных, чтобы программа работала
     * и из Program Files.
     */
    static Path chooseAppRoot(Path installDir, Path dataDir) {
        if (isWritableDir(installDir)) {
            return installDir;
        }
        printLn("Каталог установки недоступен для записи (" + installDir
                + ") — распаковываю в " + dataDir);
        return dataDir;
    }

    static boolean isWritableDir(Path dir) {
        try {
            Files.createDirectories(dir);
            Path probe = Files.createTempFile(dir, ".spark-write-", ".tmp");
            Files.delete(probe);
            return true;
        } catch (Exception e) {
            return false;
        }
    }

    /**
     * Оставляет не более {@code max} распакованных версий (каталогов app-*)
     * в {@code root}. Текущая версия ({@code current}) сохраняется всегда;
     * при {@code max == 0} удаляются все.
     */
    static void pruneOldVersions(Path root, Path current, int max) {
        if (!Files.isDirectory(root)) {
            return;
        }
        File[] children = root.toFile().listFiles();
        if (children == null) {
            return;
        }

        List<File> versions = new ArrayList<File>();
        for (File c : children) {
            if (c.isDirectory() && c.getName().startsWith("app-")) {
                versions.add(c);
            }
        }
        if (versions.isEmpty()) {
            return;
        }

        Collections.sort(versions, new Comparator<File>() {
            public int compare(File a, File b) {
                return Long.compare(b.lastModified(), a.lastModified());
            }
        });

        List<File> keepers = new ArrayList<File>();
        if (current != null) {
            keepers.add(current.toFile().getAbsoluteFile());
        }
        for (File v : versions) {
            if (keepers.size() >= max) {
                break;
            }
            File abs = v.getAbsoluteFile();
            if (!keepers.contains(abs)) {
                keepers.add(abs);
            }
        }

        for (File v : versions) {
            if (!keepers.contains(v.getAbsoluteFile())) {
                printLn("Удаляю старую версию: " + v.getName());
                deleteRecursively(v);
            }
        }
    }

    /** SHA-256/12 для имени каталога версии приложения. */
    static String sha12(Path file) throws IOException, NoSuchAlgorithmException {
        MessageDigest md = MessageDigest.getInstance("SHA-256");
        try (InputStream in = new FileInputStream(file.toFile())) {
            byte[] buf = new byte[1 << 20];
            int n;
            while ((n = in.read(buf)) > 0) {
                md.update(buf, 0, n);
            }
        }
        StringBuilder sb = new StringBuilder();
        for (byte b : md.digest()) {
            sb.append(String.format(Locale.ROOT, "%02x", b & 0xff));
        }
        return sb.substring(0, 12);
    }

    /** Распаковывает binary.jar в appDir, если версия изменилась. */
    static void extractIfNeeded(Path payload, Path appDir, String hash) throws IOException {
        if (appDir.getParent() != null) {
            Files.createDirectories(appDir.getParent());
        }

        Path marker = appDir.resolve(".spark-version");
        if (Files.isRegularFile(marker) && hash.equals(
                new String(Files.readAllBytes(marker), StandardCharsets.UTF_8).trim())) {
            return; // уже распаковано актуальной версии
        }

        printLn("Распаковываю " + PAYLOAD + " -> " + appDir);

        if (Files.exists(appDir)) {
            deleteRecursively(appDir.toFile());
        }
        Files.createDirectories(appDir);

        try (ZipFile zf = new ZipFile(payload.toFile())) {
            Enumeration<? extends ZipEntry> en = zf.entries();
            while (en.hasMoreElements()) {
                ZipEntry e = en.nextElement();
                Path out = appDir.resolve(e.getName());

                if (e.isDirectory()) {
                    Files.createDirectories(out);
                    continue;
                }

                File parent = out.toFile().getParentFile();
                if (parent != null) {
                    parent.mkdirs();
                }
                try (InputStream in = zf.getInputStream(e);
                     OutputStream os = new FileOutputStream(out.toFile())) {
                    byte[] buf = new byte[1 << 20];
                    int n;
                    while ((n = in.read(buf)) > 0) {
                        os.write(buf, 0, n);
                    }
                }
            }
        }

        Files.write(marker, hash.getBytes(StandardCharsets.UTF_8));
    }

    /**
     * Копирует «переносимые» ресурсы из папки сборки (installDir) в рабочий
     * корень (appDir), перекрывая версии из binary.jar. Отсутствующие
     * ресурсы молча пропускаются.
     */
    static void overlayAssets(Path installDir, Path appDir) throws IOException {
        for (String name : PORTABLE_ASSETS) {
            Path src = installDir.resolve(name);
            Path dst = appDir.resolve(name);

            if (Files.isDirectory(src)) {
                copyDir(src, dst);
                printLn("Ассет (каталог): " + name);
            } else if (Files.isRegularFile(src)) {
                File parent = dst.toFile().getParentFile();
                if (parent != null) {
                    parent.mkdirs();
                }
                copyFile(src, dst);
                printLn("Ассет: " + name);
            }
        }
    }

    static void copyFile(Path src, Path dst) throws IOException {
        try (InputStream in = new FileInputStream(src.toFile());
             OutputStream os = new FileOutputStream(dst.toFile())) {
            byte[] buf = new byte[1 << 20];
            int n;
            while ((n = in.read(buf)) > 0) {
                os.write(buf, 0, n);
            }
        }
    }

    static void copyDir(Path src, Path dst) throws IOException {
        Files.createDirectories(dst);
        File[] children = src.toFile().listFiles();
        if (children == null) {
            return;
        }
        for (File c : children) {
            Path cs = c.toPath();
            Path cd = dst.resolve(c.getName());
            if (c.isDirectory()) {
                copyDir(cs, cd);
            } else {
                copyFile(cs, cd);
            }
        }
    }

    static String buildClasspath(Path appDir) {
        List<String> parts = new ArrayList<String>();
        for (String rel : ALL_DIRS) {
            Path d = appDir.resolve(rel);
            if (Files.isDirectory(d)) {
                parts.add(d.toString());
            }
        }
        String cp = join(parts, File.pathSeparator);
        if (cp.isEmpty()) {
            throw new IllegalStateException("Пусто после распаковки: " + appDir);
        }
        return cp;
    }

    static String javaBin() {
        String javaHome = System.getProperty("java.home", "");
        boolean win = System.getProperty("os.name", "").toLowerCase(Locale.ROOT).contains("win");
        String exe = win ? "java.exe" : "java";

        javaHome = new File(javaHome).getAbsolutePath();

        Path jbin = win
                ? Paths.get(javaHome, "bin", exe)
                : Paths.get(javaHome, "bin", exe);

        return Files.isRegularFile(jbin) ? jbin.toString() : exe;
    }

    static void deleteRecursively(File f) {
        if (f.isDirectory()) {
            File[] children = f.listFiles();
            if (children != null) {
                for (File c : children) {
                    deleteRecursively(c);
                }
            }
        }
        f.delete();
    }

    static String join(List<String> list, String sep) {
        StringBuilder sb = new StringBuilder();
        for (String s : list) {
            if (sb.length() > 0) sb.append(sep);
            sb.append(s);
        }
        return sb.toString();
    }

    static void printLn(String s) {
        System.out.println(s);
    }

    static void fatal(String msg) {
        try {
            javax.swing.JOptionPane.showMessageDialog(null, msg, "Spark Studio",
                    javax.swing.JOptionPane.ERROR_MESSAGE);
        } catch (Throwable t) {
            System.err.println(msg);
        }
        System.exit(1);
    }
}