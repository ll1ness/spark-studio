// TechOn UI - Animation System
// Utilities for animations, transitions, and motion effects

(function() {
  'use strict';

  const AnimationSystem = {
    // Easing functions
    easings: {
      linear: t => t,
      easeInQuad: t => t * t,
      easeOutQuad: t => t * (2 - t),
      easeInOutQuad: t => t < 0.5 ? 2 * t * t : -1 + (4 - 2 * t) * t,
      easeInCubic: t => t * t * t,
      easeOutCubic: t => (--t) * t * t + 1,
      easeInOutCubic: t => t < 0.5 ? 4 * t * t * t : (t - 1) * (2 * t - 2) * (2 * t - 2) + 1,
      easeInExpo: t => t === 0 ? 0 : Math.pow(2, 10 * (t - 1)),
      easeOutExpo: t => t === 1 ? 1 : 1 - Math.pow(2, -10 * t),
      easeInOutExpo: t => t < 0.5 ? Math.pow(2, 20 * t - 10) / 2 : (2 - Math.pow(2, -20 * t + 10)) / 2,
      easeInBack: t => 2.70158 * t * t * t - 1.70158 * t * t,
      easeOutBack: t => 1 + 2.70158 * Math.pow(t - 1, 3) + 1.70158 * Math.pow(t - 1, 2),
      easeInOutBack: t => t < 0.5 ? (Math.pow(2 * t, 2) * ((2.5949095 + 1) * 2 * t - 2.5949095)) / 2 : (Math.pow(2 * t - 2, 2) * ((2.5949095 + 2) * (2 * t - 2) + 2.5949095) + 2) / 2,
      spring: (t, stiffness = 100, damping = 10) => {
        const omega = Math.sqrt(stiffness);
        const zeta = damping / (2 * omega);
        return 1 - Math.exp(-zeta * t * omega) * Math.cos(Math.sqrt(1 - zeta * zeta) * omega * t);
      }
    },

    // Animate element
    animate(element, from, to, duration, easing = 'easeInOutQuad', callback) {
      const startTime = performance.now();
      const easingFn = typeof easing === 'function' ? easing : this.easings[easing] || this.easings.linear;

      const update = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easedProgress = easingFn(progress);

        for (const property in to) {
          const fromValue = from[property] || 0;
          const toValue = to[property];
          const currentValue = fromValue + (toValue - fromValue) * easedProgress;
          element.style[property] = currentValue;
        }

        if (progress < 1) {
          requestAnimationFrame(update);
        } else if (callback) {
          callback();
        }
      };

      requestAnimationFrame(update);
    },

    // Slide animation for accordions
    slideDown(element, duration = 300, easing = 'easeInOutQuad') {
      element.style.overflow = 'hidden';
      element.style.height = '0px';
      element.style.display = 'block';
      
      const targetHeight = element.scrollHeight;
      const startTime = performance.now();
      const easingFn = this.easings[easing] || this.easings.linear;

      const update = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easedProgress = easingFn(progress);
        
        element.style.height = (targetHeight * easedProgress) + 'px';

        if (progress < 1) {
          requestAnimationFrame(update);
        } else {
          element.style.height = '';
          element.style.overflow = '';
        }
      };

      requestAnimationFrame(update);
    },

    slideUp(element, duration = 300, easing = 'easeInOutQuad') {
      const startHeight = element.scrollHeight;
      element.style.height = startHeight + 'px';
      element.style.overflow = 'hidden';
      
      const startTime = performance.now();
      const easingFn = this.easings[easing] || this.easings.linear;

      const update = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easedProgress = easingFn(progress);
        
        element.style.height = (startHeight * (1 - easedProgress)) + 'px';

        if (progress < 1) {
          requestAnimationFrame(update);
        } else {
          element.style.display = 'none';
          element.style.height = '';
          element.style.overflow = '';
        }
      };

      requestAnimationFrame(update);
    },

    // Fade animation
    fadeIn(element, duration = 300, easing = 'easeInOutQuad') {
      element.style.opacity = '0';
      element.style.display = 'block';
      
      const startTime = performance.now();
      const easingFn = this.easings[easing] || this.easings.linear;

      const update = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easedProgress = easingFn(progress);
        
        element.style.opacity = easedProgress;

        if (progress < 1) {
          requestAnimationFrame(update);
        } else {
          element.style.opacity = '';
        }
      };

      requestAnimationFrame(update);
    },

    fadeOut(element, duration = 300, easing = 'easeInOutQuad') {
      element.style.opacity = '1';
      
      const startTime = performance.now();
      const easingFn = this.easings[easing] || this.easings.linear;

      const update = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easedProgress = easingFn(progress);
        
        element.style.opacity = 1 - easedProgress;

        if (progress < 1) {
          requestAnimationFrame(update);
        } else {
          element.style.display = 'none';
          element.style.opacity = '';
        }
      };

      requestAnimationFrame(update);
    },

    // Scale animation
    scaleIn(element, duration = 300, easing = 'easeOutBack') {
      element.style.transform = 'scale(0)';
      element.style.opacity = '0';
      element.style.display = 'flex';
      
      const startTime = performance.now();
      const easingFn = this.easings[easing] || this.easings.linear;

      const update = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easedProgress = easingFn(progress);
        
        element.style.transform = `scale(${easedProgress})`;
        element.style.opacity = easedProgress;

        if (progress < 1) {
          requestAnimationFrame(update);
        } else {
          element.style.transform = '';
          element.style.opacity = '';
        }
      };

      requestAnimationFrame(update);
    },

    scaleOut(element, duration = 200, easing = 'easeInQuad') {
      element.style.transform = 'scale(1)';
      element.style.opacity = '1';
      
      const startTime = performance.now();
      const easingFn = this.easings[easing] || this.easings.linear;

      const update = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easedProgress = easingFn(progress);
        
        element.style.transform = `scale(${1 - easedProgress})`;
        element.style.opacity = 1 - easedProgress;

        if (progress < 1) {
          requestAnimationFrame(update);
        } else {
          element.style.display = 'none';
          element.style.transform = '';
          element.style.opacity = '';
        }
      };

      requestAnimationFrame(update);
    },

    // Pulse effect
    pulse(element, scale = 1.05, duration = 200) {
      element.style.transition = `transform ${duration}ms ease`;
      element.style.transform = `scale(${scale})`;
      
      setTimeout(() => {
        element.style.transform = 'scale(1)';
        setTimeout(() => {
          element.style.transition = '';
        }, duration);
      }, duration);
    },

    // Shake effect
    shake(element, intensity = 10, duration = 500) {
      const originalTransform = element.style.transform;
      const startTime = performance.now();
      
      const update = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = elapsed / duration;
        
        if (progress < 1) {
          const shake = Math.sin(progress * Math.PI * 8) * intensity * (1 - progress);
          element.style.transform = `${originalTransform} translateX(${shake}px)`;
          requestAnimationFrame(update);
        } else {
          element.style.transform = originalTransform;
        }
      };
      
      requestAnimationFrame(update);
    },

    // Count number animation
    countTo(element, target, duration = 1000, suffix = '') {
      const start = 0;
      const startTime = performance.now();
      
      const update = (currentTime) => {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        const easedProgress = this.easings.easeOutExpo(progress);
        const current = Math.floor(start + (target - start) * easedProgress);
        
        element.textContent = current + suffix;

        if (progress < 1) {
          requestAnimationFrame(update);
        } else {
          element.textContent = target + suffix;
        }
      };
      
      requestAnimationFrame(update);
    },

    // Intersection Observer for scroll animations
    observeScrollAnimations() {
      const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
          if (entry.isIntersecting) {
            entry.target.classList.add('animate-visible');
            observer.unobserve(entry.target);
          }
        });
      }, { threshold: 0.1 });

      document.querySelectorAll('.animate-on-scroll').forEach(el => {
        observer.observe(el);
      });
    }
  };

  window.toui = window.toui || {};
  window.toui.Animation = AnimationSystem;
})();