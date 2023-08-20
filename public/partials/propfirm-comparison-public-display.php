<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://fundedtrading.com
 * @since      1.0.0
 *
 * @package    Propfirm_Comparison
 * @subpackage Propfirm_Comparison/public/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php 
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
        <div class="d-grid gap-2">
        <?php
            $options = get_option('propfirm_comparison_settings');
            $page_url = isset($options['propfirm_comparison_url']) ? $options['propfirm_comparison_url'] : '';
        ?>
        <button id="generate-compare" class="btn btn-success" data-propfirm-url="<?php echo $page_url; ?>">Generate Compare</button>
        <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas" aria-label="Close">Close</button>    
        </div>
        </div>
      </div>
    </div>
    <button id="fixed-button" class="fixed-button btn btn-secondary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><i class="bi bi-files"></i> 0</button>
    <?php
}

add_action('wp_footer', 'add_offcanvas_comparasion_to_footer');
?>