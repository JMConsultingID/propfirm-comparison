<?php
// Create Post Type Propfirm
function create_propfirm_post_type() {
    register_post_type('propfirm',
        array(
            'labels' => array(
                'name' => __('PropFirms'),
                'singular_name' => __('PropFirm')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
        )
    );
}
add_action('init', 'create_propfirm_post_type');

// Create Custom Field On Post Type Propfirm
function add_propfirm_custom_fields() {
    add_meta_box('propfirm_custom_fields', 'PropFirm Details', 'display_propfirm_custom_fields', 'propfirm', 'normal', 'high');
}

function display_propfirm_custom_fields($post) {
    $futures = get_post_meta($post->ID, 'futures', true);
    $forex = get_post_meta($post->ID, 'forex', true);
    $combo = get_post_meta($post->ID, 'combo', true);
    $fees = get_post_meta($post->ID, 'fees', true);
    $tradable_assets = get_post_meta($post->ID, 'tradable_assets', true);
    $restrictions = get_post_meta($post->ID, 'restrictions', true);

    echo '<label for="futures">Futures:</label>';
    echo '<input type="text" id="futures" name="futures" value="' . esc_attr($futures) . '"><br>';

    echo '<label for="forex">Forex:</label>';
    echo '<input type="text" id="forex" name="forex" value="' . esc_attr($forex) . '"><br>';

    echo '<label for="combo">Combo:</label>';
    echo '<input type="text" id="combo" name="combo" value="' . esc_attr($combo) . '"><br>';

    echo '<label for="fees">Fees:</label>';
    echo '<input type="text" id="fees" name="fees" value="' . esc_attr($fees) . '"><br>';

    echo '<label for="tradable_assets">Tradable Assets:</label>';
    echo '<input type="text" id="tradable_assets" name="tradable_assets" value="' . esc_attr($tradable_assets) . '"><br>';

    echo '<label for="restrictions">Restrictions:</label>';
    echo '<input type="text" id="restrictions" name="restrictions" value="' . esc_attr($restrictions) . '"><br>';
}

function save_propfirm_custom_fields($post_id) {
    if (array_key_exists('futures', $_POST)) {
        update_post_meta($post_id, 'futures', sanitize_text_field($_POST['futures']));
    }
    if (array_key_exists('forex', $_POST)) {
        update_post_meta($post_id, 'forex', sanitize_text_field($_POST['forex']));
    }
    if (array_key_exists('combo', $_POST)) {
        update_post_meta($post_id, 'combo', sanitize_text_field($_POST['combo']));
    }
    if (array_key_exists('fees', $_POST)) {
        update_post_meta($post_id, 'fees', sanitize_text_field($_POST['fees']));
    }
    if (array_key_exists('tradable_assets', $_POST)) {
        update_post_meta($post_id, 'tradable_assets', sanitize_text_field($_POST['tradable_assets']));
    }
    if (array_key_exists('restrictions', $_POST)) {
        update_post_meta($post_id, 'restrictions', sanitize_text_field($_POST['restrictions']));
    }
}

add_action('add_meta_boxes', 'add_propfirm_custom_fields');
add_action('save_post', 'save_propfirm_custom_fields');