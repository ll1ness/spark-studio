#define _GNU_SOURCE
#include <dlfcn.h>
#include <stdio.h>
#include <stdlib.h>
#include <X11/Xlib.h>
#include <X11/extensions/XTest.h>

static int log_enabled = -1;

static void log_init() {
    if (log_enabled == -1) {
        log_enabled = (getenv("X11_NOGRAB_LOG") != NULL);
    }
}

#define LOG(fmt, ...) do { \
    log_init(); \
    if (log_enabled) { \
        fprintf(stderr, "[x11-nograb] " fmt "\n", ##__VA_ARGS__); \
    } \
} while(0)

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

int XTestFakeButtonEvent(Display *d, unsigned int button, int is_press, unsigned long delay) {
    LOG("XTestFakeButtonEvent(button=%u is_press=%d)", button, is_press);
    return Success;
}

int XTestGrabControl(Display *d, Bool impervious) {
    LOG("XTestGrabControl(impervious=%d)", impervious);
    return Success;
}
