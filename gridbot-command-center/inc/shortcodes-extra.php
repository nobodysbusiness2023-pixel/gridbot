<?php
/**
 * Shortcodes that were registered in inc/shortcodes.php but never implemented:
 * pricing table, testimonials, strategy comparison, demo request.
 *
 * @package GridBot_Command_Center
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Pricing Table Shortcode
 * [gridbot_pricing_table currency="$" highlight="pro"]
 */
function gridbotcc_pricing_table_shortcode($atts) {
    $atts = shortcode_atts(array(
        'currency'  => '$',
        'highlight' => 'pro',
    ), $atts);

    $plans = apply_filters('gridbotcc_pricing_plans', array(
        'starter' => array(
            'name'     => __('Starter', 'gridbot-command-center'),
            'price'    => '29',
            'period'   => __('/month', 'gridbot-command-center'),
            'features' => array(
                __('Up to 3 active grids', 'gridbot-command-center'),
                __('1 exchange connection', 'gridbot-command-center'),
                __('Real-time grid visualization', 'gridbot-command-center'),
                __('Email support', 'gridbot-command-center'),
            ),
            'cta'      => __('Start Free Trial', 'gridbot-command-center'),
        ),
        'pro' => array(
            'name'     => __('Professional', 'gridbot-command-center'),
            'price'    => '79',
            'period'   => __('/month', 'gridbot-command-center'),
            'features' => array(
                __('Unlimited active grids', 'gridbot-command-center'),
                __('5 exchange connections', 'gridbot-command-center'),
                __('Full-cycle exit automation', 'gridbot-command-center'),
                __('Performance reporting', 'gridbot-command-center'),
                __('Priority support', 'gridbot-command-center'),
            ),
            'cta'      => __('Access Platform', 'gridbot-command-center'),
        ),
        'institutional' => array(
            'name'     => __('Institutional', 'gridbot-command-center'),
            'price'    => __('Custom', 'gridbot-command-center'),
            'period'   => '',
            'features' => array(
                __('Dedicated infrastructure', 'gridbot-command-center'),
                __('Unlimited exchanges and pairs', 'gridbot-command-center'),
                __('API access', 'gridbot-command-center'),
                __('Custom risk controls', 'gridbot-command-center'),
                __('Dedicated account manager', 'gridbot-command-center'),
            ),
            'cta'      => __('Contact Sales', 'gridbot-command-center'),
        ),
    ));

    ob_start();
    ?>
    <div class="gridbot-pricing-table">
        <div class="pricing-grid">
            <?php foreach ($plans as $key => $plan) : ?>
            <div class="pricing-card card-3d <?php echo $key === $atts['highlight'] ? 'highlighted' : ''; ?>">
                <?php if ($key === $atts['highlight']) : ?>
                <span class="pricing-badge"><?php esc_html_e('Most Popular', 'gridbot-command-center'); ?></span>
                <?php endif; ?>
                <h3 class="pricing-name"><?php echo esc_html($plan['name']); ?></h3>
                <div class="pricing-price">
                    <?php if (is_numeric($plan['price'])) : ?>
                        <span class="pricing-currency"><?php echo esc_html($atts['currency']); ?></span>
                    <?php endif; ?>
                    <span class="pricing-amount"><?php echo esc_html($plan['price']); ?></span>
                    <span class="pricing-period"><?php echo esc_html($plan['period']); ?></span>
                </div>
                <ul class="pricing-features">
                    <?php foreach ($plan['features'] as $feature) : ?>
                    <li><?php echo esc_html($feature); ?></li>
                    <?php endforeach; ?>
                </ul>
                <a href="#access" class="btn <?php echo $key === $atts['highlight'] ? 'btn-primary' : 'btn-secondary'; ?> pricing-cta"><?php echo esc_html($plan['cta']); ?></a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Testimonials Shortcode
 * [gridbot_testimonials count="3"]
 *
 * Default entries are placeholders. Replace them with real, attributable quotes
 * through the `gridbotcc_testimonials` filter before publishing.
 */
function gridbotcc_testimonials_shortcode($atts) {
    $atts = shortcode_atts(array(
        'count' => '3',
    ), $atts);

    $testimonials = apply_filters('gridbotcc_testimonials', array(
        array(
            'quote'  => __('Placeholder testimonial. Replace with a real, attributable quote before launch.', 'gridbot-command-center'),
            'name'   => __('Sample Trader', 'gridbot-command-center'),
            'role'   => __('Range-bound market trader', 'gridbot-command-center'),
        ),
        array(
            'quote'  => __('Placeholder testimonial. Replace with a real, attributable quote before launch.', 'gridbot-command-center'),
            'name'   => __('Sample User', 'gridbot-command-center'),
            'role'   => __('Automation seeker', 'gridbot-command-center'),
        ),
        array(
            'quote'  => __('Placeholder testimonial. Replace with a real, attributable quote before launch.', 'gridbot-command-center'),
            'name'   => __('Sample Investor', 'gridbot-command-center'),
            'role'   => __('Capital preservation focused', 'gridbot-command-center'),
        ),
    ));
    $testimonials = array_slice($testimonials, 0, max(1, intval($atts['count'])));

    ob_start();
    ?>
    <div class="gridbot-testimonials">
        <div class="testimonials-grid">
            <?php foreach ($testimonials as $item) : ?>
            <blockquote class="testimonial-card card-3d">
                <p class="testimonial-quote">&ldquo;<?php echo esc_html($item['quote']); ?>&rdquo;</p>
                <footer class="testimonial-author">
                    <span class="testimonial-name"><?php echo esc_html($item['name']); ?></span>
                    <?php if (!empty($item['role'])) : ?>
                    <span class="testimonial-role"><?php echo esc_html($item['role']); ?></span>
                    <?php endif; ?>
                </footer>
            </blockquote>
            <?php endforeach; ?>
        </div>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Strategy Comparison Shortcode
 * [gridbot_strategy_comparison]
 */
function gridbotcc_strategy_comparison_shortcode($atts) {
    $rows = apply_filters('gridbotcc_strategy_comparison', array(
        array(__('Requires predicting direction', 'gridbot-command-center'), __('No', 'gridbot-command-center'), __('No', 'gridbot-command-center'), __('Yes', 'gridbot-command-center')),
        array(__('Profits from sideways markets', 'gridbot-command-center'), __('Yes', 'gridbot-command-center'), __('Partially', 'gridbot-command-center'), __('Rarely', 'gridbot-command-center')),
        array(__('Emotional decisions removed', 'gridbot-command-center'), __('Yes', 'gridbot-command-center'), __('Yes', 'gridbot-command-center'), __('No', 'gridbot-command-center')),
        array(__('Defined capital exposure', 'gridbot-command-center'), __('Yes', 'gridbot-command-center'), __('Yes', 'gridbot-command-center'), __('Depends', 'gridbot-command-center')),
        array(__('Time required per day', 'gridbot-command-center'), __('Minutes', 'gridbot-command-center'), __('Minutes', 'gridbot-command-center'), __('Hours', 'gridbot-command-center')),
        array(__('Risk in strong trends', 'gridbot-command-center'), __('Exposure beyond range', 'gridbot-command-center'), __('Slow to react', 'gridbot-command-center'), __('Operator dependent', 'gridbot-command-center')),
    ));

    ob_start();
    ?>
    <div class="gridbot-strategy-comparison card-3d">
        <table class="comparison-table">
            <thead>
                <tr>
                    <th><?php esc_html_e('Criteria', 'gridbot-command-center'); ?></th>
                    <th><?php esc_html_e('Grid Trading', 'gridbot-command-center'); ?></th>
                    <th><?php esc_html_e('Dollar-Cost Averaging', 'gridbot-command-center'); ?></th>
                    <th><?php esc_html_e('Discretionary Trading', 'gridbot-command-center'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row) : ?>
                <tr>
                    <?php foreach ($row as $i => $cell) : ?>
                        <?php if ($i === 0) : ?>
                            <th scope="row"><?php echo esc_html($cell); ?></th>
                        <?php else : ?>
                            <td><?php echo esc_html($cell); ?></td>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
    return ob_get_clean();
}

/**
 * Demo Request Shortcode
 * [gridbot_demo_request title="Request a Demo"]
 */
function gridbotcc_demo_request_shortcode($atts) {
    $atts = shortcode_atts(array(
        'title'       => __('Request a Demo', 'gridbot-command-center'),
        'description' => __('Tell us a little about your trading and we will set up a paper-trading walkthrough.', 'gridbot-command-center'),
    ), $atts);
    $fid = uniqid('demo-');

    ob_start();
    ?>
    <div class="gridbot-demo-request card-3d">
        <h3><?php echo esc_html($atts['title']); ?></h3>
        <p><?php echo esc_html($atts['description']); ?></p>

        <form class="demo-request-form" id="<?php echo esc_attr($fid); ?>">
            <div class="form-row">
                <div class="form-group">
                    <label for="<?php echo esc_attr($fid); ?>-name"><?php esc_html_e('Name *', 'gridbot-command-center'); ?></label>
                    <input type="text" id="<?php echo esc_attr($fid); ?>-name" name="name" required class="form-control">
                </div>
                <div class="form-group">
                    <label for="<?php echo esc_attr($fid); ?>-email"><?php esc_html_e('Email *', 'gridbot-command-center'); ?></label>
                    <input type="email" id="<?php echo esc_attr($fid); ?>-email" name="email" required class="form-control">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="<?php echo esc_attr($fid); ?>-experience"><?php esc_html_e('Grid trading experience', 'gridbot-command-center'); ?></label>
                    <select id="<?php echo esc_attr($fid); ?>-experience" name="experience" class="form-control">
                        <option value="new"><?php esc_html_e('New to grid trading', 'gridbot-command-center'); ?></option>
                        <option value="some"><?php esc_html_e('Some experience', 'gridbot-command-center'); ?></option>
                        <option value="experienced"><?php esc_html_e('Experienced', 'gridbot-command-center'); ?></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="<?php echo esc_attr($fid); ?>-capital"><?php esc_html_e('Capital range', 'gridbot-command-center'); ?></label>
                    <select id="<?php echo esc_attr($fid); ?>-capital" name="capital_range" class="form-control">
                        <option value="under-5k"><?php esc_html_e('Under $5,000', 'gridbot-command-center'); ?></option>
                        <option value="5k-25k"><?php esc_html_e('$5,000 - $25,000', 'gridbot-command-center'); ?></option>
                        <option value="25k-100k"><?php esc_html_e('$25,000 - $100,000', 'gridbot-command-center'); ?></option>
                        <option value="over-100k"><?php esc_html_e('Over $100,000', 'gridbot-command-center'); ?></option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="<?php echo esc_attr($fid); ?>-message"><?php esc_html_e('Anything specific you want to see?', 'gridbot-command-center'); ?></label>
                <textarea id="<?php echo esc_attr($fid); ?>-message" name="message" rows="3" class="form-control"></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><?php esc_html_e('Request Demo', 'gridbot-command-center'); ?></button>
        </form>

        <div class="demo-response" style="display: none;"></div>
    </div>
    <?php
    return ob_get_clean();
}
