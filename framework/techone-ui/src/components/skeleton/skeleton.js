// TechOn Skeleton Component
class TechOnSkeleton {
  constructor(element) {
    this.element = element;
  }
}

// Initialize all skeleton components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-skeleton:not([data-initialized])').forEach(el => {
    new TechOnSkeleton(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.skeleton = TechOnSkeleton;
