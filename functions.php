<?php
/**
 * Theme functions and definitions
 *
 * @package HelloElementorChild
 */

function hello_elementor_child_enqueue_scripts() {
	wp_enqueue_style( 'hello-elementor-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		[ 'hello-elementor-theme-style' ],
		filemtime( get_stylesheet_directory() . '/style.css' )
	);
	wp_enqueue_script( 'swiper', 'https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js' );
}
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_scripts', 20 );

// Defining theme path
define( 'THEME_PATH', get_stylesheet_directory_uri() );

// Defining styles path
define( 'THEME_CSS_PATH', get_stylesheet_directory_uri() . '/css' );

// Defining javascript path
define( 'THEME_JS_PATH', get_stylesheet_directory_uri() . '/js' );

// Defining images path
define( 'THEME_IMG_PATH', get_stylesheet_directory_uri() . '/images' );

// Add custom Rest API endpoints for AJAX filtering

include 'rest-api.php';

// Register custom post types
function register_leilac_custom_post_types() {
	// Resources
	
    register_leilac_custom_post_type( 'whitepaper', 'Whitepaper', 'dashicons-media-document', ['category'], true );
    register_leilac_custom_post_type( 'insight', 'Insight', 'dashicons-lightbulb', ['category'], true );
    register_leilac_custom_post_type( 'report', 'Report', 'dashicons-welcome-write-blog', ['category'], true );
    register_leilac_custom_post_type( 'conference', 'Conference', 'dashicons-format-status', ['category'], true );
    register_leilac_custom_post_type( 'podcast', 'Podcast', 'dashicons-microphone', ['category'], true );
    register_leilac_custom_post_type( 'video', 'Video', 'dashicons-format-video', ['category'], true );
	
	// Jobs
	
	register_leilac_custom_post_type( 'job', 'Job', 'dashicons-id-alt' );	
	
	// Team
	
	$labels = array(
		'name'                  => _x( 'Team', 'Post Type General Name', 'leilac' ),
		'singular_name'         => _x( 'Team Member', 'Post Type Singular Name', 'leilac' ),
		'menu_name'             => __( 'Team', 'leilac' ),
		'name_admin_bar'        => __( 'Team Member', 'leilac' ),
		'archives'              => __( 'Team Archives', 'leilac' ),
		'attributes'            => __( 'Team Member Attributes', 'leilac' ),
		'parent_item_colon'     => __( 'Parent Team Member:', 'leilac' ),
		'all_items'             => __( 'All Team Members', 'leilac' ),
		'add_new_item'          => __( 'Add New Team Member', 'leilac' ),
		'add_new'               => __( 'Add New Team Member', 'leilac' ),
		'new_item'              => __( 'New Team Member', 'leilac' ),
		'edit_item'             => __( 'Edit Team Member', 'leilac' ),
		'update_item'           => __( 'Update Team Member', 'leilac' ),
		'view_item'             => __( 'View Team Member', 'leilac' ),
		'view_items'            => __( 'View Team', 'leilac' ),
		'search_items'          => __( 'Search Team Member', 'leilac' ),
		'not_found'             => __( 'Not found', 'leilac' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'leilac' ),
		'featured_image'        => __( 'Featured Image', 'leilac' ),
		'set_featured_image'    => __( 'Set featured image', 'leilac' ),
		'remove_featured_image' => __( 'Remove featured image', 'leilac' ),
		'use_featured_image'    => __( 'Use as featured image', 'leilac' ),
		'insert_into_item'      => __( 'Insert into Team Member', 'leilac' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Team Member', 'leilac' ),
		'items_list'            => __( 'Team Members list', 'leilac' ),
		'items_list_navigation' => __( 'Team Members list navigation', 'leilac' ),
		'filter_items_list'     => __( 'Filter Team Members list', 'leilac' ),
	);

	$args = array(
		'label'                 => __( 'Team Member', 'leilac' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'thumbnail'),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 4,
		'menu_icon'             => 'dashicons-groups',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'capability_type'       => 'page',
	);

	register_post_type( 'team-member', $args );
}
add_action( 'init', 'register_leilac_custom_post_types', 0 );

function register_leilac_custom_post_type( $slug, $label, $icon, $taxonomies = [], $editor = false ) {
    $labels = array(
        'name'                  => _x( $label . 's', 'Post Type General Name', 'leilac' ),
        'singular_name'         => _x( $label, 'Post Type Singular Name', 'leilac' ),
        'menu_name'             => __( $label . 's', 'leilac' ),
        'name_admin_bar'        => __( $label, 'leilac' ),
        'archives'              => __( $label . ' Archives', 'leilac' ),
        'attributes'            => __( $label . ' Attributes', 'leilac' ),
        'parent_item_colon'     => __( 'Parent ' . $label . ':', 'leilac' ),
        'all_items'             => __( 'All ' . $label . 's', 'leilac' ),
        'add_new_item'          => __( 'Add New ' . $label, 'leilac' ),
        'add_new'               => __( 'Add New', 'leilac' ),
        'new_item'              => __( 'New ' . $label, 'leilac' ),
        'edit_item'             => __( 'Edit ' . $label, 'leilac' ),
        'update_item'           => __( 'Update ' . $label, 'leilac' ),
        'view_item'             => __( 'View ' . $label, 'leilac' ),
        'view_items'            => __( 'View ' . $label . 's', 'leilac' ),
        'search_items'          => __( 'Search ' . $label, 'leilac' ),
        'not_found'             => __( 'Not found', 'leilac' ),
        'not_found_in_trash'    => __( 'Not found in Trash', 'leilac' ),
        'featured_image'        => __( 'Featured Image', 'leilac' ),
        'set_featured_image'    => __( 'Set featured image', 'leilac' ),
        'remove_featured_image' => __( 'Remove featured image', 'leilac' ),
        'use_featured_image'    => __( 'Use as featured image', 'leilac' ),
        'insert_into_item'      => __( 'Insert into ' . $label, 'leilac' ),
        'uploaded_to_this_item' => __( 'Uploaded to this ' . $label, 'leilac' ),
        'items_list'            => __( $label . 's list', 'leilac' ),
        'items_list_navigation' => __( $label . 's list navigation', 'leilac' ),
        'filter_items_list'     => __( 'Filter ' . $label . 's list', 'leilac' ),
    );
    
    $args = array(
        'label'                 => __( $label, 'leilac' ),
        'labels'                => $labels,
        'supports'              => array( 'title', 'excerpt', 'thumbnail', 'custom-fields', ($editor ? 'editor' : '') ),
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 4,
        'menu_icon'             => $icon,
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
		'show_in_rest'       	=> $editor,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'page',
    );

    if ( $taxonomies != [] ) {
        $args['taxonomies'] = $taxonomies;
    }

    register_post_type( $slug, $args );
}

add_filter('acf/settings/remove_wp_meta_box', '__return_false');

function register_leilac_custom_taxonomies() {
	register_leilac_custom_taxonomy( 'area', 'Area', 'Areas', [ 'team-member' ]);
}

add_action( 'init', 'register_leilac_custom_taxonomies', 0 );

function register_leilac_custom_taxonomy( $slug, $singular, $plural, $post_types) {
	$labels = array(
		'name'                       => _x( $plural, 'Taxonomy General Name', 'leilac' ),
		'singular_name'              => _x( $singular, 'Taxonomy Singular Name', 'leilac' ),
		'menu_name'                  => __( $plural, 'leilac' ),
		'all_items'                  => __( 'All ' . $plural, 'leilac' ),
		'parent_item'                => __( 'Parent ' . $singular, 'leilac' ),
		'parent_item_colon'          => __( 'Parent ' . $singular, 'leilac' ),
		'new_item_name'              => __( 'New ' . $singular, 'leilac' ),
		'add_new_item'               => __( 'Add New ' . $singular, 'leilac' ),
		'edit_item'                  => __( 'Edit ' . $singular, 'leilac' ),
		'update_item'                => __( 'Update ' . $singular, 'leilac' ),
		'view_item'                  => __( 'View ' . $singular, 'leilac' ),
		'separate_items_with_commas' => __( 'Separate ' . $plural . ' with commas', 'leilac' ),
		'add_or_remove_items'        => __( 'Add or remove ' . $plural, 'leilac' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'leilac' ),
		'popular_items'              => __( 'Popular ' . $plural, 'leilac' ),
		'search_items'               => __( 'Search ' . $plural, 'leilac' ),
		'not_found'                  => __( 'Not Found', 'leilac' ),
		'no_terms'                   => __( 'No ' . $plural, 'leilac' ),
		'items_list'                 => __( $plural . ' list', 'leilac' ),
		'items_list_navigation'      => __( $plural . ' list navigation', 'leilac' ),
	);

	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
	);

	register_taxonomy( $slug, $post_types, $args );
}

// Display Content Cards

function content_cards($atts) {
	ob_start(); 
	
	if (isset($atts['related'])) {
		$atts['post_types'] = get_post_type();
		echo '<h2 class="content-cards-title">Related '. (($atts['post_types'] == 'post') ? 'New' : ucfirst( $atts['post_types'] )) .'s</h2>';
	}
	?>
	<div id="<?= $atts['id'] ?>" class="swiper content-cards">
		<div class="content-wrapper swiper-wrapper">
		<?php 
			$args = array(
				'post_type' 		=> explode( ',', $atts['post_types'] ),
				'posts_per_page'	=> $atts['per_page']
			);
	
			if (isset($atts['post_title'])) {
				$args['post_title'] = explode( ' and ', $atts['post_title'] );
			}
	
			if (isset($atts['offset'])) {
				$args['offset'] = $atts['offset'];
			}
			
			if (isset($atts['related'])) {
				$categories = '';
				foreach (get_terms() as $category) {
					$categories .= $category->ID.',';
				}
				$args['cat'] = $categories;
				$atts['cat'] = $categories;
			}
	
			$query = new WP_Query( $args );

			while ( $query->have_posts() ) : $query->the_post() ?>
				<div class="content-card swiper-slide">
					<div>
						<a href="<?php the_permalink() ?>" class="image">
							<?php the_post_thumbnail( [500, 0] ) ?>
						</a>
						<?php if ( get_post_type() == 'podcast' || get_post_type() == 'video' ) : ?>
							<img class="icon" src="/wp-content/uploads/2022/08/<?= get_post_type() ?>-icon.svg" width="40px" height="40px">
						<?php endif ?>
					</div>
					<div class="card-content">
						<a href="<?php the_permalink() ?>"><h5><?php the_title() ?></h5></a>
						<?php $excerpt = str_word_count(get_the_excerpt());
						$trimmed_excerpt = wp_trim_words( get_the_excerpt(), 7, '...' ); ?>
						<span class="date"><?= $trimmed_excerpt ?></span>
						<span class="date"><?= get_the_date() ?></span>
						<a class="content-link" href="<?php the_permalink() ?>">Click here to learn more</a>
					</div>
				</div>
			<?php endwhile;	
			wp_reset_postdata(); 
		?>
		</div>
		<div class="swiper-pagination"></div>
	</div>
	<?php if (!isset($atts['display_load_more_button']) && $query->max_num_pages != 1) : ?>
		<button class="load-more"
				data-wrapper="<?= $atts['id'] ?>"
				data-post-types="<?= $atts['post_types'] ?>"
				data-cat="<?= (isset($atts['cat']) ? $atts['cat'] : '') ?>"
				data-page="1" data-per-page="<?= $atts['per_page'] ?>"
				data-max-pages="<?= $query->max_num_pages ?>">
			More <?= str_replace(',', 's and ', preg_filter('/$/', 's', str_replace('post', 'new', $atts['post_types']))) ?>
		</button>
	<?php endif ?>
	<script>
		if (jQuery(window).width() < 1025) {
			var <?= $atts['id'] ?>Swiper = new Swiper("#<?= $atts['id'] ?>", {
				slidesPerView: 1,
				spaceBetween: 30,
				pagination: {
					el: ".swiper-pagination",
					clickable: true,
				},
				breakpoints: {
					767: {
						slidesPerView: 2,
						spaceBetween: 30
					}
				}
			});
		}
	</script>	
	<?php $output = ob_get_contents();
	
	ob_end_clean();
	
	return $output;
}

add_shortcode( 'content_cards', 'content_cards' );

// Display Team Cards

function team_cards() {
	$areas = get_terms([ 'taxonomy' => 'area', 'orderby' => 'id' ]);
	ob_start(); ?>

	<div class="team-section">
	<?php foreach ( $areas as $area ) : ?>
		<div class="area <?= $area->slug ?> swiper" style="order: <?= get_field( 'display_order', $area ) ?>;">
			<h2><?= $area->name ?></h2>
			<div class="team-members-wrapper swiper-wrapper">
			<?php 
				$args = array(
					'post_type' => 'team-member',
					'tax_query' => array(
						array(
							'taxonomy' => 'area',
							'field'    => 'slug',
							'terms'    => $area->slug,
						),
					),
				);
				$query = new WP_Query( $args );

				while ( $query->have_posts() ) : $query->the_post() 
			?>
					<div class="team-member swiper-slide" style="order: <?= get_field( 'display_order') ?>;">
						<a class="photo open-profile" data-profile="<?= get_the_ID() ?>" href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6IjMwNjciLCJ0b2dnbGUiOmZhbHNlfQ%3D%3D"><?php the_post_thumbnail( 'full' ) ?></a>
						<h4><?= get_field( 'name' ) ?></h4>
						<div class="meta">
							<span class="role"><?= get_field( 'role' ) ?></span>
						</div>
						<div class="buttons">
							<a class="open-profile" data-profile="<?= get_the_ID() ?>" href="#elementor-action%3Aaction%3Dpopup%3Aopen%26settings%3DeyJpZCI6IjMwNjciLCJ0b2dnbGUiOmZhbHNlfQ%3D%3D">
								<button>Read bio</button>
							</a>
						</div>
					</div>
			<?php 
				endwhile;	
				wp_reset_postdata(); 
			?>
			</div>
			<div class="swiper-pagination"></div>
		</div>
		<script>
			if (jQuery(window).width() <= 1024) {
				var swiper = new Swiper(".<?= $area->slug ?>", {
					slidesPerView: "auto",
					spaceBetween: 30,
					watchSlidesProgress: true,
					pagination: {
						el: ".swiper-pagination",
						clickable: true,
					},
					breakpoints: {
						1024: {
							slidesPerView: 3
						}
					}
				});
			}
		</script>
	<?php endforeach; ?>
	</div>
	
	<?php $output = ob_get_contents();
	
	ob_end_clean();
	
	return $output;
}

add_shortcode( 'team_cards', 'team_cards' );

// Display job cards

function job_cards() {
	ob_start(); ?>
	<div class="filters flex-row">
		<input id="filter-jobs" type="text" placeholder="Filter by keyword">
		<input id="filter-jobs-location" type="text" placeholder="Filter by location">
	</div>
	<div id="jobs-wrapper" class="jobs-wrapper">
	<?php 
		$query = new WP_Query( [ 'post_type' => 'job' ] );

		while ( $query->have_posts() ) : $query->the_post() 
	?>
			<div class="job-card flex-column">
				<a href="<?php the_permalink() ?>" class="photo"><h5><?php the_title() ?></h5></a>
				<div class="details flex-column">
					<span class="type before-icon"><?= get_field( 'type' ) ?></span>
					<span class="location before-icon"><?= get_field( 'location' ) ?></span>
					<span class="date before-icon">Close: <?= str_replace( '00:00', '24:00', get_field( 'date' )) ?></span>
				</div>
				<a class="linkedin" href="<?= get_field( 'linkedin' ) ?>">
					<button class="before-icon">View job</button>
				</a>
			</div>
	<?php 
		endwhile;	
		wp_reset_postdata(); 
	?>
	</div>
	
	<?php $output = ob_get_contents();
	
	ob_end_clean();
	
	return $output;
}

add_shortcode( 'job_cards', 'job_cards' );

/* Contact Map Shortcode */

function contact_us_map(){

    $return_string = '<div class="map-container">';
    $return_string .= '<img src="'. THEME_IMG_PATH .'/map-contact.svg" />';
    $return_string .= '<div class="point united-states tippy">USA</div>';
    $return_string .= '<div class="point europe tippy">Europe</div>';
    $return_string .= '<div class="point asia tippy">Asia</div>';
    $return_string .= '<div class="point australia tippy">Australia</div>';
    $return_string .= '<div id="contact-united-states" style="display:none;" class="point united-states tippy"><div class="contact-form"><h2>United States</h2><div class="flexbox flex-between wrap"><picture class="single-box-col-4"><img width="200" height="200" src="'. THEME_IMG_PATH .'/daniel-rennie.jpg" /><label>Daniel Rennie</label></picture><div class="single-box-col-7">'. do_shortcode('[gravityform id="2" title="false" description="false" ajax="false"]') .'</div></div></div>';
    $return_string .= '<div id="contact-europe" style="display:none;" class="point europe tippy"><div class="contact-form"><h2>Europe</h2><div class="flexbox flex-between wrap"><picture class="single-box-col-4"><img width="200" height="200" src="'. THEME_IMG_PATH .'/daniel-rennie.jpg" /><label>Daniel Rennie</label></picture><div class="single-box-col-7">'. do_shortcode('[gravityform id="3" title="false" description="false" ajax="false"]') .'</div></div></div>';
    $return_string .= '<div id="contact-asia" style="display:none;" class="point asia tippy"><div class="contact-form"><h2>Asia</h2><div class="flexbox flex-between wrap"><picture class="single-box-col-4"><img width="200" height="200" src="'. THEME_IMG_PATH .'/daniel-rennie.jpg" /><label>Daniel Rennie</label></picture><div class="single-box-col-7">'. do_shortcode('[gravityform id="4" title="false" description="false" ajax="false"]') .'</div></div></div>';
    $return_string .= '<div id="contact-australia" style="display:none;" class="point australia tippy"><div class="contact-form"><h2>Australia</h2><div class="flexbox flex-between wrap"><picture class="single-box-col-4"><img width="200" height="200" src="'. THEME_IMG_PATH .'/daniel-rennie.jpg" /><label>Daniel Rennie</label></picture><div class="single-box-col-7">'. do_shortcode('[gravityform id="5" title="false" description="false" ajax="false"]') .'</div></div></div>';
    $return_string .= '</div>';
    $return_string .= '<style>
      .map-container {
        position: relative;
        display: inline-block;
        width: 100%;
      }
      .map-container div[data-tippy-root] {
        width: 95% !important;
      }
      .map-container img {
        max-width: 1920px;
        height: auto;
        box-sizing: border-box;
        -moz-box-sizing: border-box;
        -webkit-box-sizing: border-box;
      }
	  .map-container .tippy-content picture {
	  	display: flex;
		flex-direction: column;
		align-items: center;
		justify-content: center;
	  }
      .map-container .tippy-content img {
	  	width: 100%;
        max-width: 350px;
      }
      .map-container .point {
        cursor: pointer;
        position: absolute;
        width: 65px;
        height: 65px;
        font-size: .8rem;
        color: #ffffff;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #1b2645;
        border-radius: 50%;
        transition: all 0.3s ease;
        will-change: transform, box-shadow;
        transform: translate(-50%, -50%);
        box-shadow: 0 0 0 rgb(27 38 69 / 40%);
        animation: pulse 3s infinite;
      }
      .map-container .point:hover {
        animation: none;
        transform: translate(-50%, -50%) scale3D(1.35, 1.35, 1);
        box-shadow: 0 3px 6px rgba(0, 0, 0, 0.16), 0 3px 6px rgba(0, 0, 0, 0.23);
        background-color: #e4886b;
      }
      .map-container .contact-form {
        text-align: center;
      }
      .map-container .contact-form h2 {
        margin-bottom: 0;
      }
      .map-container .contact-form img {
        border: 10px solid #00a0bd;
        border-radius: 100%;
      }
      .map-container .contact-form label {
        white-space: nowrap;
        font-size: 1.2rem;
        display: inline-block;
        margin-top: 20px;
      }
      .tippy-box {
        background: #fff !important;
        color: #000 !important;
      }
      .tippy-arrow {
        color: #fff !important;
      }
      .map-container .united-states {
        top: 40%;
        left: 20%;
      }
      .map-container .europe {
        top: 40%;
        left: 55%;
      }
      .map-container .asia {
        top: 45%;
        right: 22%;
      }
      .map-container .australia {
        bottom: 15%;
        right: 10%;
      }
      body.form-opened:before {
        content: "";
        width: 100%;
        height: 100%;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #000000;
        opacity: 0.5;
        z-index: 99999;
      }
      body.form-opened div[data-tippy-root] {
          z-index: 99999 !important;
      }
      @media screen and (min-width: 1441px){
        .map-container img {
          width: auto;
        }
      }
      @media screen and (max-width: 1440px){
        .map-container img {
          width: 100%;
        }
      }
      
      @keyframes pulse {
        0% {
          box-shadow: 0 0 0 0 rgba(27, 38, 69, 0.5);
        }
        70% {
          box-shadow: 0 0 0 25px rgba(0, 172, 193, 0);
        }
        100% {
          box-shadow: 0 0 0 0 rgba(0, 172, 193, 0);
        }
      }
    </style>';
    $return_string .= '<script src="https://unpkg.com/@popperjs/core@2"></script>';
    $return_string .= '<script src="https://unpkg.com/tippy.js@6"></script>';
    $return_string .= '<script>
    jQuery(document).ready(function(){
      tippy(".united-states", {
        theme: "light",
        trigger: "click",
        size: "big",
        arrow: true,
        placement: "bottom",
        interactive: true,
        content: jQuery("#contact-united-states").html(),
        allowHTML: true,
        maxWidth: 600,
        onShow() {
          jQuery("body").addClass("form-opened");
        },
        onHide() {
          jQuery("body").removeClass("form-opened");
        },
      });
      tippy(".europe", {
        theme: "light",
        trigger: "click",
        size: "big",
        arrow: true,
        placement: "bottom",
        interactive: true,
        content: jQuery("#contact-europe").html(),
        allowHTML: true,
        maxWidth: 600,
        onShow() {
          jQuery("body").addClass("form-opened");
        },
        onHide() {
          jQuery("body").removeClass("form-opened");
        },
      });
      tippy(".asia", {
        theme: "light",
        trigger: "click",
        size: "big",
        arrow: true,
        placement: "bottom",
        interactive: true,
        content: jQuery("#contact-asia").html(),
        allowHTML: true,
        maxWidth: 600,
        onShow() {
          jQuery("body").addClass("form-opened");
        },
        onHide() {
          jQuery("body").removeClass("form-opened");
        },
      });
      tippy(".australia", {
        theme: "light",
        trigger: "click",
        size: "big",
        arrow: true,
        placement: "bottom",
        interactive: true,
        content: jQuery("#contact-australia").html(),
        allowHTML: true,
        maxWidth: 600,
        onShow() {
          jQuery("body").addClass("form-opened");
        },
        onHide() {
          jQuery("body").removeClass("form-opened");
        },
      });
    });
    </script>';

    return $return_string;
}

function register_shortcodes(){
    add_shortcode('interactive-map', 'contact_us_map');
}

add_action( 'init', 'register_shortcodes');

// Breadcrumbs shortcode

function post_breadcrumbs() {
	$post_type = get_post_type();
	$archive = '';
	
	if ( $post_type == 'post' ) {
		$archive = 'latest-news';
	} else if ( $post_type == 'podcast' || $post_type == 'video' ) {
		$archive = 'podcasts-videos';
	} else if ( $post_type == 'whitepaper' || $post_type == 'insight' ) {
		$archive = 'whitepapers-insights';
	} else if ( $post_type == 'report' ) {
		$archive = 'project-reports';
	} else {
		$archive = $post_type . 's';
	}
	
	return 
		'<div class="breadcrumbs">
			<a href="'. site_url() .'">Home</a>
			&#62;
			<a href="'. site_url() .'/news-resources/">News & Resources</a>
			&#62;
			<a href="'. site_url() .'/news-resources#' . $archive . '">'. (($post_type == 'post') ? 'New' : ucfirst( $post_type )) .'s</a>
			&#62;
			<span>'. get_the_title() .'</span>
		</div>';
}

add_shortcode('breadcrumbs', 'post_breadcrumbs');

// Change to featured image when no resource link on post template

function featured_media_modifier() {
	$post_type = ucfirst(get_post_type());
	
	if(get_field('resource_link') == '') {
		return 
		   '<script>
		   		jQuery(".featured-media").eq(0).addClass("no-resource-link");
			</script>';
	} else {
		return 
		   '<script>'.
				($post_type == 'Podcast' ? 'jQuery(".featured-media h3").eq(0).html("Listen");' : '') .
		   		"jQuery('.featured-media h2').eq(0).html('$post_type Now');
			</script>";
	}
}

add_shortcode('featured_media_modifier', 'featured_media_modifier');