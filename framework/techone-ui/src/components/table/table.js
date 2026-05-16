// TechOn Table Component
class TechOnTable {
  constructor(element) {
    this.element = element;
  }
}

// Initialize all table components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-table:not([data-initialized])').forEach(el => {
    new TechOnTable(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.table = TechOnTable;
