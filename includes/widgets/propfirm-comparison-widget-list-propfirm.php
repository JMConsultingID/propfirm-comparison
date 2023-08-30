<?php
class Elementor_PropfirmComparison_Widget_listPropfirm extends \Elementor\Widget_Base {

	public function get_name() {
		return 'propfirm_list';
	}

	public function get_title() {
		return esc_html__( 'Propfirm List Post', 'propfirm-comparison' );
	}

	public function get_icon() {
		return 'eicon-posts-justified';
	}

	public function get_categories() {
		return [ 'propfirm-comparison-category' ];
	}

	public function get_keywords() {
		return [ 'propfirm', 'list' ];
	}

	public function get_style_depends() {
		return [ 'propfirm-comparison-widget-propfirm-bootstrap-style' ];
	}

	protected function register_controls() {

		// Content Tab Start

		$this->start_controls_section(
			'propfirm_list_post',
			[
				'label' => esc_html__( 'Propfirm List Post', 'propfirm-comparison' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'title',
			[
				'label' => esc_html__( 'Title', 'propfirm-comparison' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'List Propfirm', 'propfirm-comparison' ),
			]
		);

		$this->add_control(
			'subtitle',
			[
				'label' => esc_html__( 'Sub Title', 'propfirm-comparison' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Select and Compare List Propfirm' ),
			]
		);

	    $this->add_control(
	        'propfirm_post_type',
	        [
	            'label' => __( 'Post Type', 'propfirm-comparison' ),
	            'type' => \Elementor\Controls_Manager::SELECT,
	            'options' => [
					'post' => esc_html__( 'Post', 'textdomain' ),
					'page'  => esc_html__( 'Page', 'textdomain' ),
					'propfirm' => esc_html__( 'Propfirm', 'textdomain' ),
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
		$propfirm_post_type = $settings['propfirm_post_type'];

		?>
		<!-- Propfirm List Post Start-->
		<section class="container">
			<h1 class="text-center"><?php echo $propfirm_heading_title; ?></h1>
			<h4 class="text-center"><?php echo $propfirm_heading_subtitle; ?></h4>
			<div class="row">
			<?php
				global $post;
				$args = array(
				    'post_type' => $propfirm_post_type,  // Nama custom post type yang Anda buat
				    'posts_per_page' => -1,     // Tampilkan semua post propfirm
				    'orderby'=> 'name',
	    			'order'=> 'ASC',
				);

				$propfirm_query = new WP_Query($args);

				if ($propfirm_query->have_posts()) :
				    while ($propfirm_query->have_posts()) : $propfirm_query->the_post();
				    ?>
				    	<?php 
				    		$values = rwmb_meta( 'propfirm_futures' );
				    		$post_slug = $post->post_name;
				    	?>
				    	
				    	<div class="col-lg-4 col-md-6 col-sm-12">
					    <div class="card">
					      <?php 
					      $featured_image_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
					      $featured_thumb_image_url = get_the_post_thumbnail_url(get_the_ID(), 'thumbnail');
					      echo '<img src="' . esc_url($featured_image_url) . '" alt="' . get_the_title() . '" class="img-fluid">';
					      ?>				      
						  <div class="card-body">
						    <h5 class="card-title"><?php the_title(); ?></h5>
						    <p class="card-text"><?php echo $values; ?></p>
						    <p><?php echo do_shortcode( '[alike_link]' ); ?></p>
						    <p>						    	
						    <?php echo '<button class="btn btn-primary compare-button" data-propfirm-id="' . get_the_ID() . '" data-propfirm-title="' . get_the_title() . '" data-image-url="' . $featured_thumb_image_url . '" data-propfirm-slug="'.$post_slug.'" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Compare</button>'; ?>
							</p>
						  </div>
						</div>
						</div>
				    <?php
			        
				    endwhile;
				    wp_reset_postdata();  // Mengatur ulang data post
				else :
				    echo "No PropFirm data found.";
				endif;
			?>
		</div>		
		<?php
	}
}
			