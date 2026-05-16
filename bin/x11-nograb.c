#define _GNU_SOURCE
#include <dlfcn.h>
#include <X11/Xlib.h>

typedef Status (*orig_XGrabPointer_t)(Display*, Window, Bool, unsigned int, int, int, Window, Cursor, Time);
typedef int (*orig_XGrabKeyboard_t)(Display*, Window, Bool, int, int, Time);
typedef int (*orig_XGrabButton_t)(Display*, unsigned int, unsigned int, Window, Bool, unsigned int, int, int, Window, Cursor);
typedef int (*orig_XGrabKey_t)(Display*, int, unsigned int, Window, Bool, int, int);
typedef Status (*orig_XUngrabPointer_t)(Display*, Time);
typedef Status (*orig_XUngrabKeyboard_t)(Display*, Time);

Status XGrabPointer(Display *d, Window w, Bool owner_events, unsigned int event_mask, int pointer_mode, int keyboard_mode, Window confine_to, Cursor cursor, Time time) {
    return Success;
}

int XGrabKeyboard(Display *d, Window w, Bool owner_events, int pointer_mode, int keyboard_mode, Time time) {
    return Success;
}

int XGrabButton(Display *d, unsigned int button, unsigned int modifiers, Window grab_window, Bool owner_events, unsigned int event_mask, int pointer_mode, int keyboard_mode, Window confine_to, Cursor cursor) {
    orig_XGrabButton_t orig = (orig_XGrabButton_t)dlsym(RTLD_NEXT, "XGrabButton");
    if (orig) return orig(d, button, modifiers, grab_window, owner_events, event_mask, pointer_mode, keyboard_mode, confine_to, cursor);
    return Success;
}

int XGrabKey(Display *d, int keycode, unsigned int modifiers, Window grab_window, Bool owner_events, int pointer_mode, int keyboard_mode) {
    orig_XGrabKey_t orig = (orig_XGrabKey_t)dlsym(RTLD_NEXT, "XGrabKey");
    if (orig) return orig(d, keycode, modifiers, grab_window, owner_events, pointer_mode, keyboard_mode);
    return Success;
}

Status XUngrabPointer(Display *d, Time time) {
    return Success;
}

Status XUngrabKeyboard(Display *d, Time time) {
    return Success;
}
