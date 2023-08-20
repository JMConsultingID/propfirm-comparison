<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://fundedtrading.com
 * @since      1.0.0
 *
 * @package    Propfirm_Comparison
 * @subpackage Propfirm_Comparison/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Propfirm_Comparison
 * @subpackage Propfirm_Comparison/includes
 * @author     Ardika JM Consulting <ardi@jm-consulting.id>
 */
function add_propfirm_comparison_categories( $elements_manager ) {

    $elements_manager->add_category(
        'propfirm-comparison-category',
        [
            'title' => esc_html__( 'Funded Trading Widget', 'propfirm-comparison' ),
            'icon' => 'fa fa-plug',
        ]
    );
}
add_action( 'elementor/elements/categories_registered', 'add_propfirm_comparison_categories' );

function register_propfirm_comparison_widget( $widgets_manager ) {

    require_once( __DIR__ . '/widgets/propfirm-comparison-widget-list-propfirm.php' );
    require_once( __DIR__ . '/widgets/propfirm-comparison-widget-compare-propfirm.php' );    

    $widgets_manager->register( new \Elementor_PropfirmComparison_Widget_listPropfirm() );
    $widgets_manager->register( new \Elementor_PropfirmComparison_Widget_comparePropfirm() );    
}
add_action( 'elementor/widgets/register', 'register_propfirm_comparison_widget' );

function propfirm_comparison_frontend_stylesheets() {
    wp_register_style( 'propfirm-comparison-widget-bootstrap-style', plugins_url( '../public/css/bootstrap.min.css', __FILE__ ) );
    wp_enqueue_style( 'propfirm-comparison-widget-bootstrap-style' );
}
add_action( 'elementor/frontend/before_enqueue_styles', 'propfirm_comparison_frontend_stylesheets' );

function propfirm_comparison_after_frontend_stylesheets() {
   wp_register_style( 'propfirm-comparison-widget-style', plugins_url( 'widgets/assets/css/propfirm-comparison-widget-style.css', __FILE__ ), false );

    wp_enqueue_style( 'propfirm-comparison-widget-style' );
}
add_action( 'elementor/frontend/after_enqueue_styles', 'propfirm_comparison_after_frontend_stylesheets' );

//function propfirm_comparison_frontend_scripts() {
    //wp_register_script( 'propfirm-comparison-widget-bootstrap-script', plugins_url( '../public/js/bootstrap.bundle.min.js', __FILE__ ) );
    //wp_enqueue_script( 'propfirm-comparison-widget-bootstrap-script' );
//}
//add_action( 'elementor/frontend/before_register_scripts', 'propfirm_comparison_frontend_scripts' );

