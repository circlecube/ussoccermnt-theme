<?php
/**
 * Template Name: USMNT Roster
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */
define('WP_USE_THEMES', false);
// require('./blog/wp-blog-header.php');
 
// set header for json mime type
header('Content-type: application/json;');

// delete_transient( 'roster_json' );

if ( false === ( $json = get_transient( 'roster_json' ) ) ) {
	
	$json = 'var usmnt_players = [';

	$player_args = array(
		'post_type' => 'player',
		'posts_per_page' => -1,
		'meta_key' => 'last_name',
		'orderby' => 'meta_value',
		'order' => 'ASC'
	);
	$player_query = new WP_Query( $player_args );
	// The Loop
	if ( $player_query->have_posts() ) {
		while ( $player_query->have_posts() ) {
			$player_query->the_post();
			
			
			//CLUB
			$club_terms = get_the_terms( get_the_ID(), 'club');
			$clubs = [];
			foreach ( $club_terms as $term ) {
				$clubs[] = $term->name;
			}
			$club = $clubs[0];
			
			//IMG
			$attachment_id = get_post_thumbnail_id( get_the_ID() );
			$image_attributes = wp_get_attachment_image_src( $attachment_id, 'large' ); // returns an array
			$img = $image_attributes[0];
			
			//STATUS
			$player_status_terms = get_the_terms( get_the_ID(), 'status');
			$player_status = [];
			foreach ( $player_status_terms as $term ) {
				$player_status[] = $term->name;
			}
			$rosters = implode(",", $player_status);
			
			//MATCHES
			$matches_posts = get_field('matches');
			$matches_ids = [];
			$first_match = array( 'date' => INF, 'id' => 0 );;
			$last_match = array( 'date' => 0, 'id' => 0 );
			if( $matches_posts ) {
				
				foreach( $matches_posts as $match) {
			    	// var_dump($match);
			    	
			    	$this_match = array( 
			    		'id' => $match->ID,
			    		'date' => get_field('date', $match->ID)
		    		);
			    	// var_dump($this_match['id']);
			    	$matches_ids[] = $this_match;
		    		
		    		//first check
		    		if ( $first_match['date'] > $this_match['date'] ) {
		    			$first_match = $this_match;
		    		}
		    		
		    		//last check
		    		if ( $last_match['date'] < $this_match['date'] ) {
		    			$last_match = $this_match;
		    		}
			    }
			}
			if ( $first_match['id'] ) {
				$first_match['title'] = get_the_title( $first_match['id'] );
			}
			else {
				$first_match['title'] = false;
			}
			if ( $last_match['id'] ) {
				$last_match['title'] = get_the_title( $last_match['id'] );
			}
			else {
				$last_match['title'] = false;
			}
			if ( get_field('goals') ){
				$goals = get_field('goals');
			} else {
				$goals = 0;
			}

			if ( get_field('caps') && $img ) {
				
	$json .= "{
'player': '" . the_title(null,null,false) . "',
'first_name': '" . get_field('first_name') . "',
'last_name': '" . get_field('last_name') . "',
'pos': '" . get_field('position') . "',
'birthdate':  '" . get_field('birthdate') . "',
'hometown': '" . get_field('hometown') . "',
'club': '" . $club . "',
'img': '" . $img . "',
'caps': " . get_field('caps') . ",
'goals': " . $goals . ",
'rosters': '" . $rosters . "',
},";

			} //end if
			
		} // end while 
	}// end loop if
		
	wp_reset_query();
	$json .= '];var usmnt_coaches = [';


	$player_args = array(
		'post_type' => 'coach',
		'posts_per_page' => -1,
		'meta_key' => 'last_name',
		'orderby' => 'meta_value',
		'order' => 'ASC'
	);
	$player_query = new WP_Query( $player_args );
	// The Loop
	if ( $player_query->have_posts() ) {
		while ( $player_query->have_posts() ) {
			$player_query->the_post();
			
			//IMG
			$attachment_id = get_post_thumbnail_id( get_the_ID() );
			$image_attributes = wp_get_attachment_image_src( $attachment_id, 'large' ); // returns an array
			$img = $image_attributes[0];

	$json .= "{
'player': '" . the_title(null,null,false) . "',
'first_name': '" . get_field('first_name') . "',
'last_name': '" . get_field('last_name') . "',	
'pos': 'Coach',
'birthdate':  '" . get_field('birthdate') . "',
'hometown': '" . get_field('hometown') . "',
'img': '" . $img . "',
'games': " . get_field('caps') . ",
'wins': " . get_field('wins') . ",
'draws': " . get_field('draws') . ",
'loses': " . get_field('loses') . ",
'start_date':  '" . get_field('hire_date') . "',
'end_date':  '" . get_field('end_date') . "',
},";
			
		} // end while 
	}// end loop if

	$json .= "];";

	set_transient( 'roster_json', $json, WEEK_IN_SECONDS );
}

echo $json;



die(); ?>