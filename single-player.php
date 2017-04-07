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
						<dt>Home town</dt>
						<dd><?php the_field('hometown'); ?></dd>
						
						<dt>Birthday</dt>
						<dd><?php the_field('birthdate'); ?></dd>
						
						<dt>Position</dt>
						<dd><?php the_field('position'); ?></dd>
						
						<dt>Caps</dt>
						<dd><?php the_field('caps'); ?></dd>
						
						<dt>Goals</dt>
						<dd><?php the_field('goals'); ?></dd>
						
						<dt>Club</dt>
						<dd>
							<?php 
								$club_terms = get_the_terms( get_the_ID(), 'club');
								$clubs = [];
								foreach ( $club_terms as $term ) {
									$clubs[] = $term->name;
									// var_dump($term);
									$club_permalink = get_term_link( $term );
								}
								$club = $clubs[0];
							?><a href="<?php echo $club_permalink; ?>"><?php echo $club; ?></a>
						</dd>
						
						<dt>Matches</dt>
							<?php
									$matches_posts = get_field('matches');
									$matches_ids = [];
									if( $matches_posts ) {
										
										// sort matches by date
										echo '<ol>';
										foreach( $matches_posts as $match) {
									    	// var_dump($match);
									    	
									    	$this_match = array( 
									    		'id' => $match->ID,
									    		'title' => $match->post_title,
									    		'permalink' => get_the_permalink( $match->ID ),
									    		'date' => get_field( 'date', $match->ID )
								    		);
								    		
								    		array_push( $matches_ids, $this_match );
								    		// echo '<dd><a href="' . get_the_permalink( $match->ID) . '">' . $match->post_title . '</a></dd>';
								    		
									    }
									    
									    array_sort_by_column($matches_ids, 'date', SORT_DESC);
									    
									    foreach( $matches_ids as $match){
									    	echo '<li><a href="' . $match['permalink'] . '">' . $match['title'] . '</a></li>';

									    }
									    echo '</ol>';
									}
							?>
						
						
						
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
