<?php
/**
 * GridBot Command Center Theme Functions
 * 
 * @package GridBot_Command_Center
 * @version 2.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

// Theme Version
define('GRIDBOTCC_VERSION', '2.0.0');
define('GRIDBOTCC_DIR', get_template_directory());
define('GRIDBOTCC_URI', get_template_directory_uri());

/**
 * Theme Setup
 */
function gridbotcc_setup() {
    // Add theme support
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption'));
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('editor-styles');
    add_theme_support('responsive-embeds');
    
    // Register navigation menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'gridbot-command-center'),
        'footer' => __('Footer Menu', 'gridbot-command-center'),
    ));
    
    // Set content width
    if (!isset($content_width)) {
        $content_width = 1200;
    }
}
add_action('after_setup_theme', 'gridbotcc_setup');

/**
 * Enqueue Scripts and Styles
 */
function gridbotcc_scripts() {
    // Main stylesheet
    wp_enqueue_style('gridbotcc-style', get_stylesheet_uri(), array(), GRIDBOTCC_VERSION);
    
    // Custom CSS
    wp_enqueue_style('gridbotcc-custom', GRIDBOTCC_URI . '/assets/css/custom.css', array(), GRIDBOTCC_VERSION);
    
    // Google Fonts
    wp_enqueue_style('gridbotcc-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap', array(), null);
    
    // 3D Libraries
    wp_enqueue_script('three-js', 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js', array(), 'r128', true);
    wp_enqueue_script('anime-js', 'https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js', array(), '3.2.1', true);
    wp_enqueue_script('pixi-js', 'https://cdnjs.cloudflare.com/ajax/libs/pixi.js/7.2.4/pixi.min.js', array(), '7.2.4', true);
    wp_enqueue_script('matter-js', 'https://cdnjs.cloudflare.com/ajax/libs/matter-js/0.19.0/matter.min.js', array(), '0.19.0', true);
    wp_enqueue_script('echarts-js', 'https://cdn.jsdelivr.net/npm/echarts@5.4.3/dist/echarts.min.js', array(), '5.4.3', true);
    wp_enqueue_script('splide-js', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js', array(), '4.1.4', true);
    wp_enqueue_style('splide-css', 'https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css', array(), '4.1.4');
    
    // Main JavaScript
    wp_enqueue_script('gridbotcc-main', GRIDBOTCC_URI . '/assets/js/main.js', array('jquery', 'three-js', 'anime-js'), GRIDBOTCC_VERSION, true);
    
    // 3D Scene JavaScript
    wp_enqueue_script('gridbotcc-3d', GRIDBOTCC_URI . '/assets/js/3d-scenes.js', array('three-js', 'pixi-js', 'matter-js'), GRIDBOTCC_VERSION, true);
    
    // Particle System
    wp_enqueue_script('gridbotcc-particles', GRIDBOTCC_URI . '/assets/js/particles.js', array('pixi-js'), GRIDBOTCC_VERSION, true);
    
    // Footer animations (depends on particles.js for ParticleSystem)
    wp_enqueue_script('gridbotcc-footer', GRIDBOTCC_URI . '/assets/js/footer-animations.js', array('anime-js', 'gridbotcc-particles'), GRIDBOTCC_VERSION, true);
    
    // Localize script data
    wp_localize_script('gridbotcc-main', 'gridbotccData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'themeUri' => GRIDBOTCC_URI,
        'isHome' => is_front_page(),
        'isUserLoggedIn' => is_user_logged_in(),
        'formNonce' => wp_create_nonce('gridbotcc_form'),
    ));
    
    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'gridbotcc_scripts');

/**
 * Default navigation when no menu is assigned to a location
 */
function gridbotcc_default_menu($args = array()) {
    $items = array(
        '#features'     => __('Features', 'gridbot-command-center'),
        '#how-it-works' => __('How It Works', 'gridbot-command-center'),
        '#risk'         => __('Risk Disclosure', 'gridbot-command-center'),
        '#access'       => __('Access', 'gridbot-command-center'),
    );
    $menu_class = !empty($args['menu_class']) ? $args['menu_class'] : 'nav-list';
    $menu_id    = !empty($args['menu_id']) ? ' id="' . esc_attr($args['menu_id']) . '"' : '';
    $html = '<ul' . $menu_id . ' class="' . esc_attr($menu_class) . '">';
    foreach ($items as $anchor => $label) {
        $html .= '<li class="menu-item"><a href="' . esc_url(home_url('/') . $anchor) . '">' . esc_html($label) . '</a></li>';
    }
    $html .= '</ul>';
    if (!empty($args['echo']) || !isset($args['echo'])) {
        echo $html;
    }
    return $html;
}

/**
 * Register Sidebars/Widget Areas
 */
function gridbotcc_widgets_init() {
    register_sidebar(array(
        'name' => __('Primary Sidebar', 'gridbot-command-center'),
        'id' => 'primary-sidebar',
        'description' => __('Main sidebar area', 'gridbot-command-center'),
        'before_widget' => '<div id="%1$s" class="widget %2$s card-3d">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="widget-title">',
        'after_title' => '</h3>',
    ));
    
    register_sidebar(array(
        'name' => __('Footer Widgets', 'gridbot-command-center'),
        'id' => 'footer-widgets',
        'description' => __('Footer widget area', 'gridbot-command-center'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ));
}
add_action('widgets_init', 'gridbotcc_widgets_init');

/**
 * Custom Post Types
 */
function gridbotcc_register_post_types() {
    // Grid Strategies Post Type
    register_post_type('grid_strategies', array(
        'labels' => array(
            'name' => __('Grid Strategies', 'gridbot-command-center'),
            'singular_name' => __('Grid Strategy', 'gridbot-command-center'),
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'strategies'),
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt'),
        'menu_icon' => 'dashicons-chart-line',
    ));
    
    // Trading Pairs Post Type
    register_post_type('trading_pairs', array(
        'labels' => array(
            'name' => __('Trading Pairs', 'gridbot-command-center'),
            'singular_name' => __('Trading Pair', 'gridbot-command-center'),
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'pairs'),
        'supports' => array('title', 'editor', 'thumbnail'),
        'menu_icon' => 'dashicons-money-alt',
    ));
    
    // Performance Data Post Type
    register_post_type('performance_data', array(
        'labels' => array(
            'name' => __('Performance Data', 'gridbot-command-center'),
            'singular_name' => __('Performance Record', 'gridbot-command-center'),
        ),
        'public' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'performance'),
        'supports' => array('title', 'editor', 'custom-fields'),
        'menu_icon' => 'dashicons-performance',
    ));
}
add_action('init', 'gridbotcc_register_post_types');

/**
 * Custom Taxonomies
 */
function gridbotcc_register_taxonomies() {
    // Strategy Categories
    register_taxonomy('strategy_category', 'grid_strategies', array(
        'labels' => array(
            'name' => __('Strategy Categories', 'gridbot-command-center'),
            'singular_name' => __('Strategy Category', 'gridbot-command-center'),
        ),
        'hierarchical' => true,
        'public' => true,
        'rewrite' => array('slug' => 'strategy-category'),
    ));
    
    // Market Conditions
    register_taxonomy('market_condition', array('grid_strategies', 'performance_data'), array(
        'labels' => array(
            'name' => __('Market Conditions', 'gridbot-command-center'),
            'singular_name' => __('Market Condition', 'gridbot-command-center'),
        ),
        'hierarchical' => false,
        'public' => true,
    ));
}
add_action('init', 'gridbotcc_register_taxonomies');

/**
 * Customizer Settings
 */
function gridbotcc_customizer($wp_customize) {
    // 3D Settings Section
    $wp_customize->add_section('gridbotcc_3d_settings', array(
        'title' => __('3D Settings', 'gridbot-command-center'),
        'priority' => 30,
    ));
    
    // Enable 3D Effects
    $wp_customize->add_setting('gridbotcc_3d_enabled', array(
        'type' => 'option',
        'default' => '1',
        'sanitize_callback' => 'gridbotcc_sanitize_checkbox',
    ));
    
    $wp_customize->add_control('gridbotcc_3d_enabled', array(
        'label' => __('Enable 3D Effects', 'gridbot-command-center'),
        'section' => 'gridbotcc_3d_settings',
        'type' => 'checkbox',
    ));
    
    // Particle Count
    $wp_customize->add_setting('gridbotcc_particle_count', array(
        'type' => 'option',
        'default' => 1000,
        'sanitize_callback' => 'absint',
    ));
    
    $wp_customize->add_control('gridbotcc_particle_count', array(
        'label' => __('Particle Count', 'gridbot-command-center'),
        'section' => 'gridbotcc_3d_settings',
        'type' => 'number',
        'input_attrs' => array(
            'min' => 100,
            'max' => 5000,
            'step' => 100,
        ),
    ));
    
    // Animation Speed
    $wp_customize->add_setting('gridbotcc_animation_speed', array(
        'type' => 'option',
        'default' => 1.0,
        'sanitize_callback' => 'floatval',
    ));
    
    $wp_customize->add_control('gridbotcc_animation_speed', array(
        'label' => __('Animation Speed', 'gridbot-command-center'),
        'section' => 'gridbotcc_3d_settings',
        'type' => 'range',
        'input_attrs' => array(
            'min' => '0.1',
            'max' => '3.0',
            'step' => '0.1',
        ),
    ));
}
add_action('customize_register', 'gridbotcc_customizer');

function gridbotcc_sanitize_checkbox($value) {
    return ($value === true || $value === '1' || $value === 1 || $value === 'on') ? '1' : '0';
}

/**
 * Admin Notice for Theme Features
 */
function gridbotcc_admin_notice() {
    global $pagenow;
    if ($pagenow == 'themes.php') {
        ?>
        <div class="notice notice-success is-dismissible">
            <p><strong>GridBot Command Center Theme Activated!</strong></p>
            <p>Your 3D-enabled trading platform theme is now active. Use the shortcodes to add interactive elements:</p>
            <ul style="list-style: disc; margin-left: 20px;">
                <li><code>[gridbot_3d_visualizer]</code> - 3D Trading Visualizer</li>
                <li><code>[gridbot_particles]</code> - Particle System Background</li>
                <li><code>[gridbot_feature_cards]</code> - 3D Feature Cards</li>
                <li><code>[gridbot_calculator]</code> - Trading Calculator</li>
                <li><code>[gridbot_performance_chart]</code> - Performance Chart</li>
            </ul>
            <p>Customize 3D settings in Appearance > Customize > 3D Settings</p>
        </div>
        <?php
    }
}
add_action('admin_notices', 'gridbotcc_admin_notice');

/**
 * Performance Optimization
 */
function gridbotcc_optimize_performance() {
    // Remove WordPress emoji scripts
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    
    // Remove WordPress version number
    remove_action('wp_head', 'wp_generator');
    
    // Remove wlwmanifest link
    remove_action('wp_head', 'wlwmanifest_link');
    
    // Remove RSD link
    remove_action('wp_head', 'rsd_link');
    
    // Remove shortlink
    remove_action('wp_head', 'wp_shortlink_wp_head');
    
    // Remove feed links
    remove_action('wp_head', 'feed_links', 2);
    remove_action('wp_head', 'feed_links_extra', 3);
}
add_action('init', 'gridbotcc_optimize_performance');

/**
 * Security Headers
 */
function gridbotcc_security_headers() {
    if (!is_admin()) {
        header("X-Frame-Options: SAMEORIGIN");
        header("X-Content-Type-Options: nosniff");
        header("X-XSS-Protection: 1; mode=block");
        header("Referrer-Policy: strict-origin-when-cross-origin");
    }
}
add_action('send_headers', 'gridbotcc_security_headers');

/**
 * Theme Activation Hook
 */
function gridbotcc_theme_activation() {
    // Flush rewrite rules
    flush_rewrite_rules();
    
    // Set default options
    add_option('gridbotcc_3d_enabled', '1');
    add_option('gridbotcc_particle_count', 1000);
    add_option('gridbotcc_animation_speed', 1.0);
    add_option('gridbotcc_quality_level', 'medium');
    add_option('gridbotcc_color_scheme', 'default');
}
add_action('after_switch_theme', 'gridbotcc_theme_activation');

/**
 * Theme Deactivation Hook
 */
function gridbotcc_theme_deactivation() {
    flush_rewrite_rules();
}
add_action('switch_theme', 'gridbotcc_theme_deactivation');

// Include additional files
require_once GRIDBOTCC_DIR . '/inc/custom-post-types.php';
require_once GRIDBOTCC_DIR . '/inc/shortcodes.php';
require_once GRIDBOTCC_DIR . '/inc/shortcodes-extra.php';
require_once GRIDBOTCC_DIR . '/inc/forms.php';
require_once GRIDBOTCC_DIR . '/inc/3d-renderer.php';
require_once GRIDBOTCC_DIR . '/inc/admin-functions.php';

/**
 * Helper Functions
 */
function gridbotcc_get_theme_option($key, $default = '') {
    return get_option($key, $default);
}

function gridbotcc_is_3d_enabled() {
    return (string) get_option('gridbotcc_3d_enabled', '1') === '1';
}

function gridbotcc_get_particle_count() {
    $count = absint(get_option('gridbotcc_particle_count', 1000));
    return $count > 0 ? min($count, 5000) : 1000;
}

function gridbotcc_get_animation_speed() {
    $speed = (float) get_option('gridbotcc_animation_speed', 1.0);
    return ($speed >= 0.1 && $speed <= 3.0) ? $speed : 1.0;
}

function gridbotcc_get_quality_level() {
    $level = get_option('gridbotcc_quality_level', 'medium');
    return in_array($level, array('low', 'medium', 'high'), true) ? $level : 'medium';
}
