// TechOn Breadcrumbs Component
class TechOnBreadcrumbs {
  constructor(element) {
    this.element = element;
  }
}

// Initialize all breadcrumbs components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-breadcrumbs:not([data-initialized])').forEach(el => {
    new TechOnBreadcrumbs(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.breadcrumbs = TechOnBreadcrumbs;
