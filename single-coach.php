<?php
/**
 * The template for displaying all single posts.
 *
 * @package US Soccer MNT
 */

get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<?php while ( have_posts() ) : the_post(); ?>

			<?php //get_template_part( 'template-parts/content', 'single' ); ?>

			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php if ( has_post_thumbnail() ) { ?>
					<div class="post-thumbnail">
						<?php the_post_thumbnail('large'); ?>
					</div><!-- .post-thumbnail -->
				<?php } ?>

				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<dl>
						<dt>Home town</dt>
						<dd><?php the_field('hometown'); ?></dd>
						
						<dt>Birthday</dt>
						<dd><?php the_field('birthdate'); ?></dd>
						
						<dt>Position</dt>
						<dd>Coach</dd>
						
						<dt>Matches</dt>
							<?php
									$matches_posts = get_field('matches');
									$matches_ids = [];
									$unfriendly_wins = $unfriendly_total = $friendly_total = $friendly_wins = 0;
									if( $matches_posts ) {
										
										// sort matches by date
										echo '<table>
												<thead>
													<tr>
														<th>Match</th>
														<th>Overall Match</th>
														<th>Date</th>
														<th>Opponent</th>
														<th>Type</th>
														<th>Score</th>
														<th>Result</th>
													</tr>
												</thead>
												<tbody>';
										foreach( $matches_posts as $match) {
									    	// var_dump($match);
									    	$result_terms = get_the_terms( $match->ID, 'result');
									    	$results = [];
									    	foreach ( $result_terms as $term ) {
									    		$results[] = $term->name;
									    		// var_dump($term);
									    		$result_permalink = get_term_link( $term );
									    	}
									    	$result = $results[0];
									    	
									    	$opponent_terms = get_the_terms( $match->ID, 'opponent');
									    	$opponents = [];
									    	foreach ( $opponent_terms as $term ) {
									    		$opponents[] = $term->name;
									    		// var_dump($term);
									    		$opponent_permalink = get_term_link( $term );
									    	}
									    	$opponent = $opponents[0];
									    	
									    	$type_terms = get_the_terms( $match->ID, 'match-type');
									    	$types = [];
									    	foreach ( $type_terms as $term ) {
									    		$types[] = $term->name;
									    		// var_dump($term);
									    		$type_permalink = get_term_link( $term );
									    	}
									    	$type = $types[0];
									    	
									    	$this_match = array(
									    		'status' => get_post_status($match->ID),
									    		'id' => $match->ID,
									    		'title' => $match->post_title,
									    		'permalink' => get_the_permalink( $match->ID ),
									    		'date' => get_field( 'date', $match->ID ),
									    		'result' => $result,
									    		'result_permalink' => $result_permalink,
									    		'opponent' => $opponent,
									    		'opponent_permalink' => $opponent_permalink,
									    		'type' => $type,
									    		'type_permalink' => $type_permalink,
									    		'score' => get_field('score', $match->ID ),
									    		'match_number' => get_field('match_number', $match->ID ),
								    		);
								    		
								    		array_push( $matches_ids, $this_match );								    		
									    }
									    
									    array_sort_by_column($matches_ids, 'match_number', SORT_DESC);
									    
									    $num_matches = count($matches_ids);
									    
									    foreach( $matches_ids as $match){
									    	if ( $match['status'] == 'publish' ) {
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
									    	// echo '<li><a href="' . $match['permalink'] . '">' . $match['title'] . ' - ' . $match['result'] . '</a></li>';
									    	?>
									    	<tr><td><a href="<?php echo $match['permalink']; ?>"><?php 
									    			echo $num_matches--; 
									    		?></a></td>
									    		<td><a href="<?php echo $match['permalink']; ?>"><?php 
									    			echo $match['match_number']; 
									    		?></a></td>
									    		<td data-sort-value="<?php echo $match['date']; ?>"><a href="<?php echo $match['permalink']; ?>"><?php 
									    		$date = DateTime::createFromFormat('Ymd', $match['date']);
									    		echo $date->format('F d, Y');
									    		?></a></td>
									    		<td><a href="<?php echo $match['opponent_permalink']; ?>"><?php echo $match['opponent']; ?></a></td>
									    		<td><a href="<?php echo $match['type_permalink']; ?>"><?php echo $match['type']; ?></a></td>
									    		<td><?php echo $match['score']; ?></td>
									    		<td><a href="<?php echo $match['result_permalink']; ?>"><?php echo $match['result']; ?></a></td>
									    	</tr>
									    	<?php
									    }
									    echo '</tbody>
											</table>';
									}
							?>
						
						
						
						<dt>Games Coached</dt>
						<dd><?php the_field('caps'); ?></dd>
						
						<dt>Games Won</dt>
						<dd><?php the_field('wins'); ?></dd>
						
						<dt>Games Tied</dt>
						<dd><?php the_field('draws'); ?></dd>
						
						<dt>Games Lost</dt>
						<dd><?php the_field('loses'); ?></dd>
						
						<dt>Overall Winning Percentage</dt>
						<dd><?php echo round( get_field('wins') / get_field('caps') * 100, 2); ?></dd>
						
						<dt>Friendly Winning Percentage</dt>
						<dd><?php echo round( $friendly_wins / $friendly_total * 100, 2); ?></dd>
						
						
						<dt>Competitive Winning Percentage</dt>
						<dd><?php echo round( $unfriendly_wins /  $unfriendly_total * 100, 2); ?></dd>
						
					</dl>
					
					<?php //the_content(); ?>
					<?php
						/*wp_link_pages( array(
							'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'twentyfifteen' ) . '</span>',
							'after'       => '</div>',
							'link_before' => '<span>',
							'link_after'  => '</span>',
							'pagelink'    => '<span class="screen-reader-text">' . __( 'Page', 'twentyfifteen' ) . ' </span>%',
							'separator'   => '<span class="screen-reader-text">, </span>',
						) );*/
					?>
				</div><!-- .entry-content -->

				<?php edit_post_link( __( 'Edit', 'twentyfifteen' ), '<footer class="entry-footer"><span class="edit-link">', '</span></footer><!-- .entry-footer -->' ); ?>

			</article><!-- #post-## -->

			<?php the_post_navigation(); ?>

			<?php
				// If comments are open or we have at least one comment, load up the comment template.
				if ( comments_open() || get_comments_number() ) :
					comments_template();
				endif;
			?>

		<?php endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
