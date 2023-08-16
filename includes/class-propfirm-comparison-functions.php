<?php
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
            'menu_icon' => 'dashicons-screenoptions',
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