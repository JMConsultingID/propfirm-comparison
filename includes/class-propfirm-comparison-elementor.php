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

    $widgets_manager->register( new \Elementor_PropfirmComparison_Widget_listPropfirm() );
}
add_action( 'elementor/widgets/register', 'register_propfirm_comparison_widget' );

function add_offcanvas_comparasion_to_footer() {
    ?>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
      <div class="offcanvas-header pt-3">
        <h3 id="offcanvasRightLabel">Compare List</h3>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <div class="compare-sidebar">
        
        <div id="compare-list" class="row"></div>
        <ul></ul>
        <div class="d-grid gap-2">
        <button id="generate-compare" class="btn btn-success">Generate Compare</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas" aria-label="Close">Close</button>    
        </div>
        </div>
      </div>
    </div>
    <?php
}

add_action('wp_footer', 'add_offcanvas_comparasion_to_footer');