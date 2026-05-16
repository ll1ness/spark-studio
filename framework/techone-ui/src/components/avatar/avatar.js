// TechOn Avatar Component
class TechOnAvatar {
  constructor(element) {
    this.element = element;
    this.size = element.dataset.size || 'm';
    this.element.setAttribute('data-size', this.size);
  }
}

// Initialize all avatar components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-avatar:not([data-initialized])').forEach(el => {
    new TechOnAvatar(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.avatar = TechOnAvatar;
