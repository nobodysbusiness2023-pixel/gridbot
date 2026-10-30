<?php
/**
 * Form submissions for the contact, newsletter and demo-request shortcodes.
 * Front-end JS (assets/js/main.js) posts to admin-ajax with action=gridbotcc_submit_form.
 *
 * @package GridBot_Command_Center
 */

if (!defined('ABSPATH')) {
    exit;
}

function gridbotcc_handle_form_submission() {
    if (!check_ajax_referer('gridbotcc_form', 'nonce', false)) {
        wp_send_json_error(array('message' => __('Security check failed. Please reload the page and try again.', 'gridbot-command-center')), 403);
    }

    $type  = isset($_POST['form_type']) ? sanitize_key($_POST['form_type']) : 'contact';
    $email = isset($_POST['email']) ? sanitize_email(wp_unslash($_POST['email'])) : '';
    $name  = isset($_POST['name']) ? sanitize_text_field(wp_unslash($_POST['name'])) : '';

    if (!is_email($email)) {
        wp_send_json_error(array('message' => __('Please enter a valid email address.', 'gridbot-command-center')), 400);
    }

    // Honeypot: real users never fill this.
    if (!empty($_POST['website'])) {
        wp_send_json_success(array('message' => __('Thank you!', 'gridbot-command-center')));
    }

    $fields = array();
    foreach (array('name', 'email', 'phone', 'company', 'subject', 'message', 'experience', 'capital_range') as $key) {
        if (isset($_POST[$key])) {
            $fields[$key] = ($key === 'message')
                ? sanitize_textarea_field(wp_unslash($_POST[$key]))
                : sanitize_text_field(wp_unslash($_POST[$key]));
        }
    }

    $submission = array(
        'type'   => $type,
        'time'   => current_time('mysql'),
        'ip'     => isset($_SERVER['REMOTE_ADDR']) ? sanitize_text_field(wp_unslash($_SERVER['REMOTE_ADDR'])) : '',
        'fields' => $fields,
    );
    gridbotcc_store_submission($submission);

    $to = get_option('gridbotcc_contact_email');
    if (!is_email($to)) {
        $to = get_option('admin_email');
    }
    $site = get_bloginfo('name');

    switch ($type) {
        case 'newsletter':
            $subject = sprintf(__('[%s] New newsletter signup', 'gridbot-command-center'), $site);
            $message = __('A visitor subscribed to the newsletter.', 'gridbot-command-center');
            $reply   = __('Successfully subscribed! Check your email for confirmation.', 'gridbot-command-center');
            break;
        case 'demo':
            $subject = sprintf(__('[%s] Demo request from %s', 'gridbot-command-center'), $site, $name);
            $message = __('A visitor requested a demo.', 'gridbot-command-center');
            $reply   = __('Thanks! We will be in touch to schedule your demo.', 'gridbot-command-center');
            break;
        default:
            $subject = sprintf(__('[%s] Contact form: %s', 'gridbot-command-center'), $site, isset($fields['subject']) ? $fields['subject'] : '');
            $message = __('A visitor sent a message through the contact form.', 'gridbot-command-center');
            $reply   = __("Thank you for your message! We'll get back to you soon.", 'gridbot-command-center');
            break;
    }

    $body = $message . "\n\n";
    foreach ($fields as $key => $value) {
        $body .= ucfirst(str_replace('_', ' ', $key)) . ': ' . $value . "\n";
    }
    $headers = array('Reply-To: ' . ($name ? $name . ' <' . $email . '>' : $email));

    // Delivery failure is logged, not shown: the submission is already stored for the admin.
    if (!wp_mail($to, $subject, $body, $headers) && defined('WP_DEBUG') && WP_DEBUG) {
        error_log('GridBot CC: wp_mail failed for ' . $type . ' submission to ' . $to);
    }

    wp_send_json_success(array('message' => $reply));
}
add_action('wp_ajax_gridbotcc_submit_form', 'gridbotcc_handle_form_submission');
add_action('wp_ajax_nopriv_gridbotcc_submit_form', 'gridbotcc_handle_form_submission');

/**
 * Keep the latest submissions so nothing is lost if email delivery fails.
 */
function gridbotcc_store_submission($submission) {
    $all = get_option('gridbotcc_submissions', array());
    if (!is_array($all)) {
        $all = array();
    }
    array_unshift($all, $submission);
    $all = array_slice($all, 0, 500);
    update_option('gridbotcc_submissions', $all, false);
}

/**
 * Admin page listing stored submissions.
 */
function gridbotcc_add_submissions_page() {
    add_submenu_page(
        'gridbotcc-options',
        __('Form Submissions', 'gridbot-command-center'),
        __('Submissions', 'gridbot-command-center'),
        'manage_options',
        'gridbotcc-submissions',
        'gridbotcc_submissions_page'
    );
}
add_action('admin_menu', 'gridbotcc_add_submissions_page', 20);

function gridbotcc_submissions_page() {
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.', 'gridbot-command-center'));
    }
    $all = get_option('gridbotcc_submissions', array());
    ?>
    <div class="wrap">
        <h1><?php esc_html_e('Form Submissions', 'gridbot-command-center'); ?></h1>
        <p><?php printf(esc_html__('Notifications go to %s. Change it under Theme Options.', 'gridbot-command-center'), esc_html(get_option('gridbotcc_contact_email') ?: get_option('admin_email'))); ?></p>
        <?php if (empty($all)) : ?>
            <p><?php esc_html_e('No submissions yet.', 'gridbot-command-center'); ?></p>
        <?php else : ?>
        <table class="widefat striped">
            <thead><tr>
                <th><?php esc_html_e('Time', 'gridbot-command-center'); ?></th>
                <th><?php esc_html_e('Type', 'gridbot-command-center'); ?></th>
                <th><?php esc_html_e('Name', 'gridbot-command-center'); ?></th>
                <th><?php esc_html_e('Email', 'gridbot-command-center'); ?></th>
                <th><?php esc_html_e('Details', 'gridbot-command-center'); ?></th>
            </tr></thead>
            <tbody>
            <?php foreach ($all as $row) : $f = isset($row['fields']) ? $row['fields'] : array(); ?>
                <tr>
                    <td><?php echo esc_html($row['time']); ?></td>
                    <td><?php echo esc_html($row['type']); ?></td>
                    <td><?php echo esc_html(isset($f['name']) ? $f['name'] : ''); ?></td>
                    <td><?php echo esc_html(isset($f['email']) ? $f['email'] : ''); ?></td>
                    <td><?php
                        $details = array();
                        foreach ($f as $k => $v) {
                            if (in_array($k, array('name', 'email'), true) || $v === '') continue;
                            $details[] = esc_html(ucfirst(str_replace('_', ' ', $k)) . ': ' . $v);
                        }
                        echo implode('<br>', $details);
                    ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
    <?php
}
