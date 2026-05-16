// TechOn Flex Component
class TechOnFlex {
  constructor(element) {
    this.element = element;
    this.direction = element.dataset.direction || 'row';
    this.gap = element.dataset.gap || 'm';

    this.element.setAttribute('data-direction', this.direction);
    this.element.setAttribute('data-gap', this.gap);
  }
}

// Initialize all flex components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-flex:not([data-initialized])').forEach(el => {
    new TechOnFlex(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.flex = TechOnFlex;
