#define _GNU_SOURCE
#include <dlfcn.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <stdint.h>
#include <link.h>
#include <X11/Xlib.h>
#include <X11/extensions/XTest.h>

/* Opaque GDK/GTK types */
typedef struct _GdkDevice GdkDevice;
typedef struct _GdkDisplay GdkDisplay;
typedef struct _GdkScreen GdkScreen;
typedef struct _GdkWindow GdkWindow;
typedef struct _GdkSeat GdkSeat;
typedef struct _GdkCursor GdkCursor;
typedef struct _GdkEvent GdkEvent;
typedef struct _GtkWindow GtkWindow;
typedef struct _GtkWidget GtkWidget;

typedef int gboolean;
typedef unsigned int guint;
typedef unsigned int guint32;
typedef int gint;
typedef uintptr_t GdkGrabStatus;
typedef uintptr_t GdkGrabOwnership;
typedef unsigned long GdkEventMask;
typedef uintptr_t GdkSeatCapabilities;
typedef void (*GdkSeatGrabPrepareFunc)(GdkSeat *seat, GdkWindow *window, void *data);

static int log_enabled = 0;

/* ====== Constructor & dlopen interceptor ====== */

static void set_disable_grab() {
    void *handle = dlopen("libglassgtk3.so", RTLD_LAZY | RTLD_NOLOAD | RTLD_LOCAL);
    if (handle) {
        int *disableGrab = (int *)dlsym(handle, "disableGrab");
        if (disableGrab) { *disableGrab = 1; }
        dlclose(handle);
    }
}

void *dlopen(const char *filename, int flags) {
    static void *(*real_dlopen)(const char *, int) = NULL;
    if (!real_dlopen) real_dlopen = (void *(*)(const char *, int))dlsym(RTLD_NEXT, "dlopen");
    void *handle = real_dlopen(filename, flags);
    if (handle && filename && strstr(filename, "libglassgtk3")) {
        int *disableGrab = (int *)dlsym(handle, "disableGrab");
        if (disableGrab) { *disableGrab = 1; }
    }
    return handle;
}

__attribute__((constructor))
static void init() {
    fprintf(stderr, "[x11-nograb] initializing\n");
    const char *env = getenv("X11_NOGRAB_LOG");
    if (env && env[0] == '1') log_enabled = 1;
    set_disable_grab();
    if (log_enabled) fprintf(stderr, "[x11-nograb] initialized\n");
}

#define LOG(fmt, ...) do { \
    if (log_enabled) { \
        fprintf(stderr, "[x11-nograb] " fmt "\n", ##__VA_ARGS__); \
    } \
} while(0)

/* ===== X11 ===== */

Status XGrabPointer(Display *d, Window w, Bool oe, unsigned int em, int pm, int km, Window ct, Cursor c, Time t) {
    LOG("XGrabPointer(window=%lu)", (unsigned long)w); return Success;
}
int XGrabKeyboard(Display *d, Window w, Bool oe, int pm, int km, Time t) {
    LOG("XGrabKeyboard(window=%lu)", (unsigned long)w); return Success;
}
Status XUngrabPointer(Display *d, Time t) { LOG("XUngrabPointer"); return Success; }
Status XUngrabKeyboard(Display *d, Time t) { LOG("XUngrabKeyboard"); return Success; }
Status XWarpPointer(Display *d, Window s, Window dt, int sx, int sy, unsigned int sw, unsigned int sh, int dx, int dy) {
    LOG("XWarpPointer(x=%d y=%d)", dx, dy); return Success;
}
int XTestFakeMotionEvent(Display *d, int s, int x, int y, unsigned long delay) {
    LOG("XTestFakeMotionEvent(x=%d y=%d)", x, y); return Success;
}
int XTestFakeRelativeMotionEvent(Display *d, int x, int y, unsigned long delay) {
    LOG("XTestFakeRelativeMotionEvent(dx=%d dy=%d)", x, y); return Success;
}

/* ===== GDK3 ===== */

GdkGrabStatus gdk_device_grab(GdkDevice *dev, GdkWindow *win, GdkGrabOwnership own, gboolean oe, GdkEventMask em, GdkCursor *cur, guint32 t) {
    LOG("gdk_device_grab"); return 1;
}
void gdk_device_ungrab(GdkDevice *dev, guint32 t) { LOG("gdk_device_ungrab"); }
GdkGrabStatus gdk_pointer_grab(GdkWindow *win, gboolean oe, GdkEventMask em, GdkWindow *ct, GdkCursor *cur, guint32 t) {
    LOG("gdk_pointer_grab"); return 1;
}
void gdk_pointer_ungrab(guint32 t) { LOG("gdk_pointer_ungrab"); }
GdkGrabStatus gdk_keyboard_grab(GdkWindow *win, gboolean oe, guint32 t) { LOG("gdk_keyboard_grab"); return 1; }
void gdk_keyboard_ungrab(guint32 t) { LOG("gdk_keyboard_ungrab"); }
GdkGrabStatus gdk_seat_grab(GdkSeat *seat, GdkWindow *win, GdkSeatCapabilities caps, gboolean oe, GdkCursor *cur, const GdkEvent *ev, GdkSeatGrabPrepareFunc prep, void *pdata) {
    LOG("gdk_seat_grab"); return 1;
}
void gdk_seat_ungrab(GdkSeat *seat) { LOG("gdk_seat_ungrab"); }
void gdk_display_pointer_ungrab(GdkDisplay *d, guint32 t) { LOG("gdk_display_pointer_ungrab"); }
void gdk_device_warp(GdkDevice *dev, GdkScreen *scr, gint x, gint y) { LOG("gdk_device_warp(x=%d y=%d)", x, y); }
void gdk_display_warp_pointer(GdkDisplay *d, GdkScreen *scr, gint x, gint y) { LOG("gdk_display_warp_pointer(x=%d y=%d)", x, y); }

/* Block modal hint — this is what tells XWayland to constrain input */
void gdk_window_set_modal_hint(GdkWindow *window, gboolean modal) {
    LOG("gdk_window_set_modal_hint(modal=%d) — BLOCKED", modal);
    /* no-op */
}

/* Block transient-for — prevents Wayland special surface handling */
void gdk_window_set_transient_for(GdkWindow *window, GdkWindow *parent) {
    LOG("gdk_window_set_transient_for(parent=%p) — BLOCKED", (void*)parent);
    /* no-op */
}

/* Block type hint — might affect how the WM treats the window */
void gdk_window_set_type_hint(GdkWindow *window, uintptr_t hint) {
    LOG("gdk_window_set_type_hint(hint=%lu) — BLOCKED", (unsigned long)hint);
    /* no-op */
}

/* ===== GTK3 ===== */

void gtk_window_set_modal(GtkWindow *window, gboolean modal) {
    LOG("gtk_window_set_modal(modal=%d) — BLOCKED", modal);
    /* no-op */
}

void gtk_window_set_transient_for(GtkWindow *window, GtkWindow *parent) {
    LOG("gtk_window_set_transient_for(parent=%p) — BLOCKED", (void*)parent);
    /* no-op */
}

void gtk_window_set_type_hint(GtkWindow *window, uintptr_t hint) {
    LOG("gtk_window_set_type_hint(hint=%lu) — BLOCKED", (unsigned long)hint);
    /* no-op */
}

void gtk_window_present(GtkWindow *window) {
    LOG("gtk_window_present — BLOCKED");
    /* no-op: prevents XWayland from focusing/warping to modal on present */
}
