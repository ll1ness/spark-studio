// TechOn Dialog Component
class TechOnDialog {
  constructor(element) {
    this.element = element;
    this.id = element.id;
    this.overlay = element.querySelector('.to-dialog-overlay');
    this.closeBtn = element.querySelector('.to-dialog-close');

    document.querySelectorAll(`[data-dialog="${this.id}"]`).forEach(btn => {
      btn.addEventListener('click', () => this.open());
    });

    this.closeBtn?.addEventListener('click', () => this.close());
    this.overlay?.addEventListener('click', (e) => {
      if (e.target === this.overlay) this.close();
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && this.element.style.display === 'block') {
        this.close();
      }
    });
  }

  open() {
    this.element.style.display = 'block';
    document.body.style.overflow = 'hidden';
  }

  close() {
    this.element.style.display = 'none';
    document.body.style.overflow = '';
  }
}window.toui.dialog = TechOnDialog;
