<?php
if ( function_exists( 'wp_session_start' ) ) {
    wp_session_start();
}
session_start(); 

// Create Post Type Propfirm
function create_propfirm_post_type() {
    register_post_type('propfirm',
        array(
            'labels' => array(
                'name' => __('Prop Firms'),
                'singular_name' => __('PropFirm')
            ),
            'public' => true,
            'has_archive' => true,
            'supports' => array('title', 'editor', 'thumbnail'),
            'menu_icon' => 'dashicons-layout',
        )
    );
}
add_action('init', 'create_propfirm_post_type');

// Create Custom Field On Post Type Propfirm
add_filter( 'rwmb_meta_boxes', 'your_prefix_register_meta_boxes' );

function your_prefix_register_meta_boxes( $meta_boxes ) {
    $prefix = '';

    $meta_boxes[] = [
        'title'      => esc_html__( 'Propfirm Field', 'propfirm-comparison' ),
        'id'         => 'propfirm-field',
        'post_types' => ['propfirm'],
        'context'    => 'normal',
        'autosave'   => true,
        'fields'     => [
            [
                'type'        => 'text',
                'name'        => esc_html__( 'Propfirm Futures', 'propfirm-comparison' ),
                'id'          => $prefix . 'propfirm_futures',
                'desc'        => esc_html__( 'Propfirm Futures', 'propfirm-comparison' ),
                'placeholder' => esc_html__( 'Propfirm Futures', 'propfirm-comparison' ),
            ],
            [
                'type'        => 'text',
                'name'        => esc_html__( 'Propfirm Forex', 'propfirm-comparison' ),
                'id'          => $prefix . 'propfirm_forex',
                'desc'        => esc_html__( 'Propfirm Forex', 'propfirm-comparison' ),
                'placeholder' => esc_html__( 'Propfirm Forex', 'propfirm-comparison' ),
            ],
            [
                'type'        => 'text',
                'name'        => esc_html__( 'Propfirm Combo', 'propfirm-comparison' ),
                'id'          => $prefix . 'propfirm_combo',
                'desc'        => esc_html__( 'Propfirm Combo', 'propfirm-comparison' ),
                'placeholder' => esc_html__( 'Propfirm Combo', 'propfirm-comparison' ),
            ],
            [
                'type'        => 'text',
                'name'        => esc_html__( 'Propfirm Fees', 'propfirm-comparison' ),
                'id'          => $prefix . 'propfirm_fees',
                'desc'        => esc_html__( 'Propfirm Fees', 'propfirm-comparison' ),
                'placeholder' => esc_html__( 'Propfirm Fees', 'propfirm-comparison' ),
            ],
            [
                'type'        => 'text',
                'name'        => esc_html__( 'Propfirm Tradable Assets', 'propfirm-comparison' ),
                'id'          => $prefix . 'propfirm_tradable_assets',
                'desc'        => esc_html__( 'Propfirm Tradable Assets', 'propfirm-comparison' ),
                'placeholder' => esc_html__( 'Propfirm Tradable Assets', 'propfirm-comparison' ),
            ],
            [
                'type'        => 'text',
                'name'        => esc_html__( 'Restrictions', 'propfirm-comparison' ),
                'id'          => $prefix . 'restrictions',
                'desc'        => esc_html__( 'Restrictions', 'propfirm-comparison' ),
                'placeholder' => esc_html__( 'Restrictions', 'propfirm-comparison' ),
            ],
        ],
    ];

    return $meta_boxes;
}

// Add to compare session
function add_to_compare_session() {
    $propfirm_id = isset($_POST['propfirm_id']) ? intval($_POST['propfirm_id']) : 0;
    if ($propfirm_id > 0) {
        session_start();
        if (!isset($_SESSION['compare_list'])) {
            $_SESSION['compare_list'] = array();
        }
        if (!in_array($propfirm_id, $_SESSION['compare_list'])) {
            $_SESSION['compare_list'][] = $propfirm_id;
        }
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}
add_action('wp_ajax_add_to_compare', 'add_to_compare_session');
add_action('wp_ajax_nopriv_add_to_compare', 'add_to_compare_session');

// Remove from compare session
function remove_from_compare_session() {
    $propfirm_id = isset($_POST['propfirm_id']) ? intval($_POST['propfirm_id']) : 0;
    if ($propfirm_id > 0) {
        session_start();
        if (isset($_SESSION['compare_list'])) {
            $index = array_search($propfirm_id, $_SESSION['compare_list']);
            if ($index !== false) {
                unset($_SESSION['compare_list'][$index]);
                $_SESSION['compare_list'] = array_values($_SESSION['compare_list']);
            }
        }
        wp_send_json_success();
        
    } else {
        wp_send_json_error();
    }
}
add_action('wp_ajax_remove_from_compare', 'remove_from_compare_session');
add_action('wp_ajax_nopriv_remove_from_compare', 'remove_from_compare_session');

function clear_session() {
    session_start();
    $_SESSION = array(); // Clear session data
    wp_send_json_success();
}
add_action('wp_ajax_clear_session', 'clear_session');
add_action('wp_ajax_nopriv_clear_session', 'clear_session');

function get_compare_list() {
    session_start();
    $compare_list = isset($_SESSION['compare_list']) ? $_SESSION['compare_list'] : array();
    wp_send_json_success(array('compare_list' => $compare_list));
}
add_action('wp_ajax_get_compare_list', 'get_compare_list');
add_action('wp_ajax_nopriv_get_compare_list', 'get_compare_list');

// Update compare session
function update_compare_session() {
    if (isset($_POST['compare_list']) && is_array($_POST['compare_list'])) {
        session_start();
        $_SESSION['compare_list'] = $_POST['compare_list'];
        wp_send_json_success();
    } else {
        wp_send_json_error();
    }
}
add_action('wp_ajax_update_compare_session', 'update_compare_session');
add_action('wp_ajax_nopriv_update_compare_session', 'update_compare_session');

function get_propfirm_data() {
    $propfirm_id = isset($_POST['propfirm_id']) ? intval($_POST['propfirm_id']) : 0;

    if ($propfirm_id > 0) {
        $post = get_post($propfirm_id);
        if ($post && $post->post_type === 'propfirm') {
            $propfirm_data = array(
                'post_title' => $post->post_title,
                'post_thumbnail_url' => get_the_post_thumbnail_url($propfirm_id),
                'post_id' => $propfirm_id,
            );

            wp_send_json($propfirm_data);
        }
    }

    wp_send_json(array()); // Return an empty JSON object if no data found
}
// Add AJAX action to fetch propfirm data
add_action('wp_ajax_get_propfirm_data', 'get_propfirm_data');
add_action('wp_ajax_nopriv_get_propfirm_data', 'get_propfirm_data'); // For non-logged in users