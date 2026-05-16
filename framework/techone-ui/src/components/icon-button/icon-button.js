// TechOn Icon Button Component
class TechOnIconButton {
  constructor(element) {
    this.element = element;
    this.size = element.dataset.size || 'm';
    this.element.setAttribute('data-size', this.size);
  }
}

// Initialize all icon button components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-icon-button:not([data-initialized])').forEach(el => {
    new TechOnIconButton(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.iconButton = TechOnIconButton;
