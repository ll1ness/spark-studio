// TechOn Pulse Component
class TechOnPulse {
  constructor(element) {
    this.element = element;
    this.color = element.dataset.color || 'primary';
    this.element.setAttribute('data-color', this.color);
  }
}

// Initialize all pulse components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-pulse:not([data-initialized])').forEach(el => {
    new TechOnPulse(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.pulse = TechOnPulse;
