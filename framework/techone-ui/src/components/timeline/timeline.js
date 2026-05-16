// TechOn Timeline Component
class TechOnTimeline {
  constructor(element) {
    this.element = element;
  }
}

// Initialize all timeline components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-timeline:not([data-initialized])').forEach(el => {
    new TechOnTimeline(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.timeline = TechOnTimeline;
