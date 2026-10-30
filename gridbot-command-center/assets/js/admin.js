/**
 * Admin JavaScript
 * 
 * @package GridBot_Command_Center
 */

jQuery(document).ready(function($) {
    'use strict';
    
    // Theme options page functionality
    if ($('.gridbotcc-meta-box').length > 0) {
        // Meta box enhancements
        $('.gridbotcc-meta-box input[type="number"]').on('input', function() {
            // Auto-calculate some values
            const $this = $(this);
            const id = $this.attr('id');
            
            if (id === 'initial_capital' || id === 'final_capital') {
                const initial = parseFloat($('#initial_capital').val()) || 0;
                const final = parseFloat($('#final_capital').val()) || 0;
                
                if (initial > 0) {
                    const totalReturn = ((final - initial) / initial * 100).toFixed(2);
                    $('#total_return').val(totalReturn);
                }
            }
        });
    }
    
    // 3D settings page
    const animationSpeedSlider = $('input[name="animation_speed"]');
    const animationSpeedValue = $('#animation-speed-value');
    
    if (animationSpeedSlider.length > 0 && animationSpeedValue.length > 0) {
        animationSpeedSlider.on('input', function() {
            animationSpeedValue.text($(this).val() + 'x');
        });
    }
    
    // Color scheme preview
    const colorSchemeSelect = $('select[name="color_scheme"]');
    if (colorSchemeSelect.length > 0) {
        const previewDiv = $('<div class="color-scheme-preview"></div>');
        colorSchemeSelect.after(previewDiv);
        
        colorSchemeSelect.on('change', function() {
            const scheme = $(this).val();
            updateColorSchemePreview(scheme, previewDiv);
        });
        
        // Initial preview
        updateColorSchemePreview(colorSchemeSelect.val(), previewDiv);
    }
    
    function updateColorSchemePreview(scheme, previewDiv) {
        const colors = {
            default: { primary: '#2dd4bf', secondary: '#f59e0b' },
            dark: { primary: '#1e40af', secondary: '#7c3aed' },
            neon: { primary: '#00ffff', secondary: '#ff00ff' }
        };
        
        const schemeColors = colors[scheme] || colors.default;
        
        previewDiv.html(`
            <div class="color-preview" style="background: ${schemeColors.primary};"></div>
            <div class="color-preview" style="background: ${schemeColors.secondary};"></div>
        `);
    }
    
    // Performance page
    if ($('.performance-stats').length > 0) {
        // Simulate real-time performance metrics
        setInterval(updatePerformanceMetrics, 5000);
    }
    
    function updatePerformanceMetrics() {
        const metrics = $('.performance-stats td');
        
        metrics.each(function() {
            const $this = $(this);
            if ($this.text().includes('~')) {
                // Update simulated metrics
                const baseValue = parseFloat($this.text().replace(/[^\d.]/g, ''));
                const variation = (Math.random() - 0.5) * 0.2; // ±10% variation
                const newValue = baseValue * (1 + variation);
                
                if ($this.text().includes('s')) {
                    $this.text(`~${newValue.toFixed(1)}s`);
                } else if ($this.text().includes('KB')) {
                    $this.text(`~${Math.round(newValue)}KB (gzipped)`);
                }
            }
        });
    }
    
    // Dashboard widget
    if ($('.gridbotcc-dashboard-widget').length > 0) {
        // Add quick actions
        $('.gridbotcc-dashboard-widget a').on('click', function(e) {
            // Add loading animation
            $(this).css('opacity', '0.6');
        });
    }
    
    // Admin notices dismissal
    $('.notice.is-dismissible').each(function() {
        const $notice = $(this);
        const $dismissBtn = $('<button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>');
        
        $notice.append($dismissBtn);
        
        $dismissBtn.on('click', function() {
            $notice.fadeTo(100, 0, function() {
                $notice.slideUp(100, function() {
                    $notice.remove();
                });
            });
        });
    });
    
    // Tooltips for admin interface
    $('.gridbotcc-help-tip').each(function() {
        const $tip = $(this);
        const text = $tip.data('tip');
        
        $tip.on('mouseenter', function() {
            const $tooltip = $('<div class="gridbotcc-tooltip">' + text + '</div>');
            $('body').append($tooltip);
            
            const offset = $tip.offset();
            $tooltip.css({
                top: offset.top - $tooltip.outerHeight() - 10,
                left: offset.left + ($tip.outerWidth() / 2) - ($tooltip.outerWidth() / 2)
            });
            
            $tooltip.fadeIn(200);
        }).on('mouseleave', function() {
            $('.gridbotcc-tooltip').fadeOut(200, function() {
                $(this).remove();
            });
        });
    });
});

// Utility functions for admin
window.GridBotCCAdmin = {
    showNotification: function(message, type = 'success') {
        const $notice = $('<div class="notice notice-' + type + ' is-dismissible"><p>' + message + '</p></div>');
        $('.wrap').first().before($notice);
        
        setTimeout(function() {
            $notice.fadeOut(function() {
                $(this).remove();
            });
        }, 5000);
    },
    
    confirmAction: function(message, callback) {
        if (confirm(message)) {
            callback();
        }
    }
};