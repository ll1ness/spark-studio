/**
 * Spark Studio — Native Windows Launcher
 *
 * Replaces windows.bat with a proper .exe.
 * Finds its bundled JRE, builds the classpath, and launches the IDE.
 *
 * Compile with: x86_64-w64-mingw32-gcc -O2 -s -o bin/SparkStudio.exe bin/launcher.c
 * Or use: python3 bin/build-exe.py
 */

#define WIN32_LEAN_AND_MEAN
#include <windows.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <direct.h>
#include <process.h>

#define STRMAX  4096
#define CPMax  65536

/* ── classpath entries (mirrors windows.bat) ─────────────────────────── */
static const char *cp_entries[] = {
    "runtime\\jphp-core",
    "runtime\\jphp-runtime",
    "gui\\jphp-gui-ext",
    "gui\\jphp-gui-richtext-ext",
    "gui\\jphp-desktop-ext",
    "gui\\jphp-systemtray-ext",
    "gui\\spark-designer",
    "gui\\reactfx-M5",
    "gui\\richtextfx",
    "gui\\undofx",
    "gui\\wellbehavedfx",
    "parser\\antlr4-runtime",
    "parser\\spark-lexer",
    "parser\\jphp-parser",
    "extensions\\jphp-json-ext",
    "extensions\\jphp-xml-ext",
    "extensions\\jphp-zend-ext",
    "extensions\\jphp-zip-ext",
    "database\\HikariCP-java6",
    "database\\jphp-sql-ext",
    "debug\\jphp-debugger",
    "network\\flowless",
    "utils\\asm-all",
    "utils\\commons-codec",
    "utils\\gson",
    "utils\\highlights",
    "utils\\javassist-GA",
    "utils\\slf4j-api",
    "utils\\zt-zip",
    "framework\\SparkStudio",
    "framework\\jphp-app-framework",
    NULL
};

/* ── helpers ─────────────────────────────────────────────────────────── */

/* Remove trailing backslash, if any */
static void chomp_backslash(char *s) {
    size_t n = strlen(s);
    while (n > 0 && (s[n-1] == '\\' || s[n-1] == '/')) s[--n] = '\0';
}

/* Append a path to the classpath string with ; separator */
static void cp_append(char *cp, const char *base, const char *rel) {
    if (cp[0]) strcat(cp, ";");
    strcat(cp, base);
    strcat(cp, "\\");
    strcat(cp, rel);
}

/* Concatenate argv into a single command tail (space-separated, quoted) */
static void build_args(char *buf, size_t bufsz, int argc, char *argv[]) {
    buf[0] = '\0';
    for (int i = 1; i < argc; i++) {
        if (buf[0]) strcat(buf, " ");
        /* quote if contains spaces */
        if (strchr(argv[i], ' ')) {
            char *q = (char *)malloc(strlen(argv[i]) + 3);
            sprintf(q, "\"%s\"", argv[i]);
            strcat(buf, q);
            free(q);
        } else {
            strcat(buf, argv[i]);
        }
    }
}

/* ── main ────────────────────────────────────────────────────────────── */
int main(int argc, char *argv[]) {
    char exe_path    [STRMAX];
    char script_dir  [STRMAX];
    char app_home    [STRMAX];
    char classpath   [CPMax] = "";
    char java_home   [STRMAX];
    char java_exe    [STRMAX];
    char log_dir     [STRMAX];
    char cache1      [STRMAX];
    char cache2      [STRMAX];
    char cmdline     [CPMax + 4096];
    char userprofile [STRMAX];
    char extra_args  [4096] = "";
    char cwd_buf     [STRMAX];
    int  entry_count = sizeof(cp_entries) / sizeof(cp_entries[0]) - 1; /* last is NULL */

    /* ── 1. Resolve directories ──────────────────────────────────────── */
    GetModuleFileNameA(NULL, exe_path, sizeof(exe_path));
    {
        char *p = strrchr(exe_path, '\\');
        if (p) *p = '\0';
    }
    strcpy(script_dir, exe_path);

    strcpy(app_home, script_dir);
    chomp_backslash(app_home);
    {
        char *p = strrchr(app_home, '\\');
        if (p) *p = '\0';   /* remove bin\ -> APP_HOME = parent of bin */
    }

    /* ── 2. Build classpath ──────────────────────────────────────────── */
    for (int i = 0; cp_entries[i] != NULL; i++)
        cp_append(classpath, app_home, cp_entries[i]);

    /* ── 3. Java home ────────────────────────────────────────────────── */
    snprintf(java_home, sizeof(java_home), "%s\\bin\\jre", app_home);
    snprintf(java_exe,  sizeof(java_exe),  "%s\\bin\\java.exe", java_home);

    /* ── 4. JVM options ──────────────────────────────────────────────── */
    char java_opts[4096];
    snprintf(java_opts, sizeof(java_opts),
        "-Xms256M -XX:ReservedCodeCacheSize=150m "
        "-Dsun.io.useCanonCaches=false "
        "-Djava.net.preferIPv4Stack=true "
        "-Dfile.encoding=UTF-8 "
        "-Dspark.launcher=root "
        "-Dspark.path=%s "
        "-Dglass.disableGrab=true",
        app_home);

    /* ── 5. Log dir ──────────────────────────────────────────────────── */
    snprintf(log_dir, sizeof(log_dir), "%s\\bin\\logs", app_home);
    CreateDirectoryA(log_dir, NULL);

    /* ── 6. Clear bytecode caches ────────────────────────────────────── */
    /* user profile */
    GetEnvironmentVariableA("USERPROFILE", userprofile, sizeof(userprofile));
    snprintf(cache1, sizeof(cache1), "%s\\.Spark\\cache\\bytecode_v1", userprofile);
    {
        char cmd[STRMAX];
        snprintf(cmd, sizeof(cmd), "rmdir /s /q \"%s\"", cache1);
        system(cmd);
    }
    /* local cache */
    snprintf(cache2, sizeof(cache2), "%s\\bin\\cache\\bytecode_v1", app_home);
    {
        char cmd[STRMAX];
        snprintf(cmd, sizeof(cmd), "rmdir /s /q \"%s\"", cache2);
        system(cmd);
    }

    /* ── 7. Change to APP_HOME ───────────────────────────────────────── */
    _chdir(app_home);

    /* ── 8. Build command line ───────────────────────────────────────── */
    build_args(extra_args, sizeof(extra_args), argc, argv);

    snprintf(cmdline, sizeof(cmdline),
        "\"%s\" %s -cp \"%s\" org.develnext.jphp.ext.javafx.FXLauncher %s "
        ">\"%s\\output.log\" 2>\"%s\\error.log\"",
        java_exe, java_opts, classpath, extra_args,
        log_dir, log_dir);

    /* ── 9. Launch ───────────────────────────────────────────────────── */
    STARTUPINFOA si;
    PROCESS_INFORMATION pi;
    ZeroMemory(&si, sizeof(si));
    si.cb = sizeof(si);
    ZeroMemory(&pi, sizeof(pi));

    /* Show the console window so user can see errors during startup */
    si.dwFlags = STARTF_USESHOWWINDOW;
    si.wShowWindow = SW_HIDE;    /* hide console window — IDE has its own UI */

    if (!CreateProcessA(NULL, cmdline, NULL, NULL, FALSE,
                        CREATE_NO_WINDOW, NULL, NULL, &si, &pi))
    {
        /* Fallback: show error in a message box */
        char errmsg[STRMAX];
        snprintf(errmsg, sizeof(errmsg),
            "Failed to launch Spark Studio.\n\n"
            "Command:\n%s\n\n"
            "Error code: %lu",
            java_exe, GetLastError());
        MessageBoxA(NULL, errmsg, "Spark Studio", MB_OK | MB_ICONERROR);
        return 1;
    }

    /* Wait for process to finish */
    WaitForSingleObject(pi.hProcess, INFINITE);

    DWORD exit_code = 0;
    GetExitCodeProcess(pi.hProcess, &exit_code);

    CloseHandle(pi.hProcess);
    CloseHandle(pi.hThread);

    return (int)exit_code;
}
