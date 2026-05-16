// TechOn Icon Component
class TechOnIcon {
  constructor(element) {
    this.element = element;
    this.size = element.dataset.size || 'm';
    this.element.setAttribute('data-size', this.size);
  }
}

// Initialize all icon components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-icon:not([data-initialized])').forEach(el => {
    new TechOnIcon(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.icon = TechOnIcon;
