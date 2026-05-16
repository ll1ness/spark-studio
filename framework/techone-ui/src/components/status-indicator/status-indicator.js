// TechOn Status Indicator Component
class TechOnStatusIndicator {
  constructor(element) {
    this.element = element;
    this.color = element.dataset.color || 'neutral';
    this.element.setAttribute('data-color', this.color);
  }
}

// Initialize all status indicator components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-status-indicator:not([data-initialized])').forEach(el => {
    new TechOnStatusIndicator(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.statusIndicator = TechOnStatusIndicator;
