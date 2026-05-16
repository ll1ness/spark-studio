// TechOn Banner Component
class TechOnBanner {
  constructor(element) {
    this.element = element;
    this.variant = element.dataset.variant || 'info';
    this.element.setAttribute('data-variant', this.variant);
  }
}

// Initialize all banner components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-banner:not([data-initialized])').forEach(el => {
    new TechOnBanner(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.banner = TechOnBanner;
