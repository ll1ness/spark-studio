// TechOn UI - Animated Geometric Background
// Subtle algebra/geometry patterns with slow animations

(function() {
  'use strict';

  const style = document.createElement('style');
  style.id = 'techon-bg';
  style.textContent = `
    @keyframes bg-drift {
      0% { transform: translate(0, 0) rotate(0deg); }
      33% { transform: translate(2px, -1px) rotate(0.5deg); }
      66% { transform: translate(-1px, 2px) rotate(-0.3deg); }
      100% { transform: translate(0, 0) rotate(0deg); }
    }
    @keyframes bg-float {
      0%, 100% { transform: translateY(0) scale(1); }
      50% { transform: translateY(-4px) scale(1.02); }
    }
    @keyframes bg-pulse-subtle {
      0%, 100% { opacity: 0.3; }
      50% { opacity: 0.5; }
    }
    @keyframes bg-grid-scroll {
      0% { transform: translate(0, 0); }
      100% { transform: translate(-40px, -40px); }
    }
    body::before {
      content: '';
      position: fixed;
      inset: 0;
      pointer-events: none;
      z-index: 0;
      background-image:
        linear-gradient(rgba(255,255,255,.02) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,.02) 1px, transparent 1px);
      background-size: 80px 80px;
      animation: bg-grid-scroll 120s linear infinite;
      mask-image: radial-gradient(ellipse at 50% 50%, black 30%, transparent 70%);
      -webkit-mask-image: radial-gradient(ellipse at 50% 50%, black 30%, transparent 70%);
    }
    .bg-shape {
      position: fixed;
      pointer-events: none;
      z-index: 0;
      border: 1px solid rgba(255,255,255,.04);
      border-radius: 50%;
      animation: bg-float 20s ease-in-out infinite;
    }
    .bg-shape:nth-child(1) {
      width: 300px; height: 300px;
      top: -100px; right: -50px;
      animation-delay: 0s;
      border-radius: 40%;
    }
    .bg-shape:nth-child(2) {
      width: 200px; height: 200px;
      bottom: 10%; left: -60px;
      animation-delay: -5s;
      border-radius: 30% 50%;
    }
    .bg-shape:nth-child(3) {
      width: 150px; height: 150px;
      top: 40%; right: 10%;
      animation-delay: -10s;
      border-radius: 20% 60% 40% 50%;
      border-color: rgba(255,255,255,.025);
    }
    .bg-shape:nth-child(4) {
      width: 80px; height: 80px;
      top: 15%; left: 20%;
      animation-delay: -3s;
      animation: bg-drift 30s ease-in-out infinite;
      border-color: rgba(255,255,255,.03);
    }
    .bg-shape:nth-child(5) {
      width: 120px; height: 120px;
      bottom: 30%; right: 30%;
      animation-delay: -8s;
      border-radius: 50% 20% 50% 20%;
      animation: bg-pulse-subtle 8s ease-in-out infinite;
    }
  `;
  document.head.appendChild(style);

  function initBg() {
    const shapes = document.createElement('div');
    shapes.innerHTML = '<div class="bg-shape"></div><div class="bg-shape"></div><div class="bg-shape"></div><div class="bg-shape"></div><div class="bg-shape"></div>';
    document.body.prepend(shapes);
    window.toui = window.toui || {};
    window.toui.Background = true;
  }
  if (document.body) {
    initBg();
  } else {
    document.addEventListener('DOMContentLoaded', initBg);
  }
})();
