/**
 * GridBot Command Center - Main JavaScript
 * Advanced 3D Trading Platform with Interactive Visualizations
 * 
 * @package GridBot_Command_Center
 * @version 2.0.0
 */

// Global configuration
window.GridBotCC = window.GridBotCC || {};

// Initialize when DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    initializeGridBotCC();
});

/**
 * Main initialization function
 */
function initializeGridBotCC() {
    // Performance monitoring
    if (window.performance) {
        window.performance.mark('gridbot-init-start');
    }
    
    // Initialize core components
    initializeLoadingScreen();
    initializeNavigation();
    initializeScrollEffects();
    initializeAnimations();
    initializeInteractions();
    initializeCalculators();
    initializePerformanceMonitoring();
    
    // Initialize 3D components if enabled
    if (window.gridbotccConfig && window.gridbotccConfig.is3DEnabled) {
        initialize3DComponents();
    }
    
    // Mark initialization complete
    if (window.performance) {
        window.performance.mark('gridbot-init-end');
        window.performance.measure('gridbot-initialization', 'gridbot-init-start', 'gridbot-init-end');
    }
    
    console.log('GridBot Command Center initialized successfully');
}

/**
 * Loading Screen Management
 */
function initializeLoadingScreen() {
    const loadingScreen = document.getElementById('loading-screen');
    const progressBar = document.getElementById('loading-progress');
    
    if (!loadingScreen) return;
    
    // Simulate loading progress
    let progress = 0;
    const loadingInterval = setInterval(() => {
        progress += Math.random() * 15;
        if (progress > 100) progress = 100;
        
        if (progressBar) {
            progressBar.style.width = progress + '%';
        }
        
        if (progress >= 100) {
            clearInterval(loadingInterval);
            setTimeout(() => {
                hideLoadingScreen();
            }, 500);
        }
    }, 200);
    
    // Hide loading screen function
    function hideLoadingScreen() {
        if (loadingScreen) {
            anime({
                targets: loadingScreen,
                opacity: [1, 0],
                duration: 800,
                easing: 'easeOutQuad',
                complete: () => {
                    loadingScreen.style.display = 'none';
                    document.body.classList.add('loaded');
                    
                    // Trigger entrance animations
                    triggerEntranceAnimations();
                }
            });
        }
    }
}

/**
 * Navigation Management
 */
function initializeNavigation() {
    const mobileToggle = document.getElementById('mobile-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const nav = document.getElementById('main-navigation');
    
    // Mobile menu toggle
    if (mobileToggle && mobileMenu) {
        mobileToggle.addEventListener('click', function() {
            mobileMenu.classList.toggle('active');
            mobileToggle.classList.toggle('active');
        });
    }
    
    // Navigation background on scroll
    if (nav) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                nav.classList.add('scrolled');
            } else {
                nav.classList.remove('scrolled');
            }
        });
    }
    
    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                const offsetTop = target.offsetTop - 80; // Account for fixed nav
                window.scrollTo({
                    top: offsetTop,
                    behavior: 'smooth'
                });
            }
        });
    });
}

/**
 * Scroll Effects and Parallax
 */
function initializeScrollEffects() {
    // Parallax elements
    const parallaxElements = document.querySelectorAll('[data-parallax]');
    
    // Intersection Observer for reveal animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const element = entry.target;
                
                // Add revealed class
                element.classList.add('revealed');
                
                // Trigger specific animations
                if (element.classList.contains('card-3d')) {
                    animateCard3D(element);
                }
                
                if (element.classList.contains('timeline-item-3d')) {
                    animateTimelineItem(element);
                }
                
                if (element.classList.contains('feature-card-3d')) {
                    animateFeatureCard(element);
                }
                
                // Stop observing this element
                observer.unobserve(element);
            }
        });
    }, observerOptions);
    
    // Observe elements
    const animatedElements = document.querySelectorAll(
        '.card-3d, .feature-card-3d, .timeline-item-3d, .philosophy-card, .user-profile-card'
    );
    
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        observer.observe(el);
    });
    
    // Scroll parallax effect
    window.addEventListener('scroll', () => {
        const scrollY = window.scrollY;
        
        parallaxElements.forEach(element => {
            const speed = element.dataset.parallax || 0.5;
            const yPos = -(scrollY * speed);
            element.style.transform = `translateY(${yPos}px)`;
        });
    });
}

/**
 * Animation Functions
 */
function triggerEntranceAnimations() {
    // Hero title animation
    const heroTitle = document.querySelector('.hero-title');
    if (heroTitle) {
        anime({
            targets: heroTitle,
            translateY: [50, 0],
            opacity: [0, 1],
            duration: 1000,
            easing: 'easeOutExpo',
            delay: 200
        });
    }
    
    // Hero subtitle animation
    const heroSubtitle = document.querySelector('.hero-subtitle');
    if (heroSubtitle) {
        anime({
            targets: heroSubtitle,
            translateY: [30, 0],
            opacity: [0, 1],
            duration: 800,
            easing: 'easeOutExpo',
            delay: 400
        });
    }
    
    // Hero buttons animation
    const heroButtons = document.querySelectorAll('.hero-cta');
    if (heroButtons.length > 0) {
        anime({
            targets: heroButtons,
            translateY: [20, 0],
            opacity: [0, 1],
            duration: 600,
            easing: 'easeOutExpo',
            delay: anime.stagger(100, {start: 600})
        });
    }
}

function animateCard3D(card) {
    anime({
        targets: card,
        translateY: [30, 0],
        opacity: [0, 1],
        rotateX: [10, 0],
        duration: 800,
        easing: 'easeOutExpo',
        delay: Math.random() * 200
    });
}

function animateTimelineItem(item) {
    anime({
        targets: item,
        translateX: [-30, 0],
        opacity: [0, 1],
        duration: 600,
        easing: 'easeOutExpo'
    });
}

function animateFeatureCard(card) {
    anime({
        targets: card,
        translateY: [40, 0],
        opacity: [0, 1],
        rotateY: [5, 0],
        duration: 700,
        easing: 'easeOutExpo',
        delay: Math.random() * 300
    });
}

/**
 * Initialize Animations
 */
function initializeAnimations() {
    // Timeline auto-cycle
    const timelineItems = document.querySelectorAll('.timeline-item-3d');
    let currentTimelineStep = 0;
    
    setInterval(() => {
        if (timelineItems.length > 0) {
            timelineItems.forEach(item => item.classList.remove('active'));
            timelineItems[currentTimelineStep].classList.add('active');
            currentTimelineStep = (currentTimelineStep + 1) % timelineItems.length;
        }
    }, 4000);
    
    // Floating animations for icons
    const floatElements = document.querySelectorAll('.icon-3d');
    floatElements.forEach((element, index) => {
        anime({
            targets: element,
            translateY: [-5, 5],
            duration: 2000 + (index * 200),
            easing: 'easeInOutSine',
            direction: 'alternate',
            loop: true
        });
    });
}

/**
 * Interactive Elements
 */
function initializeInteractions() {
    // Button click handlers
    document.addEventListener('click', function(e) {
        if (e.target.matches('.btn') || e.target.closest('.btn')) {
            const button = e.target.matches('.btn') ? e.target : e.target.closest('.btn');
            const buttonText = button.textContent.trim();
            
            handleButtonClick(button, buttonText);
        }
        
        // Card interactions
        if (e.target.closest('.card-3d') || e.target.closest('.feature-card-3d')) {
            const card = e.target.closest('.card-3d') || e.target.closest('.feature-card-3d');
            handleCardClick(card);
        }
    });
    
    // 3D tilt: only the card under the pointer moves, and it resets when the pointer leaves it
    const TILT_SELECTOR = '.card-3d, .feature-card-3d';
    document.addEventListener('mousemove', function(e) {
        const card = e.target && e.target.closest ? e.target.closest(TILT_SELECTOR) : null;
        if (!card) return;
        const rect = card.getBoundingClientRect();
        if (!rect.width || !rect.height) return;
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;
        const rotateX = ((y - rect.height / 2) / (rect.height / 2)) * -6;
        const rotateY = ((x - rect.width / 2) / (rect.width / 2)) * 6;
        card.classList.add('is-tilting');
        card.style.transform = `perspective(1000px) rotateX(${rotateX.toFixed(2)}deg) rotateY(${rotateY.toFixed(2)}deg) translateY(-6px)`;
    });

    document.addEventListener('mouseout', function(e) {
        const card = e.target && e.target.closest ? e.target.closest(TILT_SELECTOR) : null;
        if (!card) return;
        if (e.relatedTarget && card.contains(e.relatedTarget)) return;
        card.classList.remove('is-tilting');
        card.style.transform = '';
    });

    // Forms: contact, newsletter and demo requests post to admin-ajax (see inc/forms.php)
    document.addEventListener('submit', function(e) {
        const form = e.target.closest ? e.target.closest('.contact-form, .newsletter-form, .demo-request-form') : null;
        if (!form) return;
        e.preventDefault();
        submitGridbotForm(form);
    });
}

function submitGridbotForm(form) {
    const config = window.gridbotccData || window.gridbotccConfig || {};
    const responseDiv = form.parentNode.querySelector('.form-response, .newsletter-response, .demo-response');
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn ? submitBtn.textContent : '';
    const formType = form.classList.contains('newsletter-form') ? 'newsletter'
        : form.classList.contains('demo-request-form') ? 'demo' : 'contact';

    const data = new FormData(form);
    data.append('action', 'gridbotcc_submit_form');
    data.append('form_type', formType);
    data.append('nonce', config.formNonce || '');

    if (submitBtn) { submitBtn.disabled = true; submitBtn.textContent = 'Sending...'; }

    const show = (message, ok) => {
        if (!responseDiv) return;
        responseDiv.innerHTML = '<p class="' + (ok ? 'success' : 'error') + '" style="margin-top:1rem;color:' + (ok ? '#059669' : '#dc2626') + ';">' + message + '</p>';
        responseDiv.style.display = 'block';
        if (typeof anime !== 'undefined') {
            anime({ targets: responseDiv, opacity: [0, 1], translateY: [10, 0], duration: 400, easing: 'easeOutQuad' });
        }
    };

    fetch(config.ajaxUrl || '/wp-admin/admin-ajax.php', { method: 'POST', body: data, credentials: 'same-origin' })
        .then(r => r.json())
        .then(json => {
            if (json && json.success) {
                show(json.data && json.data.message ? json.data.message : 'Thank you!', true);
                form.reset();
            } else {
                show(json && json.data && json.data.message ? json.data.message : 'Something went wrong. Please try again.', false);
            }
        })
        .catch(() => show('Could not reach the server. Please try again later.', false))
        .finally(() => { if (submitBtn) { submitBtn.disabled = false; submitBtn.textContent = originalText; } });
}

function handleButtonClick(button, text) {
    // Button press animation
    anime({
        targets: button,
        scale: [1, 0.95, 1],
        duration: 200,
        easing: 'easeOutQuad'
    });
    
    // Handle different button actions
    switch(text) {
        case 'Access Platform':
        case 'Access Platform':
            showNotification('Platform access will be available in Q1 2024', 'info');
            break;
        case 'View 3D Demo':
        case 'Start Demo':
            showNotification('3D Demo coming soon!', 'info');
            break;
        case 'View Demo':
            showNotification('Interactive demo will be available soon', 'info');
            break;
        case 'Learn More':
            showNotification('Educational resources coming soon', 'info');
            break;
        default:
            showNotification('Feature coming soon', 'info');
    }
}

function handleCardClick(card) {
    // Card selection animation
    anime({
        targets: card,
        scale: [1, 1.05, 1],
        duration: 300,
        easing: 'easeOutQuad'
    });
}

/**
 * Calculator Functions
 */
function initializeCalculators() {
    const calculator = document.querySelector('.gridbot-calculator');
    if (!calculator) return;
    
    const capitalInput = calculator.querySelector('#capital');
    const gridRangeInput = calculator.querySelector('#grid-range');
    const gridCountInput = calculator.querySelector('#grid-count');
    const calculateBtn = calculator.querySelector('#calculate-profit');
    const resultsDiv = calculator.querySelector('#calculator-results');
    
    if (calculateBtn) {
        calculateBtn.addEventListener('click', function() {
            calculateGridProfit();
        });
    }
    
    function calculateGridProfit() {
        const capital = parseFloat(capitalInput?.value) || 10000;
        const gridRange = parseFloat(gridRangeInput?.value) || 10;
        const gridCount = parseInt(gridCountInput?.value) || 10;
        
        // Simple calculation (for demo purposes)
        const gridSpacing = (capital * (gridRange / 100)) / gridCount;
        const estimatedProfitPerGrid = gridSpacing * 0.02; // 2% profit per grid
        const totalEstimatedProfit = estimatedProfitPerGrid * gridCount;
        
        // Create results HTML
        const resultsHTML = `
            <div class="calculator-results-content">
                <h4>Estimated Results</h4>
                <div class="results-grid">
                    <div class="result-item">
                        <span class="result-label">Capital Allocation:</span>
                        <span class="result-value">$${capital.toLocaleString()}</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Grid Range:</span>
                        <span class="result-value">${gridRange}%</span>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Number of Grids:</span>
                        <span class="result-value">${gridCount}</span>
                    </div>
                    <div class="result-item highlight">
                        <span class="result-label">Est. Profit per Grid:</span>
                        <span class="result-value">$${estimatedProfitPerGrid.toFixed(2)}</span>
                    </div>
                    <div class="result-item highlight total">
                        <span class="result-label">Total Est. Profit:</span>
                        <span class="result-value">$${totalEstimatedProfit.toFixed(2)}</span>
                    </div>
                </div>
                <p class="results-disclaimer">
                    * These are estimated results for demonstration purposes only. 
                    Actual results may vary significantly based on market conditions.
                </p>
            </div>
        `;
        
        if (resultsDiv) {
            resultsDiv.innerHTML = resultsHTML;
            
            // Animate results appearance
            anime({
                targets: resultsDiv,
                opacity: [0, 1],
                translateY: [20, 0],
                duration: 500,
                easing: 'easeOutQuad'
            });
        }
        
        // Create particle effect
        if (window.tradingParticles) {
            const rect = calculateBtn.getBoundingClientRect();
            window.tradingParticles.createProfitEffect(
                rect.left + rect.width / 2,
                rect.top + rect.height / 2,
                totalEstimatedProfit
            );
        }
    }
}

/**
 * Performance Monitoring
 */
function initializePerformanceMonitoring() {
    // FPS Counter
    let fps = 0;
    let lastTime = performance.now();
    let frames = 0;
    
    function updateFPS() {
        frames++;
        const currentTime = performance.now();
        
        if (currentTime >= lastTime + 1000) {
            fps = Math.round((frames * 1000) / (currentTime - lastTime));
            frames = 0;
            lastTime = currentTime;
            
            // Update FPS display
            const fpsElements = document.querySelectorAll('[id$="-fps"]');
            fpsElements.forEach(el => {
                el.textContent = `FPS: ${fps}`;
            });
        }
        
        requestAnimationFrame(updateFPS);
    }
    
    updateFPS();
    
    // Memory usage monitoring
    if (performance.memory) {
        setInterval(() => {
            const memory = performance.memory;
            const usedMB = Math.round(memory.usedJSHeapSize / 1048576);
            const totalMB = Math.round(memory.totalJSHeapSize / 1048576);
            
            // Update memory display if exists
            const memoryElements = document.querySelectorAll('.memory-usage');
            memoryElements.forEach(el => {
                el.textContent = `Memory: ${usedMB}MB / ${totalMB}MB`;
            });
        }, 5000);
    }
}

/**
 * 3D Components Initialization
 */
function initialize3DComponents() {
    // Wait for Three.js to load
    if (typeof THREE === 'undefined') {
        console.warn('Three.js not loaded, skipping 3D initialization');
        return;
    }
    
    // Initialize 3D scenes if the scripts are loaded
    if (typeof GridBot3D !== 'undefined') {
        console.log('3D Components initialized');
    }
}

/**
 * Utility Functions
 */
function showNotification(message, type = 'info', duration = 3000) {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div class="notification-content">
            <span class="notification-message">${message}</span>
            <button class="notification-close">&times;</button>
        </div>
    `;
    
    // Add styles
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        padding: 16px 20px;
        border-radius: 12px;
        color: white;
        font-weight: 500;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        max-width: 400px;
        transform: translateX(100%);
        transition: transform 0.3s ease;
    `;
    
    // Set background color based on type
    const colors = {
        info: 'linear-gradient(135deg, #2dd4bf, #0891b2)',
        success: 'linear-gradient(135deg, #059669, #047857)',
        warning: 'linear-gradient(135deg, #d97706, #b45309)',
        error: 'linear-gradient(135deg, #dc2626, #b91c1c)'
    };
    
    notification.style.background = colors[type] || colors.info;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
    }, 100);
    
    // Close functionality
    const closeBtn = notification.querySelector('.notification-close');
    closeBtn.addEventListener('click', () => {
        closeNotification(notification);
    });
    
    // Auto close
    setTimeout(() => {
        closeNotification(notification);
    }, duration);
    
    function closeNotification(el) {
        el.style.transform = 'translateX(100%)';
        setTimeout(() => {
            if (el.parentNode) {
                el.parentNode.removeChild(el);
            }
        }, 300);
    }
}

// Back-to-top button is handled in footer-animations.js

// Export for use in other scripts
window.GridBotCC = {
    showNotification,
    initializeGridBotCC
};