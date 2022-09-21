<?php
add_action('wp_enqueue_scripts', function () {
  wp_enqueue_script('rest-ajax', get_stylesheet_directory_uri() . '/assets/rest-ajax.js', array( 'jquery' ), '', true);
  wp_localize_script( 'rest-ajax', 'siteConfig', [
		'ajax_nonce' => wp_create_nonce( 'load_jobs_nonce' ),
	] );
});

class WPC_REST extends WP_REST_Controller
{

    public function register_routes()
    {
        register_rest_route('leilac/v1', '/jobs', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'get_jobs'],
			'permission_callback' => '__return_true'
		]);
		
		register_rest_route('leilac/v1', '/content', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'get_content'],
			'permission_callback' => '__return_true'
		]);
		
		register_rest_route('leilac/v1', '/team-member', [
            'methods' => WP_REST_Server::READABLE,
            'callback' => [$this, 'get_team_member_profile'],
			'permission_callback' => '__return_true'
		]);
    }

    public function get_jobs($request)
    {
		$query = $this->get_filtered_posts_query($request);
		if (!$query->have_posts()) {
			return 0;
		}
		
		ob_start();
		
		while ( $query->have_posts() ) : $query->the_post() ?>
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
        <?php endwhile;
		wp_reset_postdata();

        $response = new WP_REST_Response(ob_get_contents());
        $response->set_status(200);
		
		ob_end_clean();
		
        return $response;
    }
	
	public function get_content($request)
    {
		$args = array(
			'post_type'			=> explode( ',', $request['post_types'] ),
			'cat'				=> $request['cat'],
			'offset'			=> $request['offset'],
			'paged'				=> $request['page'],
			'posts_per_page'	=> $request['per_page'],
		);
		
		$query = new WP_Query( $args );
		
		if (!$query->have_posts()) {
			return 0;
		}
		
		ob_start();
		
		while ( $query->have_posts() ) : $query->the_post() ?>
            <div class="content-card swiper-slide">
				<a href="<?php the_permalink() ?>" class="image"><?php the_post_thumbnail( [500, 0] ) ?></a>
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

        $response = new WP_REST_Response(ob_get_contents());
        $response->set_status(200);
		
		ob_end_clean();
		
        return $response;
    }
	
	public function get_team_member_profile($request) {
		$profile = array(
			'photo'			=> get_the_post_thumbnail( $request['team_member'], 'medium_large'),
			'name'			=> get_field('name', $request['team_member']),
			'role'			=> get_field('role', $request['team_member']),
			'biography' 	=> get_field('biography', $request['team_member']),
			'podcasts'		=> array(),
			'videos'		=> array(),
			'insights'		=> array(),
			'conferences'		=> array(),
			'whitepapers'		=> array(),
			'reports'		=> array(),
			'posts'		=> array(),
		);
		
		$args = array(
			'post_type'		=>  array('podcast', 'video', 'insight', 'conference'),
			'meta_query'	=>  array(
									array(
										'key' => 'advocates',
										'value' => $request['team_member'],
										'compare' => 'LIKE',
									)
								)
		);
		
		$query = new WP_Query($args);
		
		while($query->have_posts()) {
			$query->the_post();
			$post = '<div class="content">
						<a href="' . get_permalink() . '">' . get_the_post_thumbnail(get_the_ID(), 'medium_large') . '</a>
						<a href="' . get_permalink() . '"><h5>' . get_the_title() . '</h5></a>
					</div>';
			array_push($profile[get_post_type() . 's'], $post);
		}
		
		wp_reset_postdata();
			
		$response = new WP_REST_Response($profile);
        $response->set_status(200);
		
        return $response;
	}

    private function get_filtered_posts_query($request)
    {
		$filters = $request['filter'];
        $args = array(
            'post_type' => $request['post_type'],
            'posts_per_page' => $request['per_page'],
            'page' => $request['page'],
        );
						  
		if (str_contains($request['filter'], 'keyword')) {
			$args['s'] = $request['keyword'];
		}

		if (str_contains($request['filter'], 'field')) {
			$args['meta_query'] = 	array(
										array(
											'key' => $request['key'],
											'value' => $request['value'],
											'compare' => 'LIKE',
										)
									);
			if ($request['key2']) {
				$args['meta_query']['relation'] = 'AND';
				array_push($args['meta_query'], array(
													'key' => $request['key2'],
													'value' => $request['value2'],
													'compare' => 'LIKE',
												));
			}
		}
		if (str_contains($request['filter'], 'taxonomy')) {
			$termIDs = get_terms(['taxonomy' 	=> 	$request['taxonomy'],
									  'name__like' 	=> 	$request['term'],
									  'fields' 		=> 	'ids', ]);
			
			if ($request['taxonomy'] == 'category') {
				$args['cat'] = $termIDs;
			} else {
				$args['tax_query'] = 	array(
											'taxonomy' 	=> $request['taxonomy'],
											'field'		=> 'term_id',
											'terms' 	=> $termIDs,
										);
			}
        }

        return new WP_Query($args);
    }
}

add_action('rest_api_init', function ()
{
    if (class_exists('WPC_REST'))
    {
        $controller = new WPC_REST();
        $controller->register_routes();
    }
});