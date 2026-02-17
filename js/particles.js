/**
 * particles.js — Lightweight canvas particle effect
 * Creates floating anime-themed sparkles on a canvas overlay
 */

class ParticleSystem {
    constructor(canvasId) {
        this.canvas = document.getElementById(canvasId);
        if (!this.canvas) return;

        this.ctx = this.canvas.getContext('2d');
        this.particles = [];
        this.maxParticles = 40;
        this.animationId = null;

        this.resize();
        window.addEventListener('resize', () => this.resize());
        this.init();
        this.animate();
    }

    resize() {
        if (!this.canvas) return;
        this.canvas.width = this.canvas.parentElement.offsetWidth;
        this.canvas.height = this.canvas.parentElement.offsetHeight;
    }

    init() {
        for (let i = 0; i < this.maxParticles; i++) {
            this.particles.push(this.createParticle());
        }
    }

    createParticle() {
        const colors = [
            'rgba(255, 211, 0, 0.5)',
            'rgba(255, 228, 77, 0.35)',
            'rgba(204, 170, 0, 0.4)',
            'rgba(117, 123, 129, 0.3)',
            'rgba(255, 255, 255, 0.2)',
            'rgba(255, 211, 0, 0.25)'
        ];
        return {
            x: Math.random() * (this.canvas?.width || 800),
            y: Math.random() * (this.canvas?.height || 600),
            size: Math.random() * 3 + 1,
            speedX: (Math.random() - 0.5) * 0.5,
            speedY: (Math.random() - 0.5) * 0.3 - 0.2,
            color: colors[Math.floor(Math.random() * colors.length)],
            opacity: Math.random() * 0.5 + 0.2,
            pulse: Math.random() * Math.PI * 2,
            pulseSpeed: Math.random() * 0.02 + 0.01
        };
    }

    animate() {
        if (!this.ctx || !this.canvas) return;

        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

        this.particles.forEach(p => {
            p.x += p.speedX;
            p.y += p.speedY;
            p.pulse += p.pulseSpeed;

            const currentOpacity = p.opacity * (0.5 + 0.5 * Math.sin(p.pulse));
            const currentSize = p.size * (0.8 + 0.2 * Math.sin(p.pulse));

            // Draw particle
            this.ctx.beginPath();
            this.ctx.arc(p.x, p.y, currentSize, 0, Math.PI * 2);
            this.ctx.fillStyle = p.color;
            this.ctx.globalAlpha = currentOpacity;
            this.ctx.fill();

            // Glow effect
            this.ctx.beginPath();
            this.ctx.arc(p.x, p.y, currentSize * 2, 0, Math.PI * 2);
            this.ctx.fillStyle = p.color;
            this.ctx.globalAlpha = currentOpacity * 0.2;
            this.ctx.fill();

            this.ctx.globalAlpha = 1;

            // Wrap around edges
            if (p.x < -10) p.x = this.canvas.width + 10;
            if (p.x > this.canvas.width + 10) p.x = -10;
            if (p.y < -10) p.y = this.canvas.height + 10;
            if (p.y > this.canvas.height + 10) p.y = -10;
        });

        this.animationId = requestAnimationFrame(() => this.animate());
    }

    destroy() {
        if (this.animationId) cancelAnimationFrame(this.animationId);
    }
}

// Auto-init if particle canvas exists
document.addEventListener('DOMContentLoaded', () => {
    const canvas = document.getElementById('particles-canvas');
    if (canvas) {
        new ParticleSystem('particles-canvas');
    }
});
