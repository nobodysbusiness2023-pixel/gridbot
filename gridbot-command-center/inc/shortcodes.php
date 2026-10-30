<?php
/**
 * Custom Shortcodes
 * 
 * @package GridBot_Command_Center
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register all custom shortcodes
 */
function gridbotcc_register_shortcodes() {
    // 3D Components
    add_shortcode('gridbot_3d_visualizer', 'gridbotcc_3d_visualizer_shortcode');
    add_shortcode('gridbot_particles', 'gridbotcc_particles_shortcode');
    add_shortcode('gridbot_3d_card', 'gridbotcc_3d_card_shortcode');
    add_shortcode('gridbot_3d_timeline', 'gridbotcc_3d_timeline_shortcode');
    
    // Trading Components
    add_shortcode('gridbot_calculator', 'gridbotcc_calculator_shortcode');
    add_shortcode('gridbot_performance_chart', 'gridbotcc_performance_chart_shortcode');
    add_shortcode('gridbot_trading_pairs', 'gridbotcc_trading_pairs_shortcode');
    add_shortcode('gridbot_strategy_comparison', 'gridbotcc_strategy_comparison_shortcode');
    
    // Content Components
    add_shortcode('gridbot_feature_cards', 'gridbotcc_feature_cards_shortcode');
    add_shortcode('gridbot_pricing_table', 'gridbotcc_pricing_table_shortcode');
    add_shortcode('gridbot_testimonials', 'gridbotcc_testimonials_shortcode');
    add_shortcode('gridbot_statistics', 'gridbotcc_statistics_shortcode');
    
    // Interactive Components
    add_shortcode('gridbot_contact_form', 'gridbotcc_contact_form_shortcode');
    add_shortcode('gridbot_newsletter_signup', 'gridbotcc_newsletter_signup_shortcode');
    add_shortcode('gridbot_demo_request', 'gridbotcc_demo_request_shortcode');
}
add_action('init', 'gridbotcc_register_shortcodes');

/**
 * 3D Visualizer Shortcode
 * 
 * Creates an interactive 3D trading visualization
 */
function gridbotcc_3d_visualizer_shortcode($atts) {
    $atts = shortcode_atts(array(
        'width' => '100%',
        'height' => '400px',
        'theme' => 'dark',
        'auto_play' => 'true',
        'show_controls' => 'true'
    ), $atts);
    
    ob_start();
    ?>
    <div class="gridbot-3d-visualizer" 
         data-width="<?php echo esc_attr($atts['width']); ?>" 
         data-height="<?php echo esc_attr($atts['height']); ?>" 
         data-theme="<?php echo esc_attr($atts['theme']); ?>"
         data-auto-play="<?php echo esc_attr($atts['auto_play']); ?>"
         data-show-controls="<?php echo esc_attr($atts['show_controls']); ?>">
        
        <div class="visualizer-container">
            <div id="three-canvas-<?php echo uniqid(); ?>" class="three-canvas-container" style="height: <?php echo esc_attr($atts['height']); ?>;"></div>
            
            <?php if ($atts['show_controls'] === 'true'): ?>
            <div class="visualizer-controls">
                <button class="btn btn-primary visualizer-play">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                    Play
                </button>
                <button class="btn btn-secondary visualizer-pause">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                    </svg>
                    Pause
                </button>
                <button class="btn btn-secondary visualizer-reset">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 5V1L7 6l5 5V7c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6H4c0 4.42 3.58 8 8 8s8-3.58 8-8-3.58-8-8-8z"/>
                    </svg>
                    Reset
                </button>
            </div>
            <?php endif; ?>
            
            <div class="visualizer-stats">
                <div class="stat">
                    <span class="stat-label">Active Grids:</span>
                    <span class="stat-value stat-active-grids">0</span>
                </div>
                <div class="stat">
                    <span class="stat-label">Total Profit:</span>
                    <span class="stat-value stat-total-profit">$0.00</span>
                </div>
                <div class="stat">
                    <span class="stat-label">Success Rate:</span>
                    <span class="stat-value stat-success-rate">0%</span>
                </div>
            </div>
        </div>
        
        <div class="visualizer-loading">
            <div class="loading-spinner"></div>
            <p>Initializing 3D Trading Environment...</p>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof GridBot3D === 'undefined' || !(window.gridbotccConfig && window.gridbotccConfig.is3DEnabled)) {
            document.querySelectorAll('.gridbot-3d-visualizer .visualizer-loading').forEach(function(el) {
                el.innerHTML = '<p>3D effects are disabled or unavailable in this browser.</p>';
            });
            return;
        }
        document.querySelectorAll('.gridbot-3d-visualizer').forEach(function(visualizer) {
            const canvasHost = visualizer.querySelector('.three-canvas-container');
            if (!canvasHost || canvasHost.dataset.initialized === 'true') return;
            new GridBot3D(canvasHost.id, {
                camera: { position: [0, 0, 30], fov: 60 },
                grid: { size: 10, spacing: 1, levels: 8 },
                particles: { count: 500 }
            });
        });
    });
    </script>
    <?php
    return ob_get_clean();
}

/**
 * Particle System Shortcode
 */
function gridbotcc_particles_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count' => '1000',
        'speed' => '1',
        'color' => '#2dd4bf',
        'interaction' => 'true',
        'connections' => 'true'
    ), $atts);
    
    $unique_id = uniqid('particles-');
    
    ob_start();
    ?>
    <div class="gridbot-particles" 
         id="<?php echo $unique_id; ?>"
         data-count="<?php echo esc_attr($atts['count']); ?>" 
         data-speed="<?php echo esc_attr($atts['speed']); ?>" 
         data-color="<?php echo esc_attr($atts['color']); ?>"
         data-interaction="<?php echo esc_attr($atts['interaction']); ?>"
         data-connections="<?php echo esc_attr($atts['connections']); ?>">
        <canvas id="<?php echo $unique_id; ?>-canvas" class="particle-canvas"></canvas>
        
        <div class="particle-controls">
            <button class="btn btn-secondary particle-play">Play</button>
            <button class="btn btn-secondary particle-pause">Pause</button>
            <button class="btn btn-secondary particle-reset">Reset</button>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof ParticleSystem !== 'undefined') {
            const container = document.getElementById('<?php echo $unique_id; ?>');
            const canvas = document.getElementById('<?php echo $unique_id; ?>-canvas');
            
            if (canvas && container) {
                const particleSystem = new ParticleSystem(canvas, {
                    count: parseInt('<?php echo $atts['count']; ?>'),
                    connectionDistance: 100,
                    mouseInteraction: '<?php echo $atts['interaction']; ?>' === 'true',
                    colors: ['<?php echo $atts['color']; ?>', '#f59e0b', '#059669']
                });
                
                // Control buttons
                const playBtn = container.querySelector('.particle-play');
                const pauseBtn = container.querySelector('.particle-pause');
                const resetBtn = container.querySelector('.particle-reset');
                
                if (playBtn) playBtn.addEventListener('click', () => particleSystem.play());
                if (pauseBtn) pauseBtn.addEventListener('click', () => particleSystem.pause());
                if (resetBtn) resetBtn.addEventListener('click', () => particleSystem.reset());
            }
        }
    });
    </script>
    <?php
    return ob_get_clean();
}

/**
 * 3D Card Shortcode
 */
function gridbotcc_3d_card_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'title' => '',
        'icon' => '',
        'color' => '#2dd4bf',
        'hover_effect' => 'true',
        'class' => ''
    ), $atts);
    
    $unique_id = uniqid('card-');
    
    ob_start();
    ?>
    <div class="gridbot-3d-card <?php echo esc_attr($atts['class']); ?>" id="<?php echo $unique_id; ?>">
        <div class="card-inner">
            <?php if ($atts['icon']): ?>
            <div class="card-icon" style="background: <?php echo esc_attr($atts['color']); ?>">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="currentColor">
                    <path d="<?php echo esc_attr($atts['icon']); ?>"/>
                </svg>
            </div>
            <?php endif; ?>
            
            <?php if ($atts['title']): ?>
            <h3 class="card-title"><?php echo esc_html($atts['title']); ?></h3>
            <?php endif; ?>
            
            <div class="card-content">
                <?php echo do_shortcode($content); ?>
            </div>
        </div>
        
        <?php if ($atts['hover_effect'] === 'true'): ?>
        <div class="card-glow"></div>
        <?php endif; ?>
    </div>
    
    <style>
    #<?php echo $unique_id; ?> {
        background: rgba(26, 26, 26, 0.8);
        border: 1px solid rgba(45, 212, 191, 0.2);
        border-radius: 20px;
        padding: 2rem;
        backdrop-filter: blur(20px);
        transition: all 0.3s ease;
        transform-style: preserve-3d;
        position: relative;
        overflow: hidden;
    }
    
    #<?php echo $unique_id; ?>:hover {
        transform: translateY(-10px) rotateX(5deg) rotateY(5deg);
        border-color: <?php echo esc_attr($atts['color']); ?>;
        box-shadow: 
            0 20px 40px rgba(0, 0, 0, 0.3),
            0 0 30px <?php echo esc_attr($atts['color']); ?>;
    }
    
    #<?php echo $unique_id; ?> .card-icon {
        width: 64px;
        height: 64px;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    #<?php echo $unique_id; ?>:hover .card-icon {
        transform: rotateY(180deg) scale(1.1);
    }
    
    #<?php echo $unique_id; ?> .card-title {
        color: white;
        margin-bottom: 1rem;
        font-size: 1.3rem;
        font-weight: 700;
    }
    
    #<?php echo $unique_id; ?> .card-content {
        color: #a3a3a3;
        line-height: 1.6;
    }
    </style>
    <?php
    return ob_get_clean();
}

/**
 * 3D Timeline Shortcode
 */
function gridbotcc_3d_timeline_shortcode($atts, $content = null) {
    $atts = shortcode_atts(array(
        'items' => '',
        'auto_play' => 'true',
        'color' => '#2dd4bf'
    ), $atts);
    
    // Parse timeline items
    $items = array();
    if (!empty($atts['items'])) {
        $item_data = explode('|', $atts['items']);
        foreach ($item_data as $item) {
            $parts = explode(':', $item);
            if (count($parts) >= 2) {
                $items[] = array(
                    'title' => trim($parts[0]),
                    'description' => trim($parts[1])
                );
            }
        }
    }
    
    $unique_id = uniqid('timeline-');
    
    ob_start();
    ?>
    <div class="gridbot-3d-timeline" id="<?php echo $unique_id; ?>">
        <div class="timeline-container">
            <?php foreach ($items as $index => $item): ?>
            <div class="timeline-item <?php echo $index === 0 ? 'active' : ''; ?>" data-step="<?php echo $index + 1; ?>">
                <div class="timeline-content">
                    <h3><?php echo esc_html($item['title']); ?></h3>
                    <p><?php echo esc_html($item['description']); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        
        <?php if ($atts['auto_play'] === 'true'): ?>
        <div class="timeline-controls">
            <button class="btn btn-secondary timeline-prev">Previous</button>
            <button class="btn btn-secondary timeline-next">Next</button>
        </div>
        <?php endif; ?>
    </div>
    
    <style>
    #<?php echo $unique_id; ?> .timeline-container {
        position: relative;
        padding-left: 4rem;
    }
    
    #<?php echo $unique_id; ?> .timeline-container::before {
        content: '';
        position: absolute;
        left: 20px;
        top: 0;
        bottom: 0;
        width: 2px;
        background: linear-gradient(to bottom, <?php echo esc_attr($atts['color']); ?>, #f59e0b);
        box-shadow: 0 0 10px <?php echo esc_attr($atts['color']); ?>;
    }
    
    #<?php echo $unique_id; ?> .timeline-item {
        position: relative;
        margin-bottom: 2rem;
        padding: 2rem;
        background: rgba(26, 26, 26, 0.8);
        border-radius: 16px;
        border-left: 4px solid <?php echo esc_attr($atts['color']); ?>;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        opacity: 0.5;
    }
    
    #<?php echo $unique_id; ?> .timeline-item.active {
        opacity: 1;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
    }
    
    #<?php echo $unique_id; ?> .timeline-item::before {
        content: '';
        position: absolute;
        left: -32px;
        top: 24px;
        width: 16px;
        height: 16px;
        background: <?php echo esc_attr($atts['color']); ?>;
        border-radius: 50%;
        box-shadow: 0 0 20px <?php echo esc_attr($atts['color']); ?>;
        transform: translateZ(10px);
    }
    
    #<?php echo $unique_id; ?> .timeline-item:hover {
        transform: translateX(10px) rotateX(5deg);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
    }
    
    #<?php echo $unique_id; ?> .timeline-content h3 {
        color: white;
        margin-bottom: 1rem;
        font-size: 1.3rem;
    }
    
    #<?php echo $unique_id; ?> .timeline-content p {
        color: #a3a3a3;
        line-height: 1.6;
    }
    
    #<?php echo $unique_id; ?> .timeline-controls {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 2rem;
    }
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const timeline = document.getElementById('<?php echo $unique_id; ?>');
        const items = timeline.querySelectorAll('.timeline-item');
        const prevBtn = timeline.querySelector('.timeline-prev');
        const nextBtn = timeline.querySelector('.timeline-next');
        let currentStep = 0;
        
        function updateTimeline() {
            items.forEach((item, index) => {
                item.classList.toggle('active', index === currentStep);
            });
        }
        
        if (prevBtn) {
            prevBtn.addEventListener('click', () => {
                currentStep = Math.max(0, currentStep - 1);
                updateTimeline();
            });
        }
        
        if (nextBtn) {
            nextBtn.addEventListener('click', () => {
                currentStep = Math.min(items.length - 1, currentStep + 1);
                updateTimeline();
            });
        }
        
        // Auto-play
        if ('<?php echo $atts['auto_play']; ?>' === 'true') {
            setInterval(() => {
                currentStep = (currentStep + 1) % items.length;
                updateTimeline();
            }, 4000);
        }
    });
    </script>
    <?php
    return ob_get_clean();
}

/**
 * Trading Calculator Shortcode
 */
function gridbotcc_calculator_shortcode($atts) {
    $atts = shortcode_atts(array(
        'title' => 'Grid Trading Calculator',
        'show_advanced' => 'false'
    ), $atts);
    
    ob_start();
    ?>
    <div class="gridbot-calculator card-3d">
        <h3><?php echo esc_html($atts['title']); ?></h3>
        
        <div class="calculator-form">
            <div class="form-group">
                <label for="calculator-capital">Capital Amount ($)</label>
                <input type="number" id="calculator-capital" placeholder="10000" class="form-control" value="10000">
            </div>
            
            <div class="form-group">
                <label for="calculator-grid-range">Grid Range (%)</label>
                <input type="number" id="calculator-grid-range" placeholder="10" class="form-control" value="10" step="0.1">
            </div>
            
            <div class="form-group">
                <label for="calculator-grid-count">Number of Grids</label>
                <input type="number" id="calculator-grid-count" placeholder="10" class="form-control" value="10">
            </div>
            
            <?php if ($atts['show_advanced'] === 'true'): ?>
            <div class="form-group">
                <label for="calculator-volatility">Market Volatility (%)</label>
                <input type="number" id="calculator-volatility" placeholder="5" class="form-control" value="5" step="0.1">
            </div>
            
            <div class="form-group">
                <label for="calculator-timeframe">Time Frame (days)</label>
                <input type="number" id="calculator-timeframe" placeholder="30" class="form-control" value="30">
            </div>
            <?php endif; ?>
            
            <button type="button" class="btn btn-primary calculator-calculate">
                Calculate Potential Profit
            </button>
        </div>
        
        <div class="calculator-results" id="calculator-results" style="display: none;">
            <!-- Results will be populated by JavaScript -->
        </div>
        
        <div class="calculator-chart" id="calculator-chart">
            <!-- Chart will be rendered here -->
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const calculator = document.querySelector('.gridbot-calculator');
        if (!calculator) return;
        
        const calculateBtn = calculator.querySelector('.calculator-calculate');
        const resultsDiv = calculator.querySelector('#calculator-results');
        const chartDiv = calculator.querySelector('#calculator-chart');
        
        calculateBtn.addEventListener('click', function() {
            const capital = parseFloat(calculator.querySelector('#calculator-capital').value) || 10000;
            const gridRange = parseFloat(calculator.querySelector('#calculator-grid-range').value) || 10;
            const gridCount = parseInt(calculator.querySelector('#calculator-grid-count').value) || 10;
            const volatility = parseFloat(calculator.querySelector('#calculator-volatility')?.value) || 5;
            const timeframe = parseInt(calculator.querySelector('#calculator-timeframe')?.value) || 30;
            
            // Calculate metrics
            const gridSpacing = (capital * (gridRange / 100)) / gridCount;
            const baseProfitPerGrid = gridSpacing * 0.02; // 2% base profit
            const volatilityMultiplier = 1 + (volatility / 100);
            const estimatedProfitPerGrid = baseProfitPerGrid * volatilityMultiplier;
            const totalEstimatedProfit = estimatedProfitPerGrid * gridCount;
            const monthlyReturn = (totalEstimatedProfit / capital) * 100;
            const annualReturn = monthlyReturn * 12;
            
            // Risk metrics
            const maxDrawdown = Math.min(gridRange * 0.3, 15); // Max 30% of grid range or 15%
            const riskRewardRatio = totalEstimatedProfit / (capital * (maxDrawdown / 100));
            
            // Generate results HTML
            const resultsHTML = `
                <div class="calculator-results-content">
                    <h4>Calculation Results</h4>
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
                        <div class="result-item">
                            <span class="result-label">Grid Spacing:</span>
                            <span class="result-value">$${gridSpacing.toFixed(2)}</span>
                        </div>
                        <div class="result-item highlight">
                            <span class="result-label">Est. Profit per Grid:</span>
                            <span class="result-value">$${estimatedProfitPerGrid.toFixed(2)}</span>
                        </div>
                        <div class="result-item highlight total">
                            <span class="result-label">Total Est. Profit (${timeframe}d):</span>
                            <span class="result-value">$${totalEstimatedProfit.toFixed(2)}</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Monthly Return:</span>
                            <span class="result-value">${monthlyReturn.toFixed(2)}%</span>
                        </div>
                        <div class="result-item">
                            <span class="result-label">Annual Return:</span>
                            <span class="result-value">${annualReturn.toFixed(2)}%</span>
                        </div>
                        <div class="result-item risk">
                            <span class="result-label">Max Drawdown:</span>
                            <span class="result-value">${maxDrawdown.toFixed(1)}%</span>
                        </div>
                        <div class="result-item risk">
                            <span class="result-label">Risk/Reward Ratio:</span>
                            <span class="result-value">${riskRewardRatio.toFixed(2)}</span>
                        </div>
                    </div>
                    <p class="results-disclaimer">
                        * These are estimated results for demonstration purposes only. 
                        Actual results may vary significantly based on market conditions, execution quality, and other factors.
                    </p>
                </div>
            `;
            
            resultsDiv.innerHTML = resultsHTML;
            resultsDiv.style.display = 'block';
            
            // Animate results
            if (typeof anime !== 'undefined') {
                anime({
                    targets: resultsDiv,
                    opacity: [0, 1],
                    translateY: [20, 0],
                    duration: 500,
                    easing: 'easeOutQuad'
                });
            }
            
            // Create chart if ECharts is available
            if (typeof echarts !== 'undefined' && chartDiv) {
                createProfitChart(chartDiv, {
                    capital,
                    totalProfit: totalEstimatedProfit,
                    monthlyReturn,
                    annualReturn,
                    maxDrawdown
                });
            }
        });
        
        function createProfitChart(container, data) {
            const chart = echarts.init(container);
            
            const option = {
                backgroundColor: 'transparent',
                title: {
                    text: 'Profit Projection',
                    textStyle: { color: '#2dd4bf' }
                },
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: 'rgba(26, 26, 26, 0.9)',
                    borderColor: '#2dd4bf',
                    textStyle: { color: 'white' }
                },
                legend: {
                    data: ['Capital Growth', 'Drawdown'],
                    textStyle: { color: '#a3a3a3' }
                },
                xAxis: {
                    type: 'category',
                    data: ['Initial', 'Month 1', 'Month 3', 'Month 6', 'Month 12'],
                    axisLine: { lineStyle: { color: '#374151' } },
                    axisLabel: { color: '#a3a3a3' }
                },
                yAxis: {
                    type: 'value',
                    axisLine: { lineStyle: { color: '#374151' } },
                    axisLabel: { color: '#a3a3a3' },
                    splitLine: { lineStyle: { color: '#374151' } }
                },
                series: [
                    {
                        name: 'Capital Growth',
                        type: 'line',
                        data: [
                            data.capital,
                            data.capital * (1 + data.monthlyReturn / 100),
                            data.capital * Math.pow(1 + data.monthlyReturn / 100, 3),
                            data.capital * Math.pow(1 + data.monthlyReturn / 100, 6),
                            data.capital * Math.pow(1 + data.monthlyReturn / 100, 12)
                        ],
                        itemStyle: { color: '#2dd4bf' },
                        areaStyle: { color: 'rgba(45, 212, 191, 0.1)' }
                    },
                    {
                        name: 'Drawdown',
                        type: 'line',
                        data: [
                            0,
                            0,
                            -data.capital * (data.maxDrawdown / 100),
                            -data.capital * (data.maxDrawdown / 100) * 0.8,
                            -data.capital * (data.maxDrawdown / 100) * 0.6
                        ],
                        itemStyle: { color: '#dc2626' },
                        areaStyle: { color: 'rgba(220, 38, 38, 0.1)' }
                    }
                ]
            };
            
            chart.setOption(option);
            
            // Resize chart on window resize
            window.addEventListener('resize', () => chart.resize());
        }
    });
    </script>
    <?php
    return ob_get_clean();
}

/**
 * Performance Chart Shortcode
 */
function gridbotcc_performance_chart_shortcode($atts) {
    $atts = shortcode_atts(array(
        'width' => '100%',
        'height' => '300px',
        'period' => '30d',
        'type' => 'line'
    ), $atts);
    
    $unique_id = uniqid('chart-');
    
    ob_start();
    ?>
    <div class="gridbot-performance-chart" 
         id="<?php echo $unique_id; ?>"
         data-width="<?php echo esc_attr($atts['width']); ?>" 
         data-height="<?php echo esc_attr($atts['height']); ?>" 
         data-period="<?php echo esc_attr($atts['period']); ?>"
         data-type="<?php echo esc_attr($atts['type']); ?>">
        
        <div class="chart-container">
            <div id="<?php echo $unique_id; ?>-echarts" style="width: <?php echo esc_attr($atts['width']); ?>; height: <?php echo esc_attr($atts['height']); ?>;"></div>
        </div>
        
        <div class="chart-controls">
            <button class="btn btn-secondary chart-period" data-period="7d">7 Days</button>
            <button class="btn btn-secondary chart-period active" data-period="30d">30 Days</button>
            <button class="btn btn-secondary chart-period" data-period="90d">90 Days</button>
            <button class="btn btn-secondary chart-period" data-period="1y">1 Year</button>
        </div>
    </div>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof echarts === 'undefined') return;
        
        const chartContainer = document.getElementById('<?php echo $unique_id; ?>-echarts');
        const chart = echarts.init(chartContainer);
        
        // Generate sample data based on period
        function generateData(period) {
            const now = new Date();
            const data = [];
            let days = 30;
            
            switch(period) {
                case '7d': days = 7; break;
                case '30d': days = 30; break;
                case '90d': days = 90; break;
                case '1y': days = 365; break;
            }
            
            let baseValue = 10000;
            for (let i = 0; i < days; i++) {
                const date = new Date(now.getTime() - (days - i) * 24 * 60 * 60 * 1000);
                const variation = (Math.random() - 0.5) * 200;
                baseValue += variation;
                baseValue = Math.max(9000, Math.min(12000, baseValue));
                
                data.push([
                    date.toISOString().split('T')[0],
                    Math.round(baseValue)
                ]);
            }
            
            return data;
        }
        
        function updateChart(period) {
            const data = generateData(period);
            
            const option = {
                backgroundColor: 'transparent',
                title: {
                    text: 'Performance Chart - ' + period.toUpperCase(),
                    textStyle: { color: '#2dd4bf', fontSize: 16 }
                },
                tooltip: {
                    trigger: 'axis',
                    backgroundColor: 'rgba(26, 26, 26, 0.9)',
                    borderColor: '#2dd4bf',
                    textStyle: { color: 'white' },
                    formatter: function(params) {
                        const data = params[0];
                        return data.name + '<br/>Portfolio Value: $' + data.value[1].toLocaleString();
                    }
                },
                legend: {
                    data: ['Portfolio Value'],
                    textStyle: { color: '#a3a3a3' }
                },
                grid: {
                    left: '3%',
                    right: '4%',
                    bottom: '3%',
                    containLabel: true
                },
                xAxis: {
                    type: 'time',
                    boundaryGap: false,
                    axisLine: { lineStyle: { color: '#374151' } },
                    axisLabel: { color: '#a3a3a3' },
                    splitLine: { show: false }
                },
                yAxis: {
                    type: 'value',
                    axisLine: { lineStyle: { color: '#374151' } },
                    axisLabel: { 
                        color: '#a3a3a3',
                        formatter: '${value}'
                    },
                    splitLine: { lineStyle: { color: '#374151' } }
                },
                series: [
                    {
                        name: 'Portfolio Value',
                        type: 'line',
                        data: data,
                        smooth: true,
                        symbol: 'none',
                        lineStyle: {
                            color: '#2dd4bf',
                            width: 3
                        },
                        areaStyle: {
                            color: {
                                type: 'linear',
                                x: 0, y: 0, x2: 0, y2: 1,
                                colorStops: [
                                    { offset: 0, color: 'rgba(45, 212, 191, 0.3)' },
                                    { offset: 1, color: 'rgba(45, 212, 191, 0.05)' }
                                ]
                            }
                        },
                        emphasis: {
                            focus: 'series'
                        }
                    }
                ]
            };
            
            chart.setOption(option);
        }
        
        // Initial chart
        updateChart('<?php echo $atts['period']; ?>');
        
        // Period controls
        const periodButtons = document.querySelectorAll('#<?php echo $unique_id; ?> .chart-period');
        periodButtons.forEach(button => {
            button.addEventListener('click', function() {
                periodButtons.forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
                updateChart(this.dataset.period);
            });
        });
        
        // Resize chart on window resize
        window.addEventListener('resize', () => chart.resize());
    });
    </script>
    <?php
    return ob_get_clean();
}

/**
 * Feature Cards Shortcode
 */
function gridbotcc_feature_cards_shortcode($atts) {
    $atts = shortcode_atts(array(
        'category' => '',
        'count' => '6',
        'columns' => '3'
    ), $atts);
    
    // Query features (in a real implementation, this would query actual posts)
    $features = array(
        array(
            'title' => 'Centralized Dashboard',
            'description' => 'Manage multiple grid bots from a single interface with real-time monitoring.',
            'icon' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'
        ),
        array(
            'title' => 'Real-Time Visualization',
            'description' => 'Visual representation of active orders across price levels.',
            'icon' => 'M15 12a3 3 0 11-6 0 3 3 0 016 0zM2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z'
        ),
        array(
            'title' => 'Automated Execution',
            'description' => 'Systematic buy-low/sell-high ladder execution.',
            'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'
        ),
        array(
            'title' => 'Grid-Level Profits',
            'description' => 'Each buy-sell pair represents an individual profit opportunity.',
            'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1'
        ),
        array(
            'title' => 'Full-Cycle Exit',
            'description' => 'Define profit thresholds that trigger complete position liquidation.',
            'icon' => 'M13 17h8m0 0V9m0 8l-8-8-4 4-6-6'
        ),
        array(
            'title' => 'Risk Control',
            'description' => 'Risk management through position sizing and capital allocation.',
            'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'
        )
    );
    
    // Limit features based on count
    $features = array_slice($features, 0, intval($atts['count']));
    
    $unique_id = uniqid('features-');
    
    ob_start();
    ?>
    <div class="gridbot-feature-cards" id="<?php echo $unique_id; ?>">
        <div class="features-grid">
            <?php foreach ($features as $index => $feature): ?>
            <div class="feature-card-3d" data-aos="fade-up" data-aos-delay="<?php echo $index * 200; ?>">
                <div class="feature-icon icon-3d">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="<?php echo esc_attr($feature['icon']); ?>"/>
                    </svg>
                </div>
                <h3><?php echo esc_html($feature['title']); ?></h3>
                <p><?php echo esc_html($feature['description']); ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <style>
    #<?php echo $unique_id; ?> .features-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
        gap: 2rem;
    }
    
    @media (max-width: 768px) {
        #<?php echo $unique_id; ?> .features-grid {
            grid-template-columns: 1fr;
        }
    }
    </style>
    <?php
    return ob_get_clean();
}

/**
 * Trading Pairs Shortcode
 */
function gridbotcc_trading_pairs_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count' => '8',
        'show_volume' => 'true'
    ), $atts);
    
    // Sample trading pairs (in real implementation, this would query actual posts)
    $pairs = array(
        array('symbol' => 'BTC/USDT', 'price' => '43250.00', 'change' => '+2.5%', 'volume' => '1.2B'),
        array('symbol' => 'ETH/USDT', 'price' => '2650.00', 'change' => '+1.8%', 'volume' => '890M'),
        array('symbol' => 'ADA/USDT', 'price' => '0.4850', 'change' => '-0.5%', 'volume' => '245M'),
        array('symbol' => 'SOL/USDT', 'price' => '98.50', 'change' => '+4.2%', 'volume' => '567M'),
        array('symbol' => 'DOT/USDT', 'price' => '7.25', 'change' => '+0.9%', 'volume' => '123M'),
        array('symbol' => 'LINK/USDT', 'price' => '14.80', 'change' => '+1.2%', 'volume' => '234M'),
        array('symbol' => 'MATIC/USDT', 'price' => '0.9250', 'change' => '-1.1%', 'volume' => '345M'),
        array('symbol' => 'AVAX/USDT', 'price' => '36.20', 'change' => '+2.8%', 'volume' => '456M')
    );
    
    // Limit pairs based on count
    $pairs = array_slice($pairs, 0, intval($atts['count']));
    
    ob_start();
    ?>
    <div class="gridbot-trading-pairs">
        <div class="pairs-grid">
            <?php foreach ($pairs as $pair): ?>
            <div class="pair-card card-3d">
                <div class="pair-header">
                    <h4 class="pair-symbol"><?php echo esc_html($pair['symbol']); ?></h4>
                    <span class="pair-change <?php echo strpos($pair['change'], '+') !== false ? 'positive' : 'negative'; ?>">
                        <?php echo esc_html($pair['change']); ?>
                    </span>
                </div>
                <div class="pair-price">$<?php echo esc_html($pair['price']); ?></div>
                <?php if ($atts['show_volume'] === 'true'): ?>
                <div class="pair-volume">Vol: <?php echo esc_html($pair['volume']); ?></div>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <style>
    .gridbot-trading-pairs .pairs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }
    
    .pair-card {
        padding: 1.5rem;
        text-align: center;
    }
    
    .pair-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .pair-symbol {
        color: #2dd4bf;
        font-weight: 700;
        font-size: 1.1rem;
    }
    
    .pair-change {
        font-weight: 600;
        font-size: 0.9rem;
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
    }
    
    .pair-change.positive {
        color: #059669;
        background: rgba(5, 150, 105, 0.1);
    }
    
    .pair-change.negative {
        color: #dc2626;
        background: rgba(220, 38, 38, 0.1);
    }
    
    .pair-price {
        font-size: 1.5rem;
        font-weight: 700;
        color: white;
        margin-bottom: 0.5rem;
    }
    
    .pair-volume {
        color: #6b7280;
        font-size: 0.9rem;
    }
    </style>
    <?php
    return ob_get_clean();
}

/**
 * Statistics Shortcode
 */
function gridbotcc_statistics_shortcode($atts) {
    $atts = shortcode_atts(array(
        'show_counters' => 'true',
        'animation_duration' => '2000'
    ), $atts);
    
    $stats = array(
        array('number' => '10000', 'label' => 'Active Users', 'suffix' => '+'),
        array('number' => '500000', 'label' => 'Total Volume', 'prefix' => '$'),
        array('number' => '99.9', 'label' => 'Uptime', 'suffix' => '%'),
        array('number' => '24', 'label' => 'Support', 'suffix' => '/7')
    );
    
    ob_start();
    ?>
    <div class="gridbot-statistics">
        <div class="stats-grid">
            <?php foreach ($stats as $index => $stat): ?>
            <div class="stat-card card-3d" data-aos="fade-up" data-aos-delay="<?php echo $index * 200; ?>">
                <div class="stat-number">
                    <?php if (!empty($stat['prefix'])): ?>
                    <span class="stat-prefix"><?php echo esc_html($stat['prefix']); ?></span>
                    <?php endif; ?>
                    <span class="stat-counter" data-target="<?php echo esc_attr($stat['number']); ?>" data-duration="<?php echo esc_attr($atts['animation_duration']); ?>">0</span>
                    <?php if (!empty($stat['suffix'])): ?>
                    <span class="stat-suffix"><?php echo esc_html($stat['suffix']); ?></span>
                    <?php endif; ?>
                </div>
                <div class="stat-label"><?php echo esc_html($stat['label']); ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <style>
    .gridbot-statistics .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 2rem;
    }
    
    .stat-card {
        text-align: center;
        padding: 2rem;
    }
    
    .stat-number {
        font-size: 3rem;
        font-weight: 800;
        color: #2dd4bf;
        margin-bottom: 0.5rem;
        font-family: 'JetBrains Mono', monospace;
    }
    
    .stat-prefix,
    .stat-suffix {
        font-size: 2rem;
        color: #f59e0b;
    }
    
    .stat-label {
        color: #a3a3a3;
        font-size: 1.1rem;
        font-weight: 500;
    }
    </style>
    
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Counter animation
        const counters = document.querySelectorAll('.stat-counter');
        
        const animateCounter = (counter) => {
            const target = parseInt(counter.dataset.target);
            const duration = parseInt(counter.dataset.duration);
            const increment = target / (duration / 16);
            let current = 0;
            
            const timer = setInterval(() => {
                current += increment;
                if (current >= target) {
                    counter.textContent = target.toLocaleString();
                    clearInterval(timer);
                } else {
                    counter.textContent = Math.floor(current).toLocaleString();
                }
            }, 16);
        };
        
        // Intersection Observer for counters
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target.querySelector('.stat-counter');
                    if (counter && !counter.classList.contains('animated')) {
                        counter.classList.add('animated');
                        animateCounter(counter);
                    }
                    observer.unobserve(entry.target);
                }
            });
        });
        
        counters.forEach(counter => {
            observer.observe(counter.closest('.stat-card'));
        });
    });
    </script>
    <?php
    return ob_get_clean();
}

/**
 * Contact Form Shortcode
 */
function gridbotcc_contact_form_shortcode($atts) {
    $atts = shortcode_atts(array(
        'title' => 'Get in Touch',
        'show_phone' => 'true',
        'show_company' => 'false'
    ), $atts);
    
    ob_start();
    ?>
    <div class="gridbot-contact-form card-3d">
        <h3><?php echo esc_html($atts['title']); ?></h3>
        
        <?php $fid = uniqid('cf-'); ?>
        <form class="contact-form" id="contact-form-<?php echo $fid; ?>">
            <div class="form-row">
                <div class="form-group">
                    <label for="contact-name-<?php echo $fid; ?>">Name *</label>
                    <input type="text" id="contact-name-<?php echo $fid; ?>" name="name" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="contact-email-<?php echo $fid; ?>">Email *</label>
                    <input type="email" id="contact-email-<?php echo $fid; ?>" name="email" required class="form-control">
                </div>
            </div>
            
            <?php if ($atts['show_phone'] === 'true'): ?>
            <div class="form-group">
                <label for="contact-phone-<?php echo $fid; ?>">Phone</label>
                <input type="tel" id="contact-phone-<?php echo $fid; ?>" name="phone" class="form-control">
            </div>
            <?php endif; ?>
            
            <?php if ($atts['show_company'] === 'true'): ?>
            <div class="form-group">
                <label for="contact-company-<?php echo $fid; ?>">Company</label>
                <input type="text" id="contact-company-<?php echo $fid; ?>" name="company" class="form-control">
            </div>
            <?php endif; ?>
            
            <div class="form-group">
                <label for="contact-subject-<?php echo $fid; ?>">Subject *</label>
                <input type="text" id="contact-subject-<?php echo $fid; ?>" name="subject" required class="form-control">
            </div>
            
            <div class="form-group">
                <label for="contact-message-<?php echo $fid; ?>">Message *</label>
                <textarea id="contact-message-<?php echo $fid; ?>" name="message" rows="5" required class="form-control"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Send Message</button>
        </form>
        
        <div class="form-response" style="display: none;"></div>
    </div>
    
    <?php
    return ob_get_clean();
}

/**
 * Newsletter Signup Shortcode
 */
function gridbotcc_newsletter_signup_shortcode($atts) {
    $atts = shortcode_atts(array(
        'title' => 'Stay Updated',
        'description' => 'Get the latest news and updates about GridBot Command Center.'
    ), $atts);
    
    ob_start();
    ?>
    <div class="gridbot-newsletter card-3d">
        <h3><?php echo esc_html($atts['title']); ?></h3>
        <p><?php echo esc_html($atts['description']); ?></p>
        
        <form class="newsletter-form" id="newsletter-form-<?php echo uniqid(); ?>">
            <div class="form-group">
                <input type="email" name="email" placeholder="Enter your email address" required class="form-control">
            </div>
            <button type="submit" class="btn btn-primary">Subscribe</button>
        </form>
        
        <div class="newsletter-response" style="display: none;"></div>
        
        <div class="newsletter-disclaimer">
            <small>We respect your privacy. Unsubscribe at any time.</small>
        </div>
    </div>
    
    <?php
    return ob_get_clean();
}