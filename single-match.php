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
						<?php the_post_thumbnail(); ?>
					</div><!-- .post-thumbnail -->
				<?php } ?>

				<header class="entry-header">
					<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
				</header><!-- .entry-header -->

				<div class="entry-content">
					<dl>
						<dt>Date</dt>
						<dd><?php the_field('date'); ?></dd>
						
						<dt>Opponent</dt>
						<dd><?php 
							$opponent_terms = get_the_terms( get_the_ID(), 'opponent');
							$opponents = [];
							foreach ( $opponent_terms as $term ) {
								$opponents[] = $term->name;
								// var_dump($term);
								$opponent_permalink = get_term_link( $term );
							}
							$opponent = $opponents[0];
						?><a href="<?php echo $opponent_permalink; ?>"><?php echo $opponent; ?></a>
						</dd>
						
						<dt>Score</dt>
						<dd><?php the_field('score'); ?></dd>
						
						<dt>Result</dt>
						<dd><?php 
							$result_terms = get_the_terms( get_the_ID(), 'result');
							$results = [];
							foreach ( $result_terms as $term ) {
								$results[] = $term->name;
								// var_dump($term);
								$result_permalink = get_term_link( $term );
							}
							$result = $results[0];
						?><a href="<?php echo $result_permalink; ?>"><?php echo $result; ?></a>
						</dd>
						
						<dt>Type</dt>
						<dd><?php 
							$match_type_terms = get_the_terms( get_the_ID(), 'match-type');
							$match_types = [];
							foreach ( $match_type_terms as $term ) {
								$match_types[] = $term->name;
								// var_dump($term);
								$match_type_permalink = get_term_link( $term );
							}
							$match_type = $match_types[0];
						?><a href="<?php echo $match_type_permalink; ?>"><?php echo $match_type; ?></a>
						</dd>
						
						<?php
						$caps = get_field('players');
						$capped_players = array();
						if ( $caps ) { ?>
							<dt>Lineup</dt>
							<?php foreach ( $caps as $post ) {
								setup_postdata($post);
								$player = '<a href="' . get_the_permalink() . '" class="player_card">';
								if ( has_post_thumbnail() ) {
									$player .= '<span class="post-thumbnail">';
									$player .= get_the_post_thumbnail($post->id, array(72,72));
									$player .= '</span>';
								}
								$player .= get_the_title() . '</a>';
								$capped_players[] = $player;
							}
							wp_reset_postdata();
						} ?>
						<dd><?php echo implode( '', $capped_players); ?></dd>
						
						<?php if ( have_rows('goals') ) { ?>
							<dt>Goals</dt>
								<dd>
							<?php while( have_rows('goals') ) {
								the_row();
								$players = get_sub_field('player');
								if ( $players ) {
									foreach ( $players as $post ) {
										setup_postdata($post);
										$player_id = get_the_ID();
									}
									wp_reset_postdata();
								}
								?>
									<a href="<?php echo get_the_permalink( $player_id ); ?>" class="player_card">
									<?php if ( has_post_thumbnail( $player_id ) ) { ?>
										<span class="post-thumbnail">
											<?php echo get_the_post_thumbnail($player_id, array(72,72)); ?>
										</span>
									<?php } ?>
									<?php echo get_the_title($player_id); ?>
									 '<?php the_sub_field('minute'); ?></a>
							
						<?php } //end while  
						} //end if ?>
								</dd>
						<?php
						
						$coach = get_field('coach');
						$capped_coach = array();
						if ( $coach ) { ?>
							<dt>Coach</dt>
							<?php foreach ( $coach as $post ) {
								setup_postdata($post);
								$capped_coach[] = '<a href="' . get_the_permalink() . '">' . get_the_title() . '</a>';
							}
							wp_reset_postdata();
						} ?>
						<dd><?php echo implode( ', ', $capped_coach); ?></dd>
						
						
						
						
					</dl>
					
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
