// TechOn Tooltip Component
class TechOnTooltip {
  constructor(element) {
    this.element = element;
    this.content = element.querySelector('.to-tooltip-content');
    this.position = element.dataset.position || 'top';
    if (this.content) {
      this.content.setAttribute('data-position', this.position);
    }
  }
}

// Initialize all tooltip components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-tooltip:not([data-initialized])').forEach(el => {
    new TechOnTooltip(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.tooltip = TechOnTooltip;
