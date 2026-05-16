// TechOn Grid Component
class TechOnGrid {
  constructor(element) {
    this.element = element;
    this.columns = element.dataset.columns || '2';
    this.gap = element.dataset.gap || 'm';

    this.element.setAttribute('data-columns', this.columns);
    this.element.setAttribute('data-gap', this.gap);
  }
}

// Initialize all grid components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-grid:not([data-initialized])').forEach(el => {
    new TechOnGrid(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.grid = TechOnGrid;
