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
				<h1 class="page-title">Players</h1>
			</header><!-- .page-header -->

			<?php /* Start the Loop */
			// WP_Query arguments
			$player_args = array (
				'post_type'			=> array( 'player' ),
				'posts_per_page'	=> '-1',
				'meta_key'			=> 'last_name',
				'orderby'			=> 'meta_value',
				'order'				=> 'ASC'
			);

			// The Query
			$players_query = new WP_Query( $player_args );

			// The Loop
			if ( $players_query->have_posts() ) { 
				$player_num = sizeof($players_query->posts);
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
					
				<?php while ( $players_query->have_posts() ) {
					$players_query->the_post();
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
