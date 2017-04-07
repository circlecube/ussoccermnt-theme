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
				<?php
					the_archive_title( '<h1 class="page-title">', '</h1>' );
					the_archive_description( '<div class="taxonomy-description">', '</div>' );
				?>
			</header><!-- .page-header -->

			<?php /* Start the Loop */

			// The Loop
			if ( have_posts() && get_post_type( get_the_ID() ) == 'player' ) { 
				?>
				<table>
					<thead>
						<tr>
							<th data-sorter="false">Image</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Position</th>
							<th>Caps</th>
							<th>Goals</th>
							<th>Squad</th>
							<th>Birthday</th>
						</tr>
					</thead>
					<tbody>
					
				<?php while ( have_posts() ) {
					the_post();
					// do something
					if ( get_field('caps') > 0 ) {
					?>
					<tr>
						<td><?php 
						if ( has_post_thumbnail() ) {
							the_post_thumbnail('thumbnail');
						} 
						?></td>
						<td><a href="<?php the_permalink(); ?>"><?php the_field('first_name'); ?></a></td>
						<td><a href="<?php the_permalink(); ?>"><?php the_field('last_name'); ?></a></td>
						<td><?php echo get_the_term_list( $post->ID, 'position', '', ', ' ); ?></td>
						<td><?php the_field( 'caps' ); ?></td>
						<td><?php the_field( 'goals' ); ?></td>
						<td><?php echo get_the_term_list( $post->ID, 'status', '', ', ' ); ?></td>
						<td data-sort-value=""><?php 
						// $date = DateTime::createFromFormat('Ymd', get_field('birthdate'));
						// echo $date->format('M d, Y');
						// echo $date->format('F d, Y');
						the_field('birthdate');
						?></td>
					</tr><?php
					}
				} ?>
				</tbody>
				</table>
				
				
				<?php
			} elseif ( have_posts() && get_post_type( get_the_ID() ) == 'match' ) {
				// $game_num = sizeof($query->posts);
				?>
				<table>
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
					<tbody>
					
				<?php 
				$match_total = 
				$match_wins = 
				$match_lost = 
				$match_draw = 
				$match_friendly_total = 
				$match_unfriendly_total = 
				$match_friendly_wins = 
				$match_unfriendly_wins = 0;
				while ( have_posts() ) {
					the_post();
					
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
					
					$match_total++;
					
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
						
					
					
					
					?>
					<tr>
						<td><a href="<?php the_permalink(); ?>"><?php the_field('match_number'); ?></a></td>
						<td data-sort-value="<?php the_field('date'); ?>"><a href="<?php the_permalink(); ?>"><?php 
						$date = DateTime::createFromFormat('Ymd', get_field('date'));
						// echo $date->format('M d, Y');
						echo $date->format('F d, Y');
						?></a></td>
						<td><?php echo get_the_term_list( $post->ID, 'opponent', '', '' ); ?></td>
						<td><?php echo get_the_term_list( $post->ID, 'match-type', '', '' ); ?></td>
						<td><?php echo the_field( 'score' ); ?></td>
						<td><?php echo get_the_term_list( $post->ID, 'result', '', '' ); ?></td>
						<td><?php 
							if ( get_field('coach') ) { 
								$coach_ids = get_field('coach'); 
								foreach( $coach_ids as $coach_id ) { ?>
									<a href="<?php echo post_permalink( $coach_id ); ?>">
										<?php echo get_the_title( $coach_id ); ?>
									</a>
								<?php }
							} ?>
						</td>
					</tr><?php
				} ?>
				</tbody>
				</table>
				
				
				<dt>Games Played</dt>
				<dd><?php echo $match_total; ?></dd>
				
				<dt>Games Won</dt>
				<dd><?php echo $match_wins; ?></dd>
				
				<dt>Games Tied</dt>
				<dd><?php echo $match_lost; ?></dd>
				
				<dt>Games Lost</dt>
				<dd><?php echo $match_draw; ?></dd>
				
				<dt>Overall Winning Percentage</dt>
				<dd><?php echo round( $match_wins / $match_total * 100, 2); ?></dd>
				
				<dt>Friendly Winning Percentage</dt>
				<dd><?php echo round( $match_friendly_wins / $match_friendly_total * 100, 2); ?></dd>
				
				
				<dt>Competitive Winning Percentage</dt>
				<dd><?php echo round( $match_unfriendly_wins /  $match_unfriendly_total * 100, 2); ?></dd>
				
				<?php
			} else {

				// no posts found
			}

			// Restore original Post Data
			wp_reset_postdata();
			
			
			?>
			


		<?php else : ?>

			<?php get_template_part( 'template-parts/content', 'none' ); ?>

		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
