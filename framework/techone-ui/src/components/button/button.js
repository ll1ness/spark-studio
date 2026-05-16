// TechOn Button Component
class TechOnButton {
  constructor(element) {
    this.element = element;
    this.variant = element.dataset.variant || 'secondary';
    this.size = element.dataset.size || 'm';
    this.disabled = element.disabled || element.hasAttribute('disabled');

    this.element.setAttribute('data-variant', this.variant);
    this.element.setAttribute('data-size', this.size);

    if (this.disabled) {
      this.element.setAttribute('disabled', '');
    }
  }
}

// Initialize all button components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-button:not([data-initialized])').forEach(el => {
    new TechOnButton(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.button = TechOnButton;
