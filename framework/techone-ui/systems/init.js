// TechOn UI - Auto Initialization System
// Automatically initializes all components based on CSS selectors

function initAccordion() {
  document.querySelectorAll('.to-accordion:not([data-initialized])').forEach(el => {
    const trigger = el.querySelector('.to-accordion-trigger');

    trigger?.addEventListener('click', (e) => {
      e.preventDefault();
      const isOpen = el.getAttribute('data-open') === 'true';
      el.setAttribute('data-open', isOpen ? 'false' : 'true');
    });

    el.setAttribute('data-initialized', 'true');
  });
}

function initDropdown() {
  document.querySelectorAll('.to-dropdown:not([data-initialized])').forEach(el => {
    const trigger = el.querySelector('.to-dropdown-trigger');
    let isOpen = false;

    trigger?.addEventListener('click', (e) => {
      e.stopPropagation();
      isOpen = !isOpen;
      el.setAttribute('data-open', isOpen ? 'true' : 'false');
    });

    document.addEventListener('click', (e) => {
      if (!el.contains(e.target) && isOpen) {
        isOpen = false;
        el.setAttribute('data-open', 'false');
      }
    });

    el.querySelectorAll('.to-dropdown-item').forEach(item => {
      item.addEventListener('click', () => {
        isOpen = false;
        el.setAttribute('data-open', 'false');
      });
    });

    el.setAttribute('data-initialized', 'true');
  });
}

function initDialog() {
  document.querySelectorAll('.to-dialog-wrapper:not([data-initialized])').forEach(el => {
    const overlay = el.querySelector('.to-dialog-overlay');
    const closeBtns = el.querySelectorAll('.to-dialog-close');
    const id = el.id;

    document.querySelectorAll(`[data-dialog="${id}"]`).forEach(btn => {
      btn.addEventListener('click', () => {
        el.style.display = 'block';
        document.body.style.overflow = 'hidden';
      });
    });

    closeBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        el.style.display = 'none';
        document.body.style.overflow = '';
      });
    });

    overlay?.addEventListener('click', (e) => {
      if (e.target === overlay) {
        el.style.display = 'none';
        document.body.style.overflow = '';
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && el.style.display === 'block') {
        el.style.display = 'none';
        document.body.style.overflow = '';
      }
    });

    el.setAttribute('data-initialized', 'true');
  });
}

function initScrollTop() {
  document.querySelectorAll('.to-scroll-top:not([data-initialized])').forEach(el => {
    const threshold = parseInt(el.dataset.threshold) || 300;

    function checkVisibility() {
      if (window.pageYOffset > threshold) {
        el.classList.add('visible');
      } else {
        el.classList.remove('visible');
      }
    }

    el.addEventListener('click', () => {
      window.scrollTo({ top: 0, behavior: 'smooth' });
    });

    window.addEventListener('scroll', checkVisibility, { passive: true });
    checkVisibility();

    el.setAttribute('data-initialized', 'true');
  });
}

function initToggleButton() {
  document.querySelectorAll('.to-toggle-button:not([data-initialized])').forEach(el => {
    const group = el.closest('.to-toggle-group');

    el.addEventListener('click', (e) => {
      e.preventDefault();

      if (group) {
        group.querySelectorAll('.to-toggle-button').forEach(btn => {
          if (btn !== el) {
            btn.setAttribute('data-selected', 'false');
          }
        });
      }

      const isSelected = el.getAttribute('data-selected') === 'true';
      el.setAttribute('data-selected', isSelected ? 'false' : 'true');
    });

    el.setAttribute('data-initialized', 'true');
  });
}

function initTooltip() {
  document.querySelectorAll('.to-tooltip:not([data-initialized])').forEach(el => {
    const content = el.querySelector('.to-tooltip-content');
    const position = el.dataset.position || 'top';

    if (content) {
      content.setAttribute('data-position', position);
    }

    el.setAttribute('data-initialized', 'true');
  });
}

export function initAll() {
  initAccordion();
  initDropdown();
  initDialog();
  initScrollTop();
  initToggleButton();
  initTooltip();
}