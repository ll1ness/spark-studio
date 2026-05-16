// TechOn Progress Component
class TechOnProgress {
  constructor(element) {
    this.element = element;
    this.value = parseFloat(element.dataset.value) || 0;
    this.max = parseFloat(element.dataset.max) || 100;
    this.fill = element.querySelector('.to-progress-fill');
    this.valueEl = element.querySelector('.to-progress-value');

    this.updateProgress();
  }

  updateProgress() {
    if (this.fill) {
      const percentage = Math.min(100, Math.max(0, (this.value / this.max) * 100));
      this.fill.style.width = `${percentage}%`;
    }
    if (this.valueEl) {
      this.valueEl.textContent = `${Math.round((this.value / this.max) * 100)}%`;
    }
  }

  setValue(value) {
    this.value = Math.min(this.max, Math.max(0, value));
    this.updateProgress();
  }
}

// Initialize all progress components
document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('.to-progress:not([data-initialized])').forEach(el => {
    new TechOnProgress(el);
    el.setAttribute('data-initialized', 'true');
  });
});window.toui.progress = TechOnProgress;
