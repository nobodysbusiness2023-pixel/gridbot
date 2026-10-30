<?php
/**
 * Custom Post Types and Taxonomies
 * 
 * @package GridBot_Command_Center
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Register Custom Meta Boxes for Grid Strategies
 */
function gridbotcc_add_meta_boxes() {
    // Grid Strategy Meta Box
    add_meta_box(
        'grid_strategy_details',
        __('Strategy Details', 'gridbot-command-center'),
        'gridbotcc_strategy_details_callback',
        'grid_strategies',
        'normal',
        'high'
    );
    
    // Trading Pair Meta Box
    add_meta_box(
        'trading_pair_details',
        __('Pair Information', 'gridbot-command-center'),
        'gridbotcc_pair_details_callback',
        'trading_pairs',
        'normal',
        'high'
    );
    
    // Performance Data Meta Box
    add_meta_box(
        'performance_data_details',
        __('Performance Metrics', 'gridbot-command-center'),
        'gridbotcc_performance_details_callback',
        'performance_data',
        'normal',
        'high'
    );
}
add_action('add_meta_boxes', 'gridbotcc_add_meta_boxes');

/**
 * Strategy Details Meta Box Callback
 */
function gridbotcc_strategy_details_callback($post) {
    wp_nonce_field('gridbotcc_save_meta_box_data', 'gridbotcc_meta_box_nonce');
    
    $capital_min = get_post_meta($post->ID, '_gridbotcc_capital_min', true);
    $capital_max = get_post_meta($post->ID, '_gridbotcc_capital_max', true);
    $grid_range = get_post_meta($post->ID, '_gridbotcc_grid_range', true);
    $grid_levels = get_post_meta($post->ID, '_gridbotcc_grid_levels', true);
    $expected_return = get_post_meta($post->ID, '_gridbotcc_expected_return', true);
    $risk_level = get_post_meta($post->ID, '_gridbotcc_risk_level', true);
    $difficulty = get_post_meta($post->ID, '_gridbotcc_difficulty', true);
    ?>
    <div class="gridbotcc-meta-box">
        <p>
            <label for="capital_min"><?php _e('Minimum Capital ($):', 'gridbot-command-center'); ?></label><br>
            <input type="number" id="capital_min" name="capital_min" value="<?php echo esc_attr($capital_min); ?>" class="widefat">
        </p>
        
        <p>
            <label for="capital_max"><?php _e('Maximum Capital ($):', 'gridbot-command-center'); ?></label><br>
            <input type="number" id="capital_max" name="capital_max" value="<?php echo esc_attr($capital_max); ?>" class="widefat">
        </p>
        
        <p>
            <label for="grid_range"><?php _e('Grid Range (%):', 'gridbot-command-center'); ?></label><br>
            <input type="number" id="grid_range" name="grid_range" value="<?php echo esc_attr($grid_range); ?>" class="widefat" step="0.1">
        </p>
        
        <p>
            <label for="grid_levels"><?php _e('Number of Grid Levels:', 'gridbot-command-center'); ?></label><br>
            <input type="number" id="grid_levels" name="grid_levels" value="<?php echo esc_attr($grid_levels); ?>" class="widefat">
        </p>
        
        <p>
            <label for="expected_return"><?php _e('Expected Monthly Return (%):', 'gridbot-command-center'); ?></label><br>
            <input type="number" id="expected_return" name="expected_return" value="<?php echo esc_attr($expected_return); ?>" class="widefat" step="0.1">
        </p>
        
        <p>
            <label for="risk_level"><?php _e('Risk Level:', 'gridbot-command-center'); ?></label><br>
            <select id="risk_level" name="risk_level" class="widefat">
                <option value="low" <?php selected($risk_level, 'low'); ?>><?php _e('Low', 'gridbot-command-center'); ?></option>
                <option value="medium" <?php selected($risk_level, 'medium'); ?>><?php _e('Medium', 'gridbot-command-center'); ?></option>
                <option value="high" <?php selected($risk_level, 'high'); ?>><?php _e('High', 'gridbot-command-center'); ?></option>
            </select>
        </p>
        
        <p>
            <label for="difficulty"><?php _e('Difficulty Level:', 'gridbot-command-center'); ?></label><br>
            <select id="difficulty" name="difficulty" class="widefat">
                <option value="beginner" <?php selected($difficulty, 'beginner'); ?>><?php _e('Beginner', 'gridbot-command-center'); ?></option>
                <option value="intermediate" <?php selected($difficulty, 'intermediate'); ?>><?php _e('Intermediate', 'gridbot-command-center'); ?></option>
                <option value="advanced" <?php selected($difficulty, 'advanced'); ?>><?php _e('Advanced', 'gridbot-command-center'); ?></option>
            </select>
        </p>
    </div>
    <?php
}

/**
 * Trading Pair Details Meta Box Callback
 */
function gridbotcc_pair_details_callback($post) {
    wp_nonce_field('gridbotcc_save_meta_box_data', 'gridbotcc_meta_box_nonce');
    
    $symbol = get_post_meta($post->ID, '_gridbotcc_symbol', true);
    $base_currency = get_post_meta($post->ID, '_gridbotcc_base_currency', true);
    $quote_currency = get_post_meta($post->ID, '_gridbotcc_quote_currency', true);
    $exchange = get_post_meta($post->ID, '_gridbotcc_exchange', true);
    $min_volume = get_post_meta($post->ID, '_gridbotcc_min_volume', true);
    $volatility = get_post_meta($post->ID, '_gridbotcc_volatility', true);
    ?>
    <div class="gridbotcc-meta-box">
        <p>
            <label for="symbol"><?php _e('Trading Symbol:', 'gridbot-command-center'); ?></label><br>
            <input type="text" id="symbol" name="symbol" value="<?php echo esc_attr($symbol); ?>" class="widefat">
        </p>
        
        <p>
            <label for="base_currency"><?php _e('Base Currency:', 'gridbot-command-center'); ?></label><br>
            <input type="text" id="base_currency" name="base_currency" value="<?php echo esc_attr($base_currency); ?>" class="widefat">
        </p>
        
        <p>
            <label for="quote_currency"><?php _e('Quote Currency:', 'gridbot-command-center'); ?></label><br>
            <input type="text" id="quote_currency" name="quote_currency" value="<?php echo esc_attr($quote_currency); ?>" class="widefat">
        </p>
        
        <p>
            <label for="exchange"><?php _e('Exchange:', 'gridbot-command-center'); ?></label><br>
            <input type="text" id="exchange" name="exchange" value="<?php echo esc_attr($exchange); ?>" class="widefat">
        </p>
        
        <p>
            <label for="min_volume"><?php _e('Minimum Volume (24h):', 'gridbot-command-center'); ?></label><br>
            <input type="number" id="min_volume" name="min_volume" value="<?php echo esc_attr($min_volume); ?>" class="widefat">
        </p>
        
        <p>
            <label for="volatility"><?php _e('Volatility Index:', 'gridbot-command-center'); ?></label><br>
            <input type="number" id="volatility" name="volatility" value="<?php echo esc_attr($volatility); ?>" class="widefat" step="0.01" min="0" max="1">
        </p>
    </div>
    <?php
}

/**
 * Performance Data Meta Box Callback
 */
function gridbotcc_performance_details_callback($post) {
    wp_nonce_field('gridbotcc_save_meta_box_data', 'gridbotcc_meta_box_nonce');
    
    $start_date = get_post_meta($post->ID, '_gridbotcc_start_date', true);
    $end_date = get_post_meta($post->ID, '_gridbotcc_end_date', true);
    $initial_capital = get_post_meta($post->ID, '_gridbotcc_initial_capital', true);
    $final_capital = get_post_meta($post->ID, '_gridbotcc_final_capital', true);
    $total_return = get_post_meta($post->ID, '_gridbotcc_total_return', true);
    $max_drawdown = get_post_meta($post->ID, '_gridbotcc_max_drawdown', true);
    $total_trades = get_post_meta($post->ID, '_gridbotcc_total_trades', true);
    $win_rate = get_post_meta($post->ID, '_gridbotcc_win_rate', true);
    ?>
    <div class="gridbotcc-meta-box">
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <p>
                <label for="start_date"><?php _e('Start Date:', 'gridbot-command-center'); ?></label><br>
                <input type="date" id="start_date" name="start_date" value="<?php echo esc_attr($start_date); ?>" class="widefat">
            </p>
            
            <p>
                <label for="end_date"><?php _e('End Date:', 'gridbot-command-center'); ?></label><br>
                <input type="date" id="end_date" name="end_date" value="<?php echo esc_attr($end_date); ?>" class="widefat">
            </p>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <p>
                <label for="initial_capital"><?php _e('Initial Capital ($):', 'gridbot-command-center'); ?></label><br>
                <input type="number" id="initial_capital" name="initial_capital" value="<?php echo esc_attr($initial_capital); ?>" class="widefat">
            </p>
            
            <p>
                <label for="final_capital"><?php _e('Final Capital ($):', 'gridbot-command-center'); ?></label><br>
                <input type="number" id="final_capital" name="final_capital" value="<?php echo esc_attr($final_capital); ?>" class="widefat">
            </p>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <p>
                <label for="total_return"><?php _e('Total Return (%):', 'gridbot-command-center'); ?></label><br>
                <input type="number" id="total_return" name="total_return" value="<?php echo esc_attr($total_return); ?>" class="widefat" step="0.01">
            </p>
            
            <p>
                <label for="max_drawdown"><?php _e('Max Drawdown (%):', 'gridbot-command-center'); ?></label><br>
                <input type="number" id="max_drawdown" name="max_drawdown" value="<?php echo esc_attr($max_drawdown); ?>" class="widefat" step="0.01">
            </p>
        </div>
        
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
            <p>
                <label for="total_trades"><?php _e('Total Trades:', 'gridbot-command-center'); ?></label><br>
                <input type="number" id="total_trades" name="total_trades" value="<?php echo esc_attr($total_trades); ?>" class="widefat">
            </p>
            
            <p>
                <label for="win_rate"><?php _e('Win Rate (%):', 'gridbot-command-center'); ?></label><br>
                <input type="number" id="win_rate" name="win_rate" value="<?php echo esc_attr($win_rate); ?>" class="widefat" step="0.01" min="0" max="100">
            </p>
        </div>
    </div>
    <?php
}

/**
 * Save Meta Box Data
 */
function gridbotcc_save_meta_box_data($post_id) {
    // Check nonce
    if (!isset($_POST['gridbotcc_meta_box_nonce']) || 
        !wp_verify_nonce($_POST['gridbotcc_meta_box_nonce'], 'gridbotcc_save_meta_box_data')) {
        return;
    }
    
    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }
    
    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }
    
    // Save strategy meta
    if (isset($_POST['capital_min'])) {
        update_post_meta($post_id, '_gridbotcc_capital_min', sanitize_text_field($_POST['capital_min']));
    }
    
    if (isset($_POST['capital_max'])) {
        update_post_meta($post_id, '_gridbotcc_capital_max', sanitize_text_field($_POST['capital_max']));
    }
    
    if (isset($_POST['grid_range'])) {
        update_post_meta($post_id, '_gridbotcc_grid_range', sanitize_text_field($_POST['grid_range']));
    }
    
    if (isset($_POST['grid_levels'])) {
        update_post_meta($post_id, '_gridbotcc_grid_levels', sanitize_text_field($_POST['grid_levels']));
    }
    
    if (isset($_POST['expected_return'])) {
        update_post_meta($post_id, '_gridbotcc_expected_return', sanitize_text_field($_POST['expected_return']));
    }
    
    if (isset($_POST['risk_level'])) {
        update_post_meta($post_id, '_gridbotcc_risk_level', sanitize_text_field($_POST['risk_level']));
    }
    
    if (isset($_POST['difficulty'])) {
        update_post_meta($post_id, '_gridbotcc_difficulty', sanitize_text_field($_POST['difficulty']));
    }
    
    // Save trading pair meta
    if (isset($_POST['symbol'])) {
        update_post_meta($post_id, '_gridbotcc_symbol', sanitize_text_field($_POST['symbol']));
    }
    
    if (isset($_POST['base_currency'])) {
        update_post_meta($post_id, '_gridbotcc_base_currency', sanitize_text_field($_POST['base_currency']));
    }
    
    if (isset($_POST['quote_currency'])) {
        update_post_meta($post_id, '_gridbotcc_quote_currency', sanitize_text_field($_POST['quote_currency']));
    }
    
    if (isset($_POST['exchange'])) {
        update_post_meta($post_id, '_gridbotcc_exchange', sanitize_text_field($_POST['exchange']));
    }
    
    if (isset($_POST['min_volume'])) {
        update_post_meta($post_id, '_gridbotcc_min_volume', sanitize_text_field($_POST['min_volume']));
    }
    
    if (isset($_POST['volatility'])) {
        update_post_meta($post_id, '_gridbotcc_volatility', sanitize_text_field($_POST['volatility']));
    }
    
    // Save performance data meta
    if (isset($_POST['start_date'])) {
        update_post_meta($post_id, '_gridbotcc_start_date', sanitize_text_field($_POST['start_date']));
    }
    
    if (isset($_POST['end_date'])) {
        update_post_meta($post_id, '_gridbotcc_end_date', sanitize_text_field($_POST['end_date']));
    }
    
    if (isset($_POST['initial_capital'])) {
        update_post_meta($post_id, '_gridbotcc_initial_capital', sanitize_text_field($_POST['initial_capital']));
    }
    
    if (isset($_POST['final_capital'])) {
        update_post_meta($post_id, '_gridbotcc_final_capital', sanitize_text_field($_POST['final_capital']));
    }
    
    if (isset($_POST['total_return'])) {
        update_post_meta($post_id, '_gridbotcc_total_return', sanitize_text_field($_POST['total_return']));
    }
    
    if (isset($_POST['max_drawdown'])) {
        update_post_meta($post_id, '_gridbotcc_max_drawdown', sanitize_text_field($_POST['max_drawdown']));
    }
    
    if (isset($_POST['total_trades'])) {
        update_post_meta($post_id, '_gridbotcc_total_trades', sanitize_text_field($_POST['total_trades']));
    }
    
    if (isset($_POST['win_rate'])) {
        update_post_meta($post_id, '_gridbotcc_win_rate', sanitize_text_field($_POST['win_rate']));
    }
}
add_action('save_post', 'gridbotcc_save_meta_box_data');

/**
 * Customize Admin Columns for Grid Strategies
 */
function gridbotcc_strategy_columns($columns) {
    $new_columns = array();
    $new_columns['cb'] = $columns['cb'];
    $new_columns['title'] = $columns['title'];
    $new_columns['risk_level'] = __('Risk Level', 'gridbot-command-center');
    $new_columns['difficulty'] = __('Difficulty', 'gridbot-command-center');
    $new_columns['expected_return'] = __('Expected Return', 'gridbot-command-center');
    $new_columns['date'] = $columns['date'];
    
    return $new_columns;
}
add_filter('manage_grid_strategies_posts_columns', 'gridbotcc_strategy_columns');

/**
 * Populate Custom Columns
 */
function gridbotcc_custom_column_content($column, $post_id) {
    switch ($column) {
        case 'risk_level':
            $risk_level = get_post_meta($post_id, '_gridbotcc_risk_level', true);
            echo esc_html(ucfirst($risk_level));
            break;
            
        case 'difficulty':
            $difficulty = get_post_meta($post_id, '_gridbotcc_difficulty', true);
            echo esc_html(ucfirst($difficulty));
            break;
            
        case 'expected_return':
            $expected_return = get_post_meta($post_id, '_gridbotcc_expected_return', true);
            echo esc_html($expected_return . '%');
            break;
    }
}
add_action('manage_grid_strategies_posts_custom_column', 'gridbotcc_custom_column_content', 10, 2);

/**
 * Make Columns Sortable
 */
function gridbotcc_sortable_columns($columns) {
    $columns['risk_level'] = 'risk_level';
    $columns['difficulty'] = 'difficulty';
    $columns['expected_return'] = 'expected_return';
    
    return $columns;
}
add_filter('manage_edit-grid_strategies_sortable_columns', 'gridbotcc_sortable_columns');