// TechOn Accordion Component
class TechOnAccordion {
  constructor(element) {
    this.element = element;
    this.trigger = element.querySelector('.to-accordion-trigger');
    this.content = element.querySelector('.to-accordion-content');
    this.isOpen = element.getAttribute('data-open') === 'true';

    if (this.trigger) {
      this.trigger.addEventListener('click', (e) => {
        e.preventDefault();
        this.toggle();
      });
    }
  }

  toggle() {
    this.isOpen = !this.isOpen;
    this.element.setAttribute('data-open', this.isOpen ? 'true' : 'false');
  }
}window.toui.accordion = TechOnAccordion;
