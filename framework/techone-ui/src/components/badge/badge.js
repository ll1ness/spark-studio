// TechOn Badge Component
class TechOnBadge {
  constructor(element) {
    this.element = element;
    this.variant = element.dataset.variant || 'neutral';
    this.element.setAttribute('data-variant', this.variant);
  }
}

// Initialize all badge components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-badge:not([data-initialized])').forEach(el => {
    new TechOnBadge(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.badge = TechOnBadge;
