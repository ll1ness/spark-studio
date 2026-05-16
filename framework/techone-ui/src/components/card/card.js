// TechOn Card Component
class TechOnCard {
  constructor(element) {
    this.element = element;
    this.variant = element.dataset.variant || 'default';
    this.element.setAttribute('data-variant', this.variant);
  }
}

// Initialize all card components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-card:not([data-initialized])').forEach(el => {
    new TechOnCard(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.card = TechOnCard;
