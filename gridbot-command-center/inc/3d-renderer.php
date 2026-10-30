<?php
/**
 * 3D Renderer Functions
 * 
 * @package GridBot_Command_Center
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Generate 3D Scene Configuration
 */
function gridbotcc_get_3d_config() {
    return array(
        'scene' => array(
            'background' => '#0a0a0a',
            'fog' => array(
                'enabled' => true,
                'color' => '#0a0a0a',
                'near' => 1,
                'far' => 1000
            )
        ),
        'camera' => array(
            'position' => array(0, 0, 50),
            'fov' => 75,
            'near' => 0.1,
            'far' => 1000
        ),
        'renderer' => array(
            'antialias' => true,
            'alpha' => false,
            'powerPreference' => 'high-performance'
        ),
        'lighting' => array(
            'ambient' => array(
                'color' => '#404040',
                'intensity' => 0.4
            ),
            'directional' => array(
                'color' => '#2dd4bf',
                'intensity' => 0.8,
                'position' => array(10, 10, 5)
            ),
            'point' => array(
                'color' => '#f59e0b',
                'intensity' => 0.6,
                'position' => array(-10, -10, 10),
                'distance' => 50
            )
        ),
        'materials' => array(
            'grid' => array(
                'color' => '#2dd4bf',
                'emissive' => '#2dd4bf',
                'emissiveIntensity' => 0.2,
                'transparent' => true,
                'opacity' => 0.8
            ),
            'particles' => array(
                'color' => '#2dd4bf',
                'size' => 0.5,
                'transparent' => true,
                'opacity' => 0.6
            ),
            'orders' => array(
                'buy' => '#059669',
                'sell' => '#dc2626',
                'emissiveIntensity' => 0.3
            )
        )
    );
}

/**
 * Generate Particle System Configuration
 */
function gridbotcc_get_particle_config() {
    return array(
        'count' => gridbotcc_get_particle_count(),
        'properties' => array(
            'size' => array('min' => 0.1, 'max' => 1.0),
            'speed' => array('min' => 0.1, 'max' => 0.5),
            'opacity' => array('min' => 0.2, 'max' => 0.8),
            'color' => array('#2dd4bf', '#f59e0b', '#059669')
        ),
        'behavior' => array(
            'movement' => 'float',
            'interaction' => 'mouse',
            'connection' => true,
            'connectionDistance' => 100
        ),
        'physics' => array(
            'gravity' => 0,
            'bounce' => 0.8,
            'friction' => 0.99
        )
    );
}

/**
 * Generate Trading Visualization Configuration
 */
function gridbotcc_get_trading_config() {
    return array(
        'grid' => array(
            'size' => 20,
            'spacing' => 2,
            'levels' => 10,
            'animation' => array(
                'speed' => gridbotcc_get_animation_speed(),
                'easing' => 'easeInOutQuad'
            )
        ),
        'price' => array(
            'range' => array('min' => 80, 'max' => 120),
            'volatility' => 0.02,
            'trend' => 'sideways'
        ),
        'orders' => array(
            'buy' => array(
                'count' => 5,
                'spread' => 0.5,
                'quantity' => 100
            ),
            'sell' => array(
                'count' => 5,
                'spread' => 0.5,
                'quantity' => 100
            )
        ),
        'profit' => array(
            'realized' => 0,
            'unrealized' => 0,
            'total_trades' => 0,
            'success_rate' => 0
        )
    );
}

/**
 * Generate 3D Models Data
 */
function gridbotcc_get_3d_models() {
    return array(
        'grid_cube' => array(
            'geometry' => 'box',
            'size' => array(1, 1, 1),
            'material' => 'grid',
            'position' => array(0, 0, 0)
        ),
        'order_sphere' => array(
            'geometry' => 'sphere',
            'radius' => 0.5,
            'material' => 'orders',
            'position' => array(0, 0, 0)
        ),
        'price_line' => array(
            'geometry' => 'cylinder',
            'radius' => 0.1,
            'height' => 10,
            'material' => 'price',
            'position' => array(0, 0, 0)
        )
    );
}

/**
 * Generate Animation Sequences
 */
function gridbotcc_get_animation_sequences() {
    return array(
        'hero' => array(
            'duration' => 3000,
            'easing' => 'easeInOutQuad',
            'keyframes' => array(
                array(
                    'time' => 0,
                    'camera' => array(0, 0, 100),
                    'rotation' => array(0, 0, 0)
                ),
                array(
                    'time' => 0.5,
                    'camera' => array(20, 10, 50),
                    'rotation' => array(0.1, 0.2, 0)
                ),
                array(
                    'time' => 1,
                    'camera' => array(0, 0, 50),
                    'rotation' => array(0, 0, 0)
                )
            )
        ),
        'grid_deploy' => array(
            'duration' => 2000,
            'easing' => 'easeOutBounce',
            'stagger' => 100
        ),
        'profit_accumulation' => array(
            'duration' => 1500,
            'easing' => 'easeInOutQuad',
            'repeat' => -1,
            'yoyo' => true
        )
    );
}

/**
 * Generate Shader Materials Configuration
 */
function gridbotcc_get_shader_config() {
    return array(
        'vertex_shader' => '
            uniform float time;
            uniform vec2 mouse;
            varying vec2 vUv;
            varying vec3 vPosition;
            
            void main() {
                vUv = uv;
                vPosition = position;
                
                vec3 pos = position;
                float wave = sin(time * 2.0 + position.x * 0.1) * 0.1;
                pos.z += wave;
                
                gl_Position = projectionMatrix * modelViewMatrix * vec4(pos, 1.0);
            }
        ',
        'fragment_shader' => '
            uniform float time;
            uniform vec2 resolution;
            uniform vec3 color;
            varying vec2 vUv;
            varying vec3 vPosition;
            
            void main() {
                vec2 uv = vUv;
                float glow = sin(time * 3.0 + uv.x * 10.0) * 0.5 + 0.5;
                
                vec3 finalColor = color * glow;
                gl_FragColor = vec4(finalColor, 0.8);
            }
        ',
        'uniforms' => array(
            'time' => array('value' => 0),
            'mouse' => array('value' => array(0, 0)),
            'color' => array('value' => array(0.176, 0.831, 0.745)),
            'resolution' => array('value' => array(1920, 1080))
        )
    );
}

/**
 * Generate Interactive Elements Configuration
 */
function gridbotcc_get_interaction_config() {
    return array(
        'mouse' => array(
            'enabled' => true,
            'sensitivity' => 0.01,
            'inertia' => 0.95
        ),
        'touch' => array(
            'enabled' => true,
            'gestures' => array('pan', 'pinch', 'rotate'),
            'sensitivity' => 0.005
        ),
        'keyboard' => array(
            'enabled' => true,
            'shortcuts' => array(
                'space' => 'toggle_animation',
                'r' => 'reset_view',
                'f' => 'fullscreen'
            )
        ),
        'gamepad' => array(
            'enabled' => false,
            'deadzone' => 0.1
        )
    );
}

/**
 * Generate Performance Settings
 */
function gridbotcc_get_performance_config() {
    return array(
        'quality' => array(
            'low' => array(
                'particles' => 500,
                'shadows' => false,
                'antialias' => false,
                'effects' => false
            ),
            'medium' => array(
                'particles' => 1000,
                'shadows' => true,
                'antialias' => true,
                'effects' => true
            ),
            'high' => array(
                'particles' => 2000,
                'shadows' => true,
                'antialias' => true,
                'effects' => true,
                'postprocessing' => true
            )
        ),
        'optimization' => array(
            'frustum_culling' => true,
            'occlusion_culling' => true,
            'level_of_detail' => true,
            'instanced_rendering' => true,
            'texture_atlas' => true
        )
    );
}

/**
 * Generate Color Schemes
 */
function gridbotcc_get_color_schemes() {
    return array(
        'default' => array(
            'primary' => '#2dd4bf',
            'secondary' => '#f59e0b',
            'background' => '#0a0a0a',
            'surface' => '#1a1a1a'
        ),
        'dark' => array(
            'primary' => '#1e40af',
            'secondary' => '#7c3aed',
            'background' => '#0f172a',
            'surface' => '#1e293b'
        ),
        'light' => array(
            'primary' => '#0891b2',
            'secondary' => '#059669',
            'background' => '#f8fafc',
            'surface' => '#ffffff'
        ),
        'neon' => array(
            'primary' => '#00ffff',
            'secondary' => '#ff00ff',
            'background' => '#000000',
            'surface' => '#0a0a0a'
        )
    );
}

/**
 * Get Current Color Scheme
 */
function gridbotcc_get_current_color_scheme() {
    $scheme = get_option('gridbotcc_color_scheme', 'default');
    $schemes = gridbotcc_get_color_schemes();
    
    return isset($schemes[$scheme]) ? $schemes[$scheme] : $schemes['default'];
}

/**
 * Localize 3D Configuration to JavaScript
 */
function gridbotcc_localize_3d_config() {
    $config = array(
        'scene' => gridbotcc_get_3d_config(),
        'particles' => gridbotcc_get_particle_config(),
        'trading' => gridbotcc_get_trading_config(),
        'models' => gridbotcc_get_3d_models(),
        'animations' => gridbotcc_get_animation_sequences(),
        'shaders' => gridbotcc_get_shader_config(),
        'interactions' => gridbotcc_get_interaction_config(),
        'performance' => gridbotcc_get_performance_config(),
        'colors' => gridbotcc_get_current_color_scheme()
    );
    
    return $config;
}

/**
 * Render 3D Scene Container
 */
function gridbotcc_render_3d_scene($id = 'three-scene', $class = 'three-scene-container') {
    ob_start();
    ?>
    <div id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class); ?>" data-3d-enabled="<?php echo gridbotcc_is_3d_enabled() ? 'true' : 'false'; ?>">
        <canvas id="<?php echo esc_attr($id); ?>-canvas" class="three-canvas"></canvas>
        <div class="three-ui-overlay">
            <div class="three-controls">
                <button class="three-control-btn" id="<?php echo esc_attr($id); ?>-play" title="Play Animation">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M8 5v14l11-7z"/>
                    </svg>
                </button>
                <button class="three-control-btn" id="<?php echo esc_attr($id); ?>-pause" title="Pause Animation">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M6 19h4V5H6v14zm8-14v14h4V5h-4z"/>
                    </svg>
                </button>
                <button class="three-control-btn" id="<?php echo esc_attr($id); ?>-reset" title="Reset View">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M12 5V1L7 6l5 5V7c3.31 0 6 2.69 6 6s-2.69 6-6 6-6-2.69-6-6H4c0 4.42 3.58 8 8 8s8-3.58 8-8-3.58-8-8-8z"/>
                    </svg>
                </button>
                <button class="three-control-btn" id="<?php echo esc_attr($id); ?>-fullscreen" title="Fullscreen">
                    <svg viewBox="0 0 24 24" fill="currentColor">
                        <path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/>
                    </svg>
                </button>
            </div>
            
            <div class="three-info">
                <div class="three-fps" id="<?php echo esc_attr($id); ?>-fps">FPS: --</div>
                <div class="three-triangles" id="<?php echo esc_attr($id); ?>-triangles">Triangles: --</div>
            </div>
        </div>
        
        <div class="three-loading" id="<?php echo esc_attr($id); ?>-loading">
            <div class="three-loading-spinner"></div>
            <p>Loading 3D Scene...</p>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Enqueue 3D Assets
 */
function gridbotcc_enqueue_3d_assets() {
    // 3D textures and models
    $assets = array(
        'grid_texture' => get_template_directory_uri() . '/assets/images/grid-texture.png',
        'particle_sprite' => get_template_directory_uri() . '/assets/images/particle-sprite.png',
        'environment_map' => get_template_directory_uri() . '/assets/images/environment-map.png',
        'normal_map' => get_template_directory_uri() . '/assets/images/normal-map.png'
    );
    
    wp_localize_script('gridbotcc-3d', 'gridbotcc3dAssets', $assets);
    wp_localize_script('gridbotcc-3d', 'gridbotcc3dConfig', gridbotcc_localize_3d_config());
}
add_action('wp_enqueue_scripts', 'gridbotcc_enqueue_3d_assets');

/**
 * Add 3D Scene to Footer
 */
function gridbotcc_add_background_3d() {
    if (gridbotcc_is_3d_enabled()) {
        echo gridbotcc_render_3d_scene('background-scene', 'background-3d-scene');
    }
}
// The page background scene is #three-background in header.php (see assets/js/3d-scenes.js).
// This helper remains available for templates that want an explicit scene container.
// add_action('wp_footer', 'gridbotcc_add_background_3d', 1);