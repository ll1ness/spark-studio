#define _GNU_SOURCE
#include <dlfcn.h>
#include <stdint.h>

/* Opaque GdkWindow type */
typedef struct _GdkWindow GdkWindow;
typedef struct _GtkWindow GtkWindow;
typedef int gboolean;

/* Only block modal hint — prevents XWayland from constraining pointer.
   Do NOT block transient_for or type_hint (needed for centering). */
void gdk_window_set_modal_hint(GdkWindow *window, gboolean modal) {
    /* no-op */
}

void gtk_window_set_modal(GtkWindow *window, gboolean modal) {
    /* no-op */
}
