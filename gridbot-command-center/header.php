<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <!-- WordPress Head -->
    <?php wp_head(); ?>
    
    <!-- Preconnect to external domains for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com">
    <link rel="preconnect" href="https://cdn.jsdelivr.net">
    
    <!-- Favicon and Theme Color -->
    <link rel="icon" type="image/x-icon" href="<?php echo get_template_directory_uri(); ?>/assets/images/favicon.ico">
    <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_template_directory_uri(); ?>/assets/images/apple-touch-icon.png">
    <meta name="theme-color" content="#2dd4bf">
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
    <meta name="keywords" content="grid trading, crypto trading, automated trading, gridbot, trading platform, 3D visualization">
    <meta name="author" content="GridBot Command Center">
    
    <!-- Open Graph Meta Tags -->
    <meta property="og:title" content="<?php wp_title('|', true, 'right'); ?>">
    <meta property="og:description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo home_url(); ?>">
    <meta property="og:image" content="<?php echo get_template_directory_uri(); ?>/assets/images/og-image.png">
    
    <!-- Twitter Card Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?php wp_title('|', true, 'right'); ?>">
    <meta name="twitter:description" content="<?php echo esc_attr(get_bloginfo('description')); ?>">
    <meta name="twitter:image" content="<?php echo get_template_directory_uri(); ?>/assets/images/twitter-card.png">
    
    <!-- Additional Meta Tags -->
    <meta name="robots" content="index, follow, max-image-preview:large, max-snippet:-1, max-video-preview:-1">
    <meta name="googlebot" content="index, follow">
    <meta name="bingbot" content="index, follow">
    
    <!-- Canonical URL -->
    <link rel="canonical" href="<?php echo home_url(); ?>">
    
    <!-- Schema.org markup for Google -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "WebSite",
        "name": "<?php echo esc_js(get_bloginfo('name')); ?>",
        "alternateName": "GridBot Command Center",
        "url": "<?php echo home_url(); ?>",
        "description": "<?php echo esc_js(get_bloginfo('description')); ?>",
        "potentialAction": {
            "@type": "SearchAction",
            "target": "<?php echo home_url(); ?>/?s={search_term_string}",
            "query-input": "required name=search_term_string"
        }
    }
    </script>
    
    <!-- Performance Monitoring -->
    <script>
        // Start performance timing
        window.performance.mark('theme-head-start');
        
        // Theme configuration
        window.gridbotccConfig = {
            themeUri: '<?php echo get_template_directory_uri(); ?>',
            ajaxUrl: '<?php echo admin_url('admin-ajax.php'); ?>',
            is3DEnabled: <?php echo gridbotcc_is_3d_enabled() ? 'true' : 'false'; ?>,
            particleCount: <?php echo gridbotcc_get_particle_count(); ?>,
            animationSpeed: <?php echo gridbotcc_get_animation_speed(); ?>,
            isUserLoggedIn: <?php echo is_user_logged_in() ? 'true' : 'false'; ?>
        };
    </script>
</head>

<body <?php body_class('gridbot-theme'); ?>>
    <?php wp_body_open(); ?>
    
    <!-- 3D Background Canvas -->
    <div class="three-bg" id="three-background">
        <canvas class="particle-canvas" id="particle-canvas"></canvas>
    </div>
    
    <!-- Loading Screen -->
    <div id="loading-screen" class="loading-screen">
        <div class="loading-content">
            <div class="loading-logo">
                <h1>GridBot<span class="text-primary">CC</span></h1>
            </div>
            <div class="loading-bar">
                <div class="loading-progress" id="loading-progress"></div>
            </div>
            <p class="loading-text">Initializing 3D Trading Environment...</p>
        </div>
    </div>
    
    <!-- Navigation -->
    <nav class="nav-3d" id="main-navigation">
        <div class="nav-container">
            <div class="nav-brand">
                <a href="<?php echo home_url(); ?>" class="brand-logo">
                    <span class="logo-text">GridBot<span class="text-primary">CC</span></span>
                </a>
            </div>
            
            <div class="nav-menu" id="nav-menu">
                <?php
                wp_nav_menu(array(
                    'theme_location' => 'primary',
                    'menu_id' => 'primary-menu',
                    'menu_class' => 'nav-list',
                    'container' => false,
                    'fallback_cb' => 'gridbotcc_default_menu',
                ));
                ?>
            </div>
            
            <div class="nav-actions">
                <button class="btn btn-secondary" id="demo-button">Demo</button>
                <button class="btn btn-primary" id="access-button">Access Platform</button>
                
                <!-- Mobile Menu Toggle -->
                <button class="mobile-toggle" id="mobile-toggle" aria-label="Toggle mobile menu">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
        
        <!-- Mobile Menu -->
        <div class="mobile-menu" id="mobile-menu">
            <?php
            wp_nav_menu(array(
                'theme_location' => 'primary',
                'menu_id' => 'mobile-primary-menu',
                'menu_class' => 'mobile-nav-list',
                'container' => false,
                'fallback_cb' => 'gridbotcc_default_menu',
            ));
            ?>
        </div>
    </nav>
    
    <!-- Skip to content link for accessibility -->
    <a class="skip-link screen-reader-text" href="#main-content">
        <?php _e('Skip to content', 'gridbot-command-center'); ?>
    </a>
    
    <!-- Performance Marker -->
    <script>
        window.performance.mark('theme-header-end');
    </script>
    
    <!-- Main Content Wrapper -->
    <div id="page-wrapper" class="page-wrapper">