#define _GNU_SOURCE
#include <dlfcn.h>
#include <stdio.h>
#include <stdlib.h>
#include <string.h>
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
typedef void (*GdkSeatGrabPrepareFunc)(GdkSeat *, GdkWindow *, void *);

/* ====== dlopen interceptor: set disableGrab=1 in libglassgtk3 ====== */

void *dlopen(const char *filename, int flags) {
    static void *(*real_dlopen)(const char *, int) = NULL;
    if (!real_dlopen) real_dlopen = (void *(*)(const char *, int))dlsym(RTLD_NEXT, "dlopen");
    void *handle = real_dlopen(filename, flags);
    if (handle && filename && strstr(filename, "libglassgtk3")) {
        int *disableGrab = (int *)dlsym(handle, "disableGrab");
        if (disableGrab) *disableGrab = 1;
    }
    return handle;
}

__attribute__((constructor))
static void init() {
    void *h = dlopen("libglassgtk3.so", RTLD_LAZY | RTLD_NOLOAD);
    if (h) {
        int *dg = (int *)dlsym(h, "disableGrab");
        if (dg) *dg = 1;
        dlclose(h);
    }
}

/* ===== X11 grab/warp intercepts ===== */

Status XGrabPointer(Display *disp, Window win, Bool oe, unsigned int em, int pm, int km, Window ct, Cursor cur, Time tm) {
    return Success;
}
int XGrabKeyboard(Display *disp, Window win, Bool oe, int pm, int km, Time tm) {
    return Success;
}
Status XUngrabPointer(Display *disp, Time tm) { return Success; }
Status XUngrabKeyboard(Display *disp, Time tm) { return Success; }
Status XWarpPointer(Display *disp, Window wsrc, Window wdst, int sx, int sy, unsigned int w, unsigned int h, int dx, int dy) {
    return Success;
}
int XTestFakeMotionEvent(Display *disp, int sc, int x, int y, unsigned long delay) { return Success; }
int XTestFakeRelativeMotionEvent(Display *disp, int dx, int dy, unsigned long delay) { return Success; }

/* ===== GDK grab/warp intercepts ===== */

GdkGrabStatus gdk_device_grab(GdkDevice *dev, GdkWindow *win, GdkGrabOwnership own, gboolean oe, GdkEventMask em, GdkCursor *cur, guint32 tm) { return 1; }
void gdk_device_ungrab(GdkDevice *dev, guint32 tm) {}
GdkGrabStatus gdk_pointer_grab(GdkWindow *win, gboolean oe, GdkEventMask em, GdkWindow *ct, GdkCursor *cur, guint32 tm) { return 1; }
void gdk_pointer_ungrab(guint32 tm) {}
GdkGrabStatus gdk_keyboard_grab(GdkWindow *win, gboolean oe, guint32 tm) { return 1; }
void gdk_keyboard_ungrab(guint32 tm) {}
GdkGrabStatus gdk_seat_grab(GdkSeat *seat, GdkWindow *win, GdkSeatCapabilities caps, gboolean oe, GdkCursor *cur, const GdkEvent *ev, GdkSeatGrabPrepareFunc prep, void *pdata) { return 1; }
void gdk_seat_ungrab(GdkSeat *seat) {}
void gdk_display_pointer_ungrab(GdkDisplay *disp, guint32 tm) {}
void gdk_device_warp(GdkDevice *dev, GdkScreen *sc, gint x, gint y) {}
void gdk_display_warp_pointer(GdkDisplay *disp, GdkScreen *sc, gint x, gint y) {}
