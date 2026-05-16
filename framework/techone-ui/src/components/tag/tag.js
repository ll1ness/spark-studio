// TechOn Tag Component
class TechOnTag {
  constructor(element) {
    this.element = element;
    this.variant = element.dataset.variant || 'default';
    this.element.setAttribute('data-variant', this.variant);
  }
}

// Initialize all tag components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-tag:not([data-initialized])').forEach(el => {
    new TechOnTag(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.tag = TechOnTag;
