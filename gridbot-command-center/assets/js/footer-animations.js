/**
 * Footer Animations and Interactions
 * 
 * @package GridBot_Command_Center
 */


/**
 * Footer Animations
 */
function initializeFooterAnimations() {
    const footer = document.getElementById('main-footer');
    if (!footer) return;
    
    // Animate footer sections on scroll
    const footerSections = footer.querySelectorAll('.footer-section');
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                anime({
                    targets: entry.target,
                    translateY: [50, 0],
                    opacity: [0, 1],
                    duration: 800,
                    easing: 'easeOutQuad',
                    delay: Math.random() * 300
                });
                
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    
    footerSections.forEach(section => {
        section.style.opacity = '0';
        section.style.transform = 'translateY(50px)';
        observer.observe(section);
    });
    
    // Social link hover effects
    const socialLinks = footer.querySelectorAll('.social-link');
    socialLinks.forEach(link => {
        link.addEventListener('mouseenter', function() {
            anime({
                targets: this,
                scale: [1, 1.1],
                rotateY: [0, 180],
                duration: 300,
                easing: 'easeOutQuad'
            });
        });
        
        link.addEventListener('mouseleave', function() {
            anime({
                targets: this,
                scale: [1.1, 1],
                rotateY: [180, 0],
                duration: 300,
                easing: 'easeOutQuad'
            });
        });
    });
}

/**
 * Back to Top Button
 */
function initializeBackToTop() {
    const backToTop = document.getElementById('back-to-top');
    if (!backToTop) return;
    
    // Show/hide based on scroll position
    window.addEventListener('scroll', function() {
        if (window.scrollY > 500) {
            if (!backToTop.classList.contains('visible')) {
                backToTop.classList.add('visible');
                anime({
                    targets: backToTop,
                    scale: [0, 1],
                    rotate: [180, 0],
                    duration: 300,
                    easing: 'easeOutBack'
                });
            }
        } else {
            if (backToTop.classList.contains('visible')) {
                anime({
                    targets: backToTop,
                    scale: [1, 0],
                    rotate: [0, 180],
                    duration: 300,
                    easing: 'easeInBack',
                    complete: () => {
                        backToTop.classList.remove('visible');
                    }
                });
            }
        }
    });
    
    // Click animation
    backToTop.addEventListener('click', function(e) {
        e.preventDefault();
        
        // Button press animation
        anime({
            targets: this,
            scale: [1, 0.8, 1],
            duration: 200,
            easing: 'easeOutQuad'
        });
        
        // Scroll to top
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
}

/**
 * Performance Overlay
 */
function initializePerformanceOverlay() {
    const overlay = document.getElementById('performance-overlay');
    const closeBtn = document.getElementById('performance-close');
    
    if (!overlay) return;
    
    // Close button functionality
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            closePerformanceOverlay();
        });
    }
    
    // Close on overlay click
    overlay.addEventListener('click', function(e) {
        if (e.target === overlay) {
            closePerformanceOverlay();
        }
    });
    
    // ESC key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && overlay.classList.contains('active')) {
            closePerformanceOverlay();
        }
    });
    
    function closePerformanceOverlay() {
        anime({
            targets: overlay,
            opacity: [1, 0],
            duration: 300,
            easing: 'easeOutQuad',
            complete: () => {
                overlay.classList.remove('active');
            }
        });
    }
    
    // Add performance toggle functionality
    const performanceToggle = document.getElementById('performance-toggle');
    if (performanceToggle) {
        performanceToggle.addEventListener('click', function() {
            showPerformanceOverlay();
        });
    }
    
    function showPerformanceOverlay() {
        updatePerformanceStats();
        overlay.classList.add('active');
        
        anime({
            targets: overlay,
            opacity: [0, 1],
            duration: 300,
            easing: 'easeOutQuad'
        });
        
        anime({
            targets: overlay.querySelector('.performance-content'),
            scale: [0.8, 1],
            opacity: [0, 1],
            duration: 400,
            delay: 100,
            easing: 'easeOutBack'
        });
    }
    
    function updatePerformanceStats() {
        const statsContainer = document.getElementById('performance-stats');
        if (!statsContainer) return;
        
        // Simulate performance metrics
        const metrics = [
            { name: 'FPS', value: Math.floor(Math.random() * 20) + 50, unit: '' },
            { name: 'Memory Usage', value: Math.floor(Math.random() * 100) + 200, unit: 'MB' },
            { name: 'Load Time', value: (Math.random() * 2 + 1).toFixed(2), unit: 's' },
            { name: 'CPU Usage', value: Math.floor(Math.random() * 40) + 10, unit: '%' }
        ];
        
        const statsHTML = metrics.map(metric => `
            <div class="performance-stat">
                <span class="stat-name">${metric.name}</span>
                <span class="stat-value">${metric.value}${metric.unit}</span>
            </div>
        `).join('');
        
        statsContainer.innerHTML = statsHTML;
    }
}

/**
 * Footer Particles
 */
function initializeFooterParticles() {
    const footerCanvas = document.getElementById('footer-particles');
    if (!footerCanvas || typeof ParticleSystem === 'undefined') return;
    
    // Initialize subtle particle system for footer
    const footerParticles = new ParticleSystem(footerCanvas, {
        count: 300,
        connectionDistance: 60,
        mouseInteraction: false,
        colors: ['#374151', '#4b5563', '#6b7280']
    });
    
    // Make particles more subtle
    footerParticles.particles.forEach(particle => {
        particle.alpha = particle.alpha * 0.5;
        particle.size = particle.size * 0.7;
    });
}

/**
 * Footer Performance Metrics
 */
function initializeFooterPerformanceMetrics() {
    const metricsContainer = document.getElementById('footer-performance-metrics');
    if (!metricsContainer) return;
    
    // Simulate real-time metrics
    setInterval(() => {
        const metrics = [
            { name: 'Active Bots', value: Math.floor(Math.random() * 50) + 200 },
            { name: '24h Volume', value: '$' + (Math.random() * 1000000 + 500000).toLocaleString() },
            { name: 'Success Rate', value: (Math.random() * 5 + 92).toFixed(1) + '%' }
        ];
        
        const metricsHTML = metrics.map(metric => `
            <div class="metric">
                <span class="metric-name">${metric.name}</span>
                <span class="metric-value">${metric.value}</span>
            </div>
        `).join('');
        
        metricsContainer.innerHTML = metricsHTML;
    }, 5000);
}

/**
 * Newsletter Signup in Footer
 */
function initializeFooterNewsletter() {
    const newsletterForm = document.querySelector('.footer-newsletter-form');
    if (!newsletterForm) return;
    
    newsletterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const email = this.querySelector('input[type="email"]').value;
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.textContent;
        
        // Loading state
        submitBtn.textContent = 'Subscribing...';
        submitBtn.disabled = true;
        
        // Simulate API call
        setTimeout(() => {
            // Success state
            submitBtn.textContent = 'Subscribed!';
            submitBtn.style.background = '#059669';
            
            // Reset form
            this.reset();
            
            // Reset button after delay
            setTimeout(() => {
                submitBtn.textContent = originalText;
                submitBtn.disabled = false;
                submitBtn.style.background = '';
            }, 3000);
        }, 1500);
    });
}

/**
 * Social Media Feed Integration
 */
function initializeSocialFeed() {
    const socialFeed = document.querySelector('.social-feed');
    if (!socialFeed) return;
    
    // Simulate social media posts
    const posts = [
        {
            platform: 'twitter',
            content: 'Just launched our new 3D trading visualization! Check it out.',
            time: '2 hours ago',
            likes: 42
        },
        {
            platform: 'discord',
            content: 'Community discussion about grid trading strategies.',
            time: '5 hours ago',
            likes: 18
        },
        {
            platform: 'telegram',
            content: 'New tutorial video available for beginners.',
            time: '1 day ago',
            likes: 67
        }
    ];
    
    const feedHTML = posts.map(post => `
        <div class="social-post">
            <div class="post-header">
                <span class="platform">${post.platform}</span>
                <span class="time">${post.time}</span>
            </div>
            <div class="post-content">${post.content}</div>
            <div class="post-footer">
                <span class="likes">${post.likes} likes</span>
            </div>
        </div>
    `).join('');
    
    socialFeed.innerHTML = feedHTML;
}

/**
 * Theme Credits
 */
function initializeThemeCredits() {
    const credits = document.querySelector('.theme-credits');
    if (!credits) return;
    
    // Animate credits on scroll
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                anime({
                    targets: entry.target,
                    opacity: [0, 1],
                    translateY: [20, 0],
                    duration: 600,
                    easing: 'easeOutQuad'
                });
                
                observer.unobserve(entry.target);
            }
        });
    });
    
    observer.observe(credits);
}

// Initialize all footer functionality (single entry point)
document.addEventListener('DOMContentLoaded', function() {
    initializeFooterAnimations();
    initializeBackToTop();
    initializePerformanceOverlay();
    initializeFooterParticles();
    initializeFooterPerformanceMetrics();
    initializeFooterNewsletter();
    initializeSocialFeed();
    initializeThemeCredits();
});