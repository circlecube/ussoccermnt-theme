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
				<h1 class="page-title">Matches</h1>
			</header><!-- .page-header -->

			<?php 
			// delete_transient( 'all_matches_table' );
			// Get any existing copy of our transient data
			if ( false === ( $match_table = get_transient( 'all_matches_table' ) ) ) {
				// It wasn't there, so regenerate the data and save the transient
				
			/* Start the Loop */
			// WP_Query arguments
			$match_args = array (
				'post_type'			=> array( 'match' ),
				'posts_per_page'	=> '-1',
				'meta_key'			=> 'date',
				'orderby'			=> 'meta_value_num',
				'order'				=> 'DESC',
				'post_status'		=> array('publish','draft')
			);
			
			
			// The Query
			$matches_query = new WP_Query( $match_args );

			// The Loop
			if ( $matches_query->have_posts() ) { 
				$game_num = sizeof($matches_query->posts);
				
				$match_table = "<table>
					<thead>
						<tr>
							<th>Match</th>
							<th>Date</th>
							<th>Opponent</th>
							<th>Type</th>
							<th>Score</th>
							<th>Result</th>
							<th>Coach</th>
						</tr>
					</thead>
					<tbody>";
					
				$match_total = 
				$match_wins = 
				$match_lost = 
				$match_draw = 
				$match_friendly_total = 
				$match_unfriendly_total = 
				$match_friendly_wins = 
				$match_unfriendly_wins = 0;
				
				while ( $matches_query->have_posts() ) {
					$matches_query->the_post();
					// do something
					
					$type_terms = get_the_terms( $post->ID, 'match-type');
					$types = [];
					foreach ( $type_terms as $term ) {
						$types[] = $term->name;
					}
					$result_terms = get_the_terms( $match->ID, 'result');
					$results = [];
					foreach ( $result_terms as $term ) {
						$results[] = $term->name;
					}
					if ( $post->post_status == 'publish') {
						$match_total++;
					}
					if ( $results[0] == 'W' ) {
						$match_wins++;
					} elseif ( $results[0] == 'L' ) {
						$match_lost++;
					} elseif ( $results[0] == 'D' ) {
						$match_draw++;
					}
					
					
					if ( $types[0] == 'Friendly' ) {
						$match_friendly_total++;
						
						if ( $results[0] == 'W' ) {
							$match_friendly_wins++;
						}
					}
					else {
						$match_unfriendly_total++;
						
						if ( $results[0] == 'W' ) {
							$match_unfriendly_wins++;
						}
					}
					
					// update_field('field_55ae906eba5ea', $game_num); // use to reset game nums
					// $game_num--;
						$date = DateTime::createFromFormat('Ymd', get_field('date'));
						
						if ( get_field('coach') ) { 
							$coach_ids = get_field('coach'); 
							$coach_id = $coach_ids[0];
						}
						else {
							$coach_id = null;
						}
						if ( $coach_id ) {
							$coach_link = "<a href='" . post_permalink( $coach_id ) . "'>" . get_the_title( $coach_id ) ."</a>";
						} else {
							$coach_link = "not set";
						}

					$match_table .= "<tr>
						<td><a href='" . get_the_permalink() . "'>" . get_field('match_number') . "</a></td>
						<td data-sort-value='" . get_field('date') . "'><a href='" . get_the_permalink() . "'>" . $date->format('F d, Y') . "</a></td>
						<td>" . get_the_term_list( $post->ID, 'opponent', '', '' ) . "</td>
						<td>" . get_the_term_list( $post->ID, 'match-type', '', '' ) . "</td>
						<td>" . get_field( 'score' ) . "</td>
						<td>" . get_the_term_list( $post->ID, 'result', '', '' ) . "</td>
						<td>" . $coach_link . "</td>
					</tr>";
				}
				$match_table .= "</tbody>
				</table>";
				
				
				$match_table .= "
				<dt>Games Played</dt>
				<dd>" . $match_total . "</dd>
				
				<dt>Games Won</dt>
				<dd>" . $match_wins . "</dd>
				
				<dt>Games Tied</dt>
				<dd>" . $match_lost . "</dd>
				
				<dt>Games Lost</dt>
				<dd>" . $match_draw . "</dd>
				
				<dt>Overall Winning Percentage</dt>
				<dd>" . round( $match_wins / $match_total * 100, 2) . "</dd>
				
				<dt>Friendly Winning Percentage</dt>
				<dd>" . round( $match_friendly_wins / $match_friendly_total * 100, 2) . "</dd>
				
				
				<dt>Competitive Winning Percentage</dt>
				<dd>" . round( $match_unfriendly_wins /  $match_unfriendly_total * 100, 2) . "</dd>";
				
			}

			// Restore original Post Data
			wp_reset_postdata();
			
			set_transient( 'all_matches_table', $match_table, DAY_IN_SECONDS );
			// echo 'not transient data';
		} else {
			// echo 'yes transient data';
		}
		
		echo $match_table;
			
			
		?>
			


		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
