// TechOn Toggle Button Component
class TechOnToggleButton {
  constructor(element) {
    this.element = element;
    this.selected = element.getAttribute('data-selected') === 'true';
    this.group = element.closest('.to-toggle-group');

    this.element.addEventListener('click', (e) => {
      e.preventDefault();

      if (this.group) {
        if (this.selected) return;
        this.group.querySelectorAll('.to-toggle-button').forEach(btn => {
          const sel = btn === this.element;
          btn.setAttribute('data-selected', sel ? 'true' : 'false');
        });
        this.selected = true;
      } else {
        this.toggle();
      }
    });
  }

  toggle() {
    this.selected = !this.selected;
    this.element.setAttribute('data-selected', this.selected ? 'true' : 'false');
  }

  setSelected(selected) {
    this.selected = selected;
    this.element.setAttribute('data-selected', selected ? 'true' : 'false');
  }
}window.toui.toggleButton = TechOnToggleButton;
