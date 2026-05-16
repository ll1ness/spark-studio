// TechOn Dropdown Component
class TechOnDropdown {
  constructor(element) {
    this.element = element;
    this.trigger = element.querySelector('.to-dropdown-trigger');
    this.menu = element.querySelector('.to-dropdown-menu');
    this.open = false;

    this.element.setAttribute('data-open', 'false');

    if (this.trigger) {
      this.trigger.addEventListener('click', (e) => {
        e.stopPropagation();
        if (this.open) this.close(); else this.open_();
      });
    }

    document.addEventListener('click', (e) => {
      if (!this.element.contains(e.target) && this.open) {
        this.close();
      }
    });
  }

  positionMenu() {
    if (!this.trigger || !this.menu) return;
    const rect = this.trigger.getBoundingClientRect();
    this.menu.style.position = 'fixed';
    this.menu.style.top = (rect.bottom + 8) + 'px';
    this.menu.style.left = rect.left + 'px';
    this.menu.style.width = Math.max(200, rect.width) + 'px';
  }

  open_() {
    this.open = true;
    this.element.setAttribute('data-open', 'true');
    this.positionMenu();
    this._onScroll = () => this.positionMenu();
    this._onResize = () => this.positionMenu();
    window.addEventListener('scroll', this._onScroll, { passive: true });
    window.addEventListener('resize', this._onResize, { passive: true });
  }

  close() {
    this.open = false;
    this.element.setAttribute('data-open', 'false');
    if (this.menu) {
      this.menu.style.position = '';
      this.menu.style.top = '';
      this.menu.style.left = '';
      this.menu.style.width = '';
    }
    window.removeEventListener('scroll', this._onScroll);
    window.removeEventListener('resize', this._onResize);
  }
}
window.toui.dropdown = TechOnDropdown;
