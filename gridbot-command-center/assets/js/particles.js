/**
 * GridBot Command Center - Particle System
 * Advanced particle effects for immersive trading visualizations
 * 
 * @package GridBot_Command_Center
 */

class ParticleSystem {
    constructor(canvas, config = {}) {
        this.canvas = canvas;
        this.ctx = canvas.getContext('2d');
        this.config = {
            count: 1000,
            connectionDistance: 100,
            mouseInteraction: true,
            colors: ['#2dd4bf', '#f59e0b', '#059669'],
            ...config
        };
        
        this.particles = [];
        this.mouse = { x: 0, y: 0 };
        this.animationId = null;
        this.isPlaying = true;
        
        this.init();
    }
    
    init() {
        this.resize();
        this.createParticles();
        this.setupEventListeners();
        this.animate();
    }
    
    resize() {
        const parent = this.canvas.parentElement;
        this.canvas.width = (parent && parent.clientWidth) ? parent.clientWidth : window.innerWidth;
        this.canvas.height = (parent && parent.clientHeight) ? parent.clientHeight : window.innerHeight;
    }
    
    createParticles() {
        this.particles = [];
        
        for (let i = 0; i < this.config.count; i++) {
            const particle = {
                x: Math.random() * this.canvas.width,
                y: Math.random() * this.canvas.height,
                vx: (Math.random() - 0.5) * 0.5,
                vy: (Math.random() - 0.5) * 0.5,
                size: Math.random() * 2 + 1,
                color: this.config.colors[Math.floor(Math.random() * this.config.colors.length)],
                alpha: Math.random() * 0.5 + 0.2,
                life: 1.0,
                decay: Math.random() * 0.01 + 0.001,
                originalSize: 0,
                pulse: Math.random() * Math.PI * 2
            };
            
            particle.originalSize = particle.size;
            this.particles.push(particle);
        }
    }
    
    setupEventListeners() {
        window.addEventListener('resize', () => this.resize());
        
        if (this.config.mouseInteraction) {
            this.canvas.addEventListener('mousemove', (e) => this.onMouseMove(e));
            this.canvas.addEventListener('touchmove', (e) => this.onTouchMove(e));
        }
    }
    
    onMouseMove(event) {
        const rect = this.canvas.getBoundingClientRect();
        this.mouse.x = event.clientX - rect.left;
        this.mouse.y = event.clientY - rect.top;
    }
    
    onTouchMove(event) {
        event.preventDefault();
        if (event.touches.length > 0) {
            const touch = event.touches[0];
            const rect = this.canvas.getBoundingClientRect();
            this.mouse.x = touch.clientX - rect.left;
            this.mouse.y = touch.clientY - rect.top;
        }
    }
    
    updateParticles() {
        this.particles.forEach((particle, index) => {
            // Update position
            particle.x += particle.vx;
            particle.y += particle.vy;
            
            // Boundary check with wrap-around
            if (particle.x < 0) particle.x = this.canvas.width;
            if (particle.x > this.canvas.width) particle.x = 0;
            if (particle.y < 0) particle.y = this.canvas.height;
            if (particle.y > this.canvas.height) particle.y = 0;
            
            // Mouse interaction
            if (this.config.mouseInteraction) {
                const dx = this.mouse.x - particle.x;
                const dy = this.mouse.y - particle.y;
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance < 100) {
                    const force = (100 - distance) / 100;
                    particle.vx += dx * force * 0.001;
                    particle.vy += dy * force * 0.001;
                }
            }
            
            // Apply friction
            particle.vx *= 0.99;
            particle.vy *= 0.99;
            
            // Pulsing effect
            particle.pulse += 0.02;
            particle.size = particle.originalSize * (1 + Math.sin(particle.pulse) * 0.2);
            
            // Life and decay
            particle.life -= particle.decay;
            if (particle.life <= 0) {
                particle.life = 1.0;
                particle.x = Math.random() * this.canvas.width;
                particle.y = Math.random() * this.canvas.height;
            }
            
            // Update alpha based on life
            particle.alpha = particle.life * 0.5 + 0.2;
        });
    }
    
    drawParticles() {
        this.particles.forEach(particle => {
            this.ctx.save();
            this.ctx.globalAlpha = particle.alpha;
            this.ctx.fillStyle = particle.color;
            
            // Create glow effect
            const gradient = this.ctx.createRadialGradient(
                particle.x, particle.y, 0,
                particle.x, particle.y, particle.size * 2
            );
            gradient.addColorStop(0, particle.color);
            gradient.addColorStop(1, 'transparent');
            
            this.ctx.fillStyle = gradient;
            this.ctx.beginPath();
            this.ctx.arc(particle.x, particle.y, particle.size * 2, 0, Math.PI * 2);
            this.ctx.fill();
            
            // Draw core
            this.ctx.fillStyle = particle.color;
            this.ctx.beginPath();
            this.ctx.arc(particle.x, particle.y, particle.size, 0, Math.PI * 2);
            this.ctx.fill();
            
            this.ctx.restore();
        });
    }
    
    drawConnections() {
        const maxDistance = this.config.connectionDistance;
        // Connections are O(n^2); only the first few hundred particles take part.
        const limit = Math.min(this.particles.length, this.config.connectionLimit || 300);
        
        for (let i = 0; i < limit; i++) {
            for (let j = i + 1; j < limit; j++) {
                const particle1 = this.particles[i];
                const particle2 = this.particles[j];
                
                const dx = particle1.x - particle2.x;
                const dy = particle1.y - particle2.y;
                const distance = Math.sqrt(dx * dx + dy * dy);
                
                if (distance < maxDistance) {
                    const opacity = (1 - distance / maxDistance) * 0.3;
                    
                    this.ctx.save();
                    this.ctx.globalAlpha = opacity;
                    this.ctx.strokeStyle = particle1.color;
                    this.ctx.lineWidth = 0.5;
                    
                    this.ctx.beginPath();
                    this.ctx.moveTo(particle1.x, particle1.y);
                    this.ctx.lineTo(particle2.x, particle2.y);
                    this.ctx.stroke();
                    
                    this.ctx.restore();
                }
            }
        }
    }
    
    animate() {
        if (!this.isPlaying) return;
        
        this.animationId = requestAnimationFrame(() => this.animate());
        
        // Clear canvas
        this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
        
        // Update and draw
        this.updateParticles();
        this.drawConnections();
        this.drawParticles();
    }
    
    play() {
        this.isPlaying = true;
        this.animate();
    }
    
    pause() {
        this.isPlaying = false;
        if (this.animationId) {
            cancelAnimationFrame(this.animationId);
            this.animationId = null;
        }
    }
    
    reset() {
        this.createParticles();
    }
    
    destroy() {
        this.pause();
        this.particles = [];
    }
}

// Advanced Particle Effects
class AdvancedParticleEffects {
    static createExplosion(x, y, config = {}) {
        const particles = [];
        const count = config.count || 50;
        const colors = config.colors || ['#2dd4bf', '#f59e0b', '#dc2626'];
        
        for (let i = 0; i < count; i++) {
            const angle = (Math.PI * 2 * i) / count;
            const speed = Math.random() * 10 + 5;
            
            particles.push({
                x: x,
                y: y,
                vx: Math.cos(angle) * speed,
                vy: Math.sin(angle) * speed,
                size: Math.random() * 3 + 1,
                color: colors[Math.floor(Math.random() * colors.length)],
                life: 1.0,
                decay: Math.random() * 0.02 + 0.01
            });
        }
        
        return particles;
    }
    
    static createTrail(x, y, vx, vy, config = {}) {
        const particles = [];
        const length = config.length || 20;
        
        for (let i = 0; i < length; i++) {
            const t = i / length;
            particles.push({
                x: x - vx * t * 5,
                y: y - vy * t * 5,
                vx: 0,
                vy: 0,
                size: (1 - t) * 3,
                color: config.color || '#2dd4bf',
                life: 1 - t,
                decay: 0.05
            });
        }
        
        return particles;
    }
    
    static createVortex(x, y, config = {}) {
        const particles = [];
        const count = config.count || 100;
        const radius = config.radius || 50;
        
        for (let i = 0; i < count; i++) {
            const angle = (Math.PI * 2 * i) / count;
            const r = (i / count) * radius;
            
            particles.push({
                x: x + Math.cos(angle) * r,
                y: y + Math.sin(angle) * r,
                vx: Math.sin(angle) * 2,
                vy: -Math.cos(angle) * 2,
                size: Math.random() * 2 + 1,
                color: config.color || '#2dd4bf',
                life: 1.0,
                decay: 0.01
            });
        }
        
        return particles;
    }
}

// Trading-specific Particle Effects
class TradingParticleEffects {
    constructor(canvas) {
        this.canvas = canvas;
        this.ctx = canvas.getContext('2d');
        this.effects = [];
    }
    
    createBuyOrderEffect(x, y) {
        const particles = AdvancedParticleEffects.createExplosion(x, y, {
            count: 20,
            colors: ['#059669', '#10b981']
        });
        
        this.effects.push({
            type: 'buy_order',
            particles: particles,
            duration: 1000
        });
    }
    
    createSellOrderEffect(x, y) {
        const particles = AdvancedParticleEffects.createExplosion(x, y, {
            count: 20,
            colors: ['#dc2626', '#ef4444']
        });
        
        this.effects.push({
            type: 'sell_order',
            particles: particles,
            duration: 1000
        });
    }
    
    createProfitEffect(x, y, amount) {
        const intensity = Math.min(amount / 1000, 1);
        const particles = AdvancedParticleEffects.createExplosion(x, y, {
            count: Math.floor(30 * intensity),
            colors: ['#f59e0b', '#fbbf24', '#fde047']
        });
        
        this.effects.push({
            type: 'profit',
            particles: particles,
            duration: 2000,
            amount: amount
        });
    }
    
    update() {
        this.effects.forEach((effect, effectIndex) => {
            effect.particles.forEach((particle, particleIndex) => {
                particle.x += particle.vx;
                particle.y += particle.vy;
                particle.vx *= 0.98;
                particle.vy *= 0.98;
                particle.vy += 0.1; // gravity
                particle.life -= particle.decay;
                
                if (particle.life <= 0) {
                    effect.particles.splice(particleIndex, 1);
                }
            });
            
            if (effect.particles.length === 0) {
                this.effects.splice(effectIndex, 1);
            }
        });
    }
    
    render() {
        this.effects.forEach(effect => {
            effect.particles.forEach(particle => {
                this.ctx.save();
                this.ctx.globalAlpha = particle.life;
                this.ctx.fillStyle = particle.color;
                
                const gradient = this.ctx.createRadialGradient(
                    particle.x, particle.y, 0,
                    particle.x, particle.y, particle.size * 2
                );
                gradient.addColorStop(0, particle.color);
                gradient.addColorStop(1, 'transparent');
                
                this.ctx.fillStyle = gradient;
                this.ctx.beginPath();
                this.ctx.arc(particle.x, particle.y, particle.size * 2, 0, Math.PI * 2);
                this.ctx.fill();
                
                this.ctx.restore();
            });
        });
    }
}

// Initialize particle systems
document.addEventListener('DOMContentLoaded', function() {
    // Initialize hero particles
    const heroCanvas = document.getElementById('hero-particles');
    if (heroCanvas) {
        window.heroParticles = new ParticleSystem(heroCanvas, {
            count: 1500,
            connectionDistance: 120,
            mouseInteraction: true,
            colors: ['#2dd4bf', '#f59e0b', '#059669']
        });
    }
    
    // Footer particles are initialized by footer-animations.js

    // Initialize trading particle effects for calculator
    const calculatorCanvas = document.getElementById('calculator-particles');
    if (calculatorCanvas) {
        window.tradingParticles = new TradingParticleEffects(calculatorCanvas);
    }
});

// Export for use in other scripts
window.ParticleSystem = ParticleSystem;
window.AdvancedParticleEffects = AdvancedParticleEffects;
window.TradingParticleEffects = TradingParticleEffects;