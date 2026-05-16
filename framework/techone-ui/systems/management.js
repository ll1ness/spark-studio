// TechOn UI - Management System
// State management, event bus, and component registry

(function() {
  'use strict';

  const ManagementSystem = {
    // Simple state store
    store: {},

    createStore(initialState = {}) {
      const listeners = [];
      let state = { ...initialState };

      return {
        getState: () => ({ ...state }),
        
        setState: (newState) => {
          const prevState = { ...state };
          state = { ...state, ...newState };
          listeners.forEach(fn => fn(state, prevState));
        },
        
        subscribe: (listener) => {
          listeners.push(listener);
          return () => {
            const index = listeners.indexOf(listener);
            if (index > -1) listeners.splice(index, 1);
          };
        }
      };
    },

    // Global event bus
    events: {
      listeners: {},

      on(event, callback) {
        if (!this.listeners[event]) this.listeners[event] = [];
        this.listeners[event].push(callback);
        return () => this.off(event, callback);
      },

      off(event, callback) {
        if (!this.listeners[event]) return;
        const index = this.listeners[event].indexOf(callback);
        if (index > -1) this.listeners[event].splice(index, 1);
      },

      emit(event, data) {
        if (!this.listeners[event]) return;
        this.listeners[event].forEach(cb => cb(data));
      },

      once(event, callback) {
        const wrapper = (data) => {
          callback(data);
          this.off(event, wrapper);
        };
        this.on(event, wrapper);
      }
    },

    // Component registry
    components: {},

    register(name, component) {
      this.components[name] = component;
      this.events.emit('component:registered', { name, component });
    },

    get(name) {
      return this.components[name];
    },

    getAll() {
      return { ...this.components };
    },

    // Utility: debounce
    debounce(fn, delay) {
      let timeout;
      return (...args) => {
        clearTimeout(timeout);
        timeout = setTimeout(() => fn(...args), delay);
      };
    },

    // Utility: throttle
    throttle(fn, limit) {
      let inThrottle;
      return (...args) => {
        if (!inThrottle) {
          fn(...args);
          inThrottle = true;
          setTimeout(() => inThrottle = false, limit);
        }
      };
    },

    // Utility: once
    once(fn) {
      let called = false;
      let result;
      return (...args) => {
        if (!called) {
          called = true;
          result = fn(...args);
        }
        return result;
      };
    },

    // Utility: memoize
    memoize(fn) {
      const cache = new Map();
      return (...args) => {
        const key = JSON.stringify(args);
        if (cache.has(key)) return cache.get(key);
        const result = fn(...args);
        cache.set(key, result);
        return result;
      };
    },

    // Cookie helpers
    cookie: {
      get(name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        return match ? decodeURIComponent(match[2]) : null;
      },

      set(name, value, days = 7) {
        const expires = new Date(Date.now() + days * 864e5).toUTCString();
        document.cookie = `${name}=${encodeURIComponent(value)}; expires=${expires}; path=/`;
      },

      remove(name) {
        document.cookie = `${name}=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/`;
      }
    },

    // LocalStorage helpers
    storage: {
      get(key, defaultValue = null) {
        try {
          const item = localStorage.getItem(key);
          return item ? JSON.parse(item) : defaultValue;
        } catch {
          return defaultValue;
        }
      },

      set(key, value) {
        try {
          localStorage.setItem(key, JSON.stringify(value));
          return true;
        } catch {
          return false;
        }
      },

      remove(key) {
        localStorage.removeItem(key);
      },

      clear() {
        localStorage.clear();
      }
    },

    // UUID generator
    uuid() {
      return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, c => {
        const r = Math.random() * 16 | 0;
        const v = c === 'x' ? r : (r & 0x3 | 0x8);
        return v.toString(16);
      });
    },

    // Deep clone
    deepClone(obj) {
      return JSON.parse(JSON.stringify(obj));
    },

    // Merge objects deeply
    deepMerge(target, ...sources) {
      if (!sources.length) return target;
      const source = sources.shift();
      if (this.isObject(target) && this.isObject(source)) {
        for (const key in source) {
          if (this.isObject(source[key])) {
            if (!target[key]) Object.assign(target, { [key]: {} });
            this.deepMerge(target[key], source[key]);
          } else {
            Object.assign(target, { [key]: source[key] });
          }
        }
      }
      return this.deepMerge(target, ...sources);
    },

    isObject(item) {
      return item && typeof item === 'object' && !Array.isArray(item);
    },

    // Random ID generator
    randomId(prefix = '') {
      return prefix + Math.random().toString(36).substr(2, 9);
    },

    // Copy to clipboard
    copyToClipboard(text) {
      if (navigator.clipboard) {
        return navigator.clipboard.writeText(text);
      }
      const textarea = document.createElement('textarea');
      textarea.value = text;
      textarea.style.position = 'fixed';
      textarea.style.opacity = '0';
      document.body.appendChild(textarea);
      textarea.select();
      document.execCommand('copy');
      document.body.removeChild(textarea);
      return Promise.resolve();
    }
  };

  window.TechOnUI = window.TechOnUI || {};
  window.TechOnUI.Management = ManagementSystem;
})();