// TechOn UI - Main JavaScript

(function() {
  'use strict';

  function loadComponents() {
    const container = document.getElementById('components-container');
    if (!container) return;

    fetch('components.json')
      .then(r => r.json())
      .then(components => {
        const comps = components.map(name => ({
          name,
          url: `components/${name}/index.html`
        }));
        
        container.innerHTML = '';
        
        const loadPromises = comps.map(comp => {
          const preview = document.createElement('div');
          preview.className = 'component-preview';
          preview.innerHTML = `
            <div class="component-title">${comp.name}</div>
            <div class="component-content" data-component="${comp.name}">
              <span style="color:#666;">Loading...</span>
            </div>
          `;
          container.appendChild(preview);
          
          return fetch(comp.url)
            .then(r => r.text())
            .then(html => {
              const parser = new DOMParser();
              const compDoc = parser.parseFromString(html, 'text/html');
              const body = compDoc.querySelector('body');
              if (body) {
                const content = body.innerHTML;
                const contentEl = preview.querySelector('.component-content');
                contentEl.innerHTML = content;
                
                const scripts = contentEl.querySelectorAll('script');
                scripts.forEach(s => s.remove());
                
                return scripts;
              }
              return [];
            })
            .then(scripts => {
              return new Promise(resolve => {
                requestAnimationFrame(() => {
                  scripts.forEach(s => {
                    if (s.src) return;
                    const newScript = document.createElement('script');
                    newScript.textContent = s.textContent;
                    preview.querySelector('.component-content').appendChild(newScript);
                  });
                  resolve();
                });
              });
            })
            .catch(() => {
              preview.querySelector('.component-content').innerHTML = 
                '<span class="error">Failed to load</span>';
              return Promise.resolve();
            });
        });
        
        Promise.all(loadPromises).then(() => {
          if (window.toui && window.toui.init) {
            window.toui.init();
          }
        });
      })
      .catch(e => {
        container.innerHTML = `<div class="error">Error: ${e.message}</div>`;
      });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', loadComponents);
  } else {
    loadComponents();
  }
})();