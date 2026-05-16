#define _GNU_SOURCE
#include <dlfcn.h>
#include <X11/Xlib.h>

/* XGrabPointer — return Success without actually grabbing on XWayland
   (XWayland doesn't enforce pointer grabs properly anyway) */
Status XGrabPointer(Display *d, Window w, Bool owner_events, unsigned int event_mask, int pointer_mode, int keyboard_mode, Window confine_to, Cursor cursor, Time time) {
    return Success;
}

int XGrabKeyboard(Display *d, Window w, Bool owner_events, int pointer_mode, int keyboard_mode, Time time) {
    return Success;
}

Status XUngrabPointer(Display *d, Time time) {
    return Success;
}

Status XUngrabKeyboard(Display *d, Time time) {
    return Success;
}

/* Block XWarpPointer — prevents JavaFX from teleporting the cursor
   back to the modal dialog when it enters another app window.
   This is the real fix for the "cursor deported back" symptom. */
Status XWarpPointer(Display *d, Window src, Window dest, int src_x, int src_y, unsigned int src_w, unsigned int src_h, int dest_x, int dest_y) {
    return Success;
}
