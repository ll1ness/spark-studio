#define _GNU_SOURCE
#include <dlfcn.h>
#include <stdio.h>
#include <stdlib.h>
#include <stdint.h>
#include <X11/Xlib.h>
#include <X11/extensions/XTest.h>

/* Opaque GDK types */
typedef struct _GdkDevice GdkDevice;
typedef struct _GdkDisplay GdkDisplay;
typedef struct _GdkScreen GdkScreen;
typedef struct _GdkWindow GdkWindow;
typedef struct _GdkSeat GdkSeat;
typedef struct _GdkCursor GdkCursor;
typedef struct _GdkEvent GdkEvent;

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

__attribute__((constructor))
static void init() {
    const char *env = getenv("X11_NOGRAB_LOG");
    if (env && env[0] == '1') {
        log_enabled = 1;
    }
    if (log_enabled) {
        fprintf(stderr, "[x11-nograb] library loaded\n");
    }
}

#define LOG(fmt, ...) do { \
    if (log_enabled) { \
        fprintf(stderr, "[x11-nograb] " fmt "\n", ##__VA_ARGS__); \
    } \
} while(0)

/* ===== X11 intercepts ===== */

Status XGrabPointer(Display *d, Window w, Bool owner_events, unsigned int event_mask, int pointer_mode, int keyboard_mode, Window confine_to, Cursor cursor, Time time) {
    LOG("XGrabPointer(window=%lu)", (unsigned long)w);
    return Success;
}

int XGrabKeyboard(Display *d, Window w, Bool owner_events, int pointer_mode, int keyboard_mode, Time time) {
    LOG("XGrabKeyboard(window=%lu)", (unsigned long)w);
    return Success;
}

Status XUngrabPointer(Display *d, Time time) {
    LOG("XUngrabPointer");
    return Success;
}

Status XUngrabKeyboard(Display *d, Time time) {
    LOG("XUngrabKeyboard");
    return Success;
}

Status XWarpPointer(Display *d, Window src, Window dest, int src_x, int src_y, unsigned int src_w, unsigned int src_h, int dest_x, int dest_y) {
    LOG("XWarpPointer(src=%lu dest=%lu x=%d y=%d)", (unsigned long)src, (unsigned long)dest, dest_x, dest_y);
    return Success;
}

int XTestFakeMotionEvent(Display *d, int screen, int x, int y, unsigned long delay) {
    LOG("XTestFakeMotionEvent(screen=%d x=%d y=%d delay=%lu)", screen, x, y, delay);
    return Success;
}

int XTestFakeRelativeMotionEvent(Display *d, int x, int y, unsigned long delay) {
    LOG("XTestFakeRelativeMotionEvent(dx=%d dy=%d delay=%lu)", x, y, delay);
    return Success;
}

/* ===== GDK3 intercepts ===== */

GdkGrabStatus gdk_device_grab(GdkDevice *device, GdkWindow *window, GdkGrabOwnership ownership, gboolean owner_events, GdkEventMask event_mask, GdkCursor *cursor, guint32 time) {
    LOG("gdk_device_grab(window=%p)", (void*)window);
    return 1;
}

void gdk_device_ungrab(GdkDevice *device, guint32 time) {
    LOG("gdk_device_ungrab(device=%p)", (void*)device);
}

GdkGrabStatus gdk_pointer_grab(GdkWindow *window, gboolean owner_events, GdkEventMask event_mask, GdkWindow *confine_to, GdkCursor *cursor, guint32 time) {
    LOG("gdk_pointer_grab(window=%p)", (void*)window);
    return 1;
}

void gdk_pointer_ungrab(guint32 time) {
    LOG("gdk_pointer_ungrab()");
}

GdkGrabStatus gdk_keyboard_grab(GdkWindow *window, gboolean owner_events, guint32 time) {
    LOG("gdk_keyboard_grab(window=%p)", (void*)window);
    return 1;
}

void gdk_keyboard_ungrab(guint32 time) {
    LOG("gdk_keyboard_ungrab()");
}

GdkGrabStatus gdk_seat_grab(GdkSeat *seat, GdkWindow *window, GdkSeatCapabilities capabilities, gboolean owner_events, GdkCursor *cursor, const GdkEvent *event, GdkSeatGrabPrepareFunc prepare_func, void *prepare_data) {
    LOG("gdk_seat_grab(seat=%p window=%p)", (void*)seat, (void*)window);
    return 1;
}

void gdk_seat_ungrab(GdkSeat *seat) {
    LOG("gdk_seat_ungrab(seat=%p)", (void*)seat);
}

void gdk_display_pointer_ungrab(GdkDisplay *display, guint32 time) {
    LOG("gdk_display_pointer_ungrab(display=%p)", (void*)display);
}

void gdk_device_warp(GdkDevice *device, GdkScreen *screen, gint x, gint y) {
    LOG("gdk_device_warp(device=%p x=%d y=%d)", (void*)device, x, y);
}

void gdk_display_warp_pointer(GdkDisplay *display, GdkScreen *screen, gint x, gint y) {
    LOG("gdk_display_warp_pointer(display=%p x=%d y=%d)", (void*)display, x, y);
}
