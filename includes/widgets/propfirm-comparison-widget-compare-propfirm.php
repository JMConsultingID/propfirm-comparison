<?php
class Elementor_PropfirmComparison_Widget_comparePropfirm extends \Elementor\Widget_Base {

	public function get_name() {
		return 'propfirm_compare';
	}

	public function get_title() {
		return esc_html__( 'Propfirm Compare Table', 'propfirm-comparison' );
	}

	public function get_icon() {
		return 'eicon-price-table';
	}

	public function get_categories() {
		return [ 'propfirm-comparison-category' ];
	}

	public function get_keywords() {
		return [ 'propfirm', 'compare', 'table' ];
	}

	public function get_style_depends() {
		return [ 'propfirm-comparison-widget-compare-bootstrap-style' ];
	}

	protected function register_controls() {

		// Content Tab Start

		$this->start_controls_section(
			'propfirm_compare_table',
			[
				'label' => esc_html__( 'Propfirm Compare Table', 'propfirm-comparison' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'propfirm-comparison' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Propfirm Comparasion Page', 'propfirm-comparison' ),
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label' => esc_html__( 'Sub Title', 'propfirm-comparison' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Compare List Propfirm' ),
			]
		);

	    $this->add_control(
	        'propfirm_compare_style_type',
	        [
	            'label' => __( 'Style Type', 'propfirm-comparison' ),
	            'type' => \Elementor\Controls_Manager::SELECT,
	            'options' => [
					'style-1' => esc_html__( 'Style 1', 'textdomain' ),
					'style-2'  => esc_html__( 'Style 2', 'textdomain' ),
					'style-3' => esc_html__( 'Style 3', 'textdomain' ),
				],
	            'default' => 'propfirm',
	        ]
	    );

		$this->end_controls_section();

		// Content Tab End


	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$propfirm_heading_title = $settings['title'];
		$propfirm_heading_subtitle = $settings['subtitle'];
		$propfirm_compare_style = $settings['propfirm_compare_style_type'];

		?>
		<!-- Propfirm Compare Table Start-->
		<div class="col-12">			
			<h1><?php echo $propfirm_heading_title; ?></h1>
			<?php
			$propfirm_ids = isset($_GET['propfirm_ids']) ? explode(',', $_GET['propfirm_ids']) : array();

            $options = get_option('propfirm_comparison_settings');
            $acf_group_id = isset($options['propfirm_comparison_acf_parameter']) ? intval($options['propfirm_comparison_acf_parameter']) : 0;

			// Define the field group ID
			$field_group_id = $acf_group_id; // Replace with your ACF field group ID

			// Get all fields within the "Field Group"
			$field_objects = acf_get_fields($field_group_id);

			// Loop through $propfirm_ids to get data and build the comparison table
    		// Fetch necessary data for each propfirm (replace this with your actual code)
			$propfirms = array();

			if (empty($propfirm_ids) && !\Elementor\Plugin::$instance->editor->is_edit_mode()) {
    			echo "<p>Warning : No PropFirm data available for comparison. Please select the Propfirm to compare</p>";
    		}

			if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
    			echo "<p>Warning : No PropFirm data available for comparison. this is for example</p>";
    			$args = array(
				    'post_type'      => 'propfirm', // Replace with your custom post type slug
				    'posts_per_page' => 2, // Number of random posts you want to retrieve
				    'orderby'        => 'rand', // Order by random
				    'fields'         => 'ids', // Only retrieve post IDs
				);
				$random_post_ids = get_posts( $args );
				$propfirm_ids = $random_post_ids;
    		}

    		if (!empty($propfirm_ids)) {
			foreach ($propfirm_ids as $propfirm_id) {
			    $propfirms[$propfirm_id] = array(
			        'title' => get_the_title($propfirm_id),
			        'propfirm_futures' => rwmb_meta( 'propfirm_futures', '', $propfirm_id ),
	    			'propfirm_forex' => rwmb_meta( 'propfirm_forex', '', $propfirm_id ),
	    			'propfirm_combo' => rwmb_meta( 'propfirm_combo', '', $propfirm_id ),
	    			'propfirm_fees' => rwmb_meta( 'propfirm_fees', '', $propfirm_id ),
	    			'propfirm_tradable_assets' => rwmb_meta( 'propfirm_tradable_assets', '', $propfirm_id ),
	    			'restrictions' => rwmb_meta( 'restrictions', '', $propfirm_id ),
			    );
			}

    		?>
    		<div class="table-responsive">
			<table class="table table-striped text-successtable-border border-light table-hover">
			    <thead class="border-light">
			        <tr>
			            <th class="text-uppercase" scope="col">Features</th>
			            <?php foreach ($propfirms as $propfirm_id => $propfirm) : ?>
			                <th class="text-uppercase" scope="row text-uppercase">
			                	<?php echo !empty($propfirm['title']) ? $propfirm['title'] : 'Example Title' ; ?></th>
			            <?php endforeach; ?>
			        </tr>
			    </thead>
			    <tbody>
			        <?php foreach ($field_objects as $field) : ?>
			            <tr>
			                <td><?php echo $field['label']; ?></td>
			                <?php foreach ($propfirm_ids as $propfirm_id) : ?>
			                    <?php
			                    $field_value = get_field($field['name'], $propfirm_id); // Get the field value
			                    ?>
			                    <td><?php echo !empty($field_value) ? $field_value : '-'; ?></td>
			                <?php endforeach; ?>
			            </tr>
			        <?php endforeach; ?>
			    </tbody>
			</table>
			</div>
		<?php 
		}
		?>
		</div>
		<!-- Propfirm Compare Table End-->		
		<?php
	}
}
			