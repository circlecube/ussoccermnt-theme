<?php
/**
 * The template for displaying archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package US Soccer MNT
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">Coaches</h1>
			</header><!-- .page-header -->

			<?php 
			delete_transient( 'all_coaches_table' );
			// Get any existing copy of our transient data
			if ( false === ( $coach_table = get_transient( 'all_coaches_table' ) ) ) {
				// It wasn't there, so regenerate the data and save the transient
				
			/* Start the Loop */
			// WP_Query arguments
			$coach_args = array (
				'post_type'			=> array( 'coach' ),
				'posts_per_page'	=> '-1',
				'meta_key'			=> 'hire_date',
				'orderby'			=> 'meta_value_num',
				'order'				=> 'DESC'
			);
			
			
			// The Query
			$coaches_query = new WP_Query( $coach_args );

			// The Loop
			if ( $coaches_query->have_posts() ) { 
				$game_num = sizeof($coaches_query->posts);
				
				$coach_table = "<table>
					<thead>
						<tr>
							<th data-sorter='false'>Image</th>
							<th>Coach</th>
							<th>Home town</th>
							<th>Matches</th>
							<th>Wins</th>
							<th>Draws</th>
							<th>Loses</th>
							<th>Overall Winning Percentage</th>
							<th>Friendly Winning Percentage</th>
							<th>Competitive Winning Percentage</th>
						</tr>
					</thead>
					<tbody>";
					
				while ( $coaches_query->have_posts() ) {
					$coaches_query->the_post();
					// do something
					
					// update_field('field_55ae906eba5ea', $game_num); // use to reset game nums
					// $game_num--;
						// $date = DateTime::createFromFormat('Ymd', get_field('date'));
					$thumb = '';
					if ( has_post_thumbnail() ) {
						$thumb = get_the_post_thumbnail($post->ID, 'thumbnail');
					}
					$coach_table .= "<tr>
						<td>" . $thumb . "</td>
						<td><a href='" . get_the_permalink() . "'>" . the_title(null,null,false) . "</a></td>
						<td>" . get_field('hometown') . "</td>
						<td>" . get_field('caps') . "</td>
						<td>" . get_field('wins') . "</td>
						<td>" . get_field('draws') . "</td>
						<td>" . get_field('loses') . "</td>
						<td>" . round( get_field('wins') / get_field('caps') * 100, 2) . "</td>";
						
						$matches_posts = get_field('matches');
						$matches_ids = [];
						$unfriendly_wins = $unfriendly_total = $friendly_total = $friendly_wins = 0;
						if( $matches_posts ) {
							
							foreach( $matches_posts as $match) {
						    	// var_dump($match);
						    	$result_terms = get_the_terms( $match->ID, 'result');
						    	$results = [];
						    	if ( is_array( $result_terms) ) {
							    	foreach ( $result_terms as $term ) {
							    		$results[] = $term->name;
							    		// var_dump($term);
							    		// $result_permalink = get_term_link( $term );
							    	}

							    	$result = $results[0];
							    } else {
							    	$result = '-';
							    }
						    	
						    	$type_terms = get_the_terms( $match->ID, 'match-type');
						    	$types = [];
						    	foreach ( $type_terms as $term ) {
						    		$types[] = $term->name;
						    		// var_dump($term);
						    		// $type_permalink = get_term_link( $term );
						    	}
						    	$type = $types[0];
						    	
						    	$this_match = array( 
						    		'result' => $result,
						    		'type' => $type,
						    		'status' => get_post_status($match->ID)
					    		);
					    		
					    		array_push( $matches_ids, $this_match );								    		
						    }
						    								    
						    $num_matches = count($matches_ids);
						    
						    foreach( $matches_ids as $match){
						    	if ( $match['status'] == 'publish') {
							    	if ( $match['type'] == 'Friendly' ) {
							    		$friendly_total++;
							    		if ( $match['result'] == 'W' ) {
							    			$friendly_wins++;
							    		}
							    	}
							    	else {
							    		$unfriendly_total++;
							    		if ( $match['result'] == 'W' ) {
							    			$unfriendly_wins++;
							    		}
							    	}
							    }
						    }
						}
						if ( $friendly_total > 0 ) {
							$friendly_win_average = round( $friendly_wins / $friendly_total * 100, 2);
						} else {
							$friendly_win_average = '-';
						}

						if ( $unfriendly_total > 0 ) {
							$unfriendly_win_average = round( $unfriendly_wins / $unfriendly_total * 100, 2);
						} else {
							$unfriendly_win_average = '-';
						}
						$coach_table .= "<td>" . $friendly_win_average . "</td>
						<td>" . $unfriendly_win_average . "</td>
					</tr>";
				}
				$coach_table .= "</tbody>
				</table>";
				
			}

			// Restore original Post Data
			wp_reset_postdata();
			
			set_transient( 'all_coaches_table', $coach_table, DAY_IN_SECONDS );
			// echo 'not transient data';
		} else {
			// echo 'yes transient data';
		}
		
		echo $coach_table;
			
			
		?>
			


		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
