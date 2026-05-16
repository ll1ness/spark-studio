// TechOn UI - Interaction System
// Handles all user interactions: clicks, keyboard, touch, drag, hover

(function() {
  'use strict';

  const InteractionSystem = {
    // Click handler with debounce
    handleClick(element, callback, debounceMs = 0) {
      let timeout;
      element.addEventListener('click', (e) => {
        if (debounceMs > 0) {
          if (timeout) return;
          timeout = setTimeout(() => { timeout = null; }, debounceMs);
        }
        callback(e, element);
      });
    },

    // Double click handler
    handleDoubleClick(element, callback) {
      element.addEventListener('dblclick', (e) => callback(e, element));
    },

    // Long press handler
    handleLongPress(element, callback, duration = 500) {
      let pressTimer;
      element.addEventListener('touchstart', (e) => {
        pressTimer = setTimeout(() => callback(e, element), duration);
      });
      element.addEventListener('touchend', () => clearTimeout(pressTimer));
      element.addEventListener('touchmove', () => clearTimeout(pressTimer));
      element.addEventListener('mousedown', (e) => {
        pressTimer = setTimeout(() => callback(e, element), duration);
      });
      element.addEventListener('mouseup', () => clearTimeout(pressTimer));
      element.addEventListener('mouseleave', () => clearTimeout(pressTimer));
    },

    // Keyboard shortcuts
    handleKeyboard(shortcut, callback, target = document) {
      target.addEventListener('keydown', (e) => {
        const keys = shortcut.toLowerCase().split('+').map(k => k.trim());
        const ctrl = keys.includes('ctrl') || keys.includes('cmd');
        const shift = keys.includes('shift');
        const alt = keys.includes('alt');
        const key = keys.filter(k => !['ctrl', 'cmd', 'shift', 'alt'].includes(k))[0];

        const ctrlMatch = (ctrl && (e.ctrlKey || e.metaKey)) || !ctrl;
        const shiftMatch = shift === e.shiftKey;
        const altMatch = alt === e.altKey;
        const keyMatch = e.key.toLowerCase() === key;

        if (ctrlMatch && shiftMatch && altMatch && keyMatch) {
          e.preventDefault();
          callback(e);
        }
      });
    },

    // Hover effects
    handleHover(element, onEnter, onLeave) {
      element.addEventListener('mouseenter', (e) => {
        element.classList.add('hover');
        if (onEnter) onEnter(e, element);
      });
      element.addEventListener('mouseleave', (e) => {
        element.classList.remove('hover');
        if (onLeave) onLeave(e, element);
      });
    },

    // Touch swipe detection
    detectSwipe(element, callbacks = {}) {
      let touchStartX = 0;
      let touchStartY = 0;
      let touchEndX = 0;
      let touchEndY = 0;

      element.addEventListener('touchstart', (e) => {
        touchStartX = e.changedTouches[0].screenX;
        touchStartY = e.changedTouches[0].screenY;
      }, { passive: true });

      element.addEventListener('touchend', (e) => {
        touchEndX = e.changedTouches[0].screenX;
        touchEndY = e.changedTouches[0].screenY;
        handleSwipe();
      }, { passive: true });

      function handleSwipe() {
        const deltaX = touchEndX - touchStartX;
        const deltaY = touchEndY - touchStartY;
        const minSwipe = 50;

        if (Math.abs(deltaX) > Math.abs(deltaY)) {
          if (deltaX > minSwipe && callbacks.onSwipeRight) callbacks.onSwipeRight(element);
          if (deltaX < -minSwipe && callbacks.onSwipeLeft) callbacks.onSwipeLeft(element);
        } else {
          if (deltaY > minSwipe && callbacks.onSwipeDown) callbacks.onSwipeDown(element);
          if (deltaY < -minSwipe && callbacks.onSwipeUp) callbacks.onSwipeUp(element);
        }
      }
    },

    // Drag and drop
    enableDragDrop(element, callbacks = {}) {
      let isDragging = false;
      let dragHandle = callbacks.handle || element;
      let offsetX, offsetY;

      dragHandle.addEventListener('mousedown', (e) => {
        isDragging = true;
        offsetX = e.clientX - element.offsetLeft;
        offsetY = e.clientY - element.offsetTop;
        element.style.position = 'absolute';
        element.style.zIndex = '9999';
        if (callbacks.onDragStart) callbacks.onDragStart(e, element);
      });

      document.addEventListener('mousemove', (e) => {
        if (!isDragging) return;
        element.style.left = (e.clientX - offsetX) + 'px';
        element.style.top = (e.clientY - offsetY) + 'px';
        if (callbacks.onDrag) callbacks.onDrag(e, element);
      });

      document.addEventListener('mouseup', () => {
        if (isDragging) {
          isDragging = false;
          if (callbacks.onDragEnd) callbacks.onDragEnd(element);
        }
      });
    },

    // Focus/blur handlers
    handleFocus(element, onFocus, onBlur) {
      element.addEventListener('focus', (e) => {
        element.classList.add('focused');
        if (onFocus) onFocus(e, element);
      });
      element.addEventListener('blur', (e) => {
        element.classList.remove('focused');
        if (onBlur) onBlur(e, element);
      });
    },

    // Resize observer
    observeResize(element, callback) {
      const observer = new ResizeObserver((entries) => {
        entries.forEach(entry => callback(entry.contentRect, element));
      });
      observer.observe(element);
      return observer;
    },

    // Mutation observer
    observeMutations(element, callback, options = {}) {
      const observer = new MutationObserver((mutations) => {
        mutations.forEach(mutation => callback(mutation, element));
      });
      observer.observe(element, {
        childList: options.childList !== false,
        subtree: options.subtree !== false,
        attributes: options.attributes,
        attributeFilter: options.attributeFilter
      });
      return observer;
    },

    // Click outside detection
    onClickOutside(element, callback) {
      const handler = (e) => {
        if (!element.contains(e.target)) {
          callback(e);
        }
      };
      document.addEventListener('click', handler);
      return () => document.removeEventListener('click', handler);
    },

    // Scroll position detection
    onScrollToBottom(element, callback, threshold = 100) {
      const handler = () => {
        const scrollTop = element.scrollTop;
        const scrollHeight = element.scrollHeight;
        const clientHeight = element.clientHeight;
        
        if (scrollHeight - scrollTop - clientHeight < threshold) {
          callback();
        }
      };
      element.addEventListener('scroll', handler);
      return handler;
    },

    // Form validation helpers
    validateInput(input, rules) {
      const value = input.value;
      const errors = [];

      if (rules.required && !value) errors.push('Required field');
      if (rules.minLength && value.length < rules.minLength) errors.push(`Minimum ${rules.minLength} characters`);
      if (rules.maxLength && value.length > rules.maxLength) errors.push(`Maximum ${rules.maxLength} characters`);
      if (rules.pattern && !rules.pattern.test(value)) errors.push('Invalid format');
      if (rules.email && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) errors.push('Invalid email');
      if (rules.url) {
        const urlRegex = /^https?:\/\//;
        if (!urlRegex.test(value)) errors.push('Invalid URL');
      }

      return { valid: errors.length === 0, errors };
    }
  };

  window.TechOnUI = window.TechOnUI || {};
  window.TechOnUI.Interaction = InteractionSystem;
})();