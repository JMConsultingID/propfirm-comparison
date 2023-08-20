<?php
if ( function_exists( 'wp_session_start' ) ) {
    wp_session_start();
}
session_start();

// Add plugin settings page
function propfirm_comparison_settings_page() {
    add_submenu_page(
        'edit.php?post_type=propfirm',
        'Propfirm Settings',
        'Propfirm Settings',
        'manage_options',
        'propfirm_comparison',
        'propfirm_comparison_settings_page_content'
    );
}
add_action('admin_menu', 'propfirm_comparison_settings_page');

// Create settings page content
function propfirm_comparison_settings_page_content() {
    ?>
    <div class="wrap">
        <h2>Funded Trading Propfirm Comparison Settings</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('propfirm_comparison_settings');
            do_settings_sections('propfirm_comparison');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}

// Register settings and fields
function propfirm_comparison_register_settings() {
    register_setting('propfirm_comparison_settings', 'propfirm_comparison_settings');

    add_settings_section(
        'propfirm_comparison_general',
        'General Settings',
        'propfirm_comparison_general_section_callback',
        'propfirm_comparison'
    );

    add_settings_field(
        'propfirm_comparison_post_type',
        'Propfirm Comparison Post Type',
        'propfirm_comparison_post_type_field_callback',
        'propfirm_comparison',
        'propfirm_comparison_general'
    );    

    add_settings_field(
        'propfirm_comparison_acf_parameter',
        'Propfirm Comparison ACF Parameter Group',
        'propfirm_comparison_acf_parameter_field_callback',
        'propfirm_comparison',
        'propfirm_comparison_general'
    );

    add_settings_field(
        'propfirm_comparison_url',
        'Propfirm Comparison URL',
        'propfirm_comparison_url_field_callback',
        'propfirm_comparison',
        'propfirm_comparison_general'
    );
}
add_action('admin_init', 'propfirm_comparison_register_settings');

// Section callback
function propfirm_comparison_general_section_callback() {
    echo '<h3>Plugin Instructions : </h3>';
    echo '    
    <ol>
        <li>Install the Advanced Custom Fields Plugin.</li>
        <li>Create a Field Group containing the parameters you want to compare, then select the post type. For example, "post," "page," or "propfirm."</li>
        <li>If you want to create a new post type, create a new post type using the Advanced Custom Fields Plugin.</li>
        <li>To create the Prop Firm List page, go to "New Page" and select the Elementor editor. Choose the "Propfirm List Post" widget.</li>
        <li>To create the comparison page, go to "New Page" and select the Elementor editor. Choose the "Propfirm Compare Table" widget.</li>
        <li>For customizing the style, expand using CSS. This plugin uses Bootstrap 5.</li>
        <li>In the General Settings of Propfirm Comparison, select the post type, then choose the ACF Parameter Group, and select the Compare page you created earlier using Elementor.</li>        
        <li>If you have any questions, please send an email to <a href="mailto:ardi@jm-consulting.id">ardi@jm-consulting.id</a>.</li>
        <li>Enjoy using this plugin.</li>
    </ol>
    ';
}

// Field callback
function propfirm_comparison_post_type_field_callback() {
    $options = get_option('propfirm_comparison_settings');
    $selected_post_type = isset($options['propfirm_comparison_post_type']) ? sanitize_text_field($options['propfirm_comparison_post_type']) : 'propfirm';


    // Get all Post Type
    $post_types = get_post_types(array('public' => true), 'objects');

    echo '<select name="propfirm_comparison_settings[propfirm_comparison_post_type]">';

    foreach ($post_types as $post_type) {
        $post_type_name = $post_type->name;
        $selected = $post_type_name === $selected_post_type ? 'selected' : '';
        echo "<option value='$post_type_name' $selected>{$post_type->labels->singular_name}</option>";
    }

    echo '</select>';   
}

// Field callback
function propfirm_comparison_acf_parameter_field_callback() {
    $options = get_option('propfirm_comparison_settings');
    $selected_group_id = isset($options['propfirm_comparison_acf_parameter']) ? intval($options['propfirm_comparison_acf_parameter']) : 0;

    // Get all ACF groups
    if (class_exists('ACF')) {
    $acf_groups = acf_get_field_groups();

    echo '<select name="propfirm_comparison_settings[propfirm_comparison_acf_parameter]">';
    echo '<option value="0">Select a Froup ACF Parameter</option>';

    foreach ($acf_groups as $group) {
        $group_id = $group['ID'];
        $selected = $group_id === $selected_group_id ? 'selected' : '';
        echo "<option value='$group_id' $selected>$group_id - {$group['title']}</option>";
    }
    echo '</select>';
    }
    else{
        echo "Funded Trading Propfirm Comparison requires 'Advanced Custom Fields' to be installed and active.";
    }
}

// Field callback
function propfirm_comparison_url_field_callback() {
    $options = get_option('propfirm_comparison_settings');
    $selected_slug = isset($options['propfirm_comparison_url']) ? sanitize_text_field($options['propfirm_comparison_url']) : '';


    // Get all pages
    $pages = get_pages();

    echo '<select name="propfirm_comparison_settings[propfirm_comparison_url]">';
    echo '<option value="">Select a Page</option>';

    foreach ($pages as $page) {
        $page_slug = $page->post_name;
        $selected = $page_slug === $selected_slug ? 'selected' : '';
        echo "<option value='$page_slug' $selected>{$page->post_title}</option>";
    }

    echo '</select>';   
}



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