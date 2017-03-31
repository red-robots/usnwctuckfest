`<?php
/*
 * @author: Fritz Healy
 * NOTE: Working from existing templates
 *
 * This function is the generic loop to display the queried posts as tiles on the tuckfest site
 * The $args value is passed from the template as the argument used for the query
 * NOTE: This function is faster than the recursive loop-recursive but the two can be 
 * used interchangably for all intensive purposes
 */
function display_loop_tile_touchable( $args ) {
	/*
	 * Queries the posts with WP_Query and set up The Loop
	 */
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		?>
        <div class="slideable touchable tile container <?php
		if ( $args['post_type'] && $args['post_type'] === 'page' ) {
			echo strtolower( preg_replace( "/\s+/", "-", preg_replace( "/&#[0-9]+;/"
				, "", get_the_title( $args['post_parent'] ) ) ) );
		} elseif ( $args['post_type'] && $args['post_type'] === 'post' ) {
			if ( $args['category__in'] ) {
				for ( $i = 0; $i < count( $args['category__in'] ); $i ++ ) {
					echo " " . strtolower( preg_replace( "/\s+/", "-", preg_replace( "/&#[0-9]+;/"
							, "", get_the_category_by_ID( $args['category__in'][ $i ] ) ) ) );
				}
			}
		}
		?>">
			<?php
			//The Loop
			while ( $query->have_posts() ) {
				$query->the_post();

				/*
				 * Get all of the page options using get_post_meta and get_field
				 */
				$embed_code     = get_post_meta( $query->post->ID, 'soy_vid', true );
				$vid_url        = get_post_meta( $query->post->ID, 'soy_vid_url', true );
				$force_feat_img = get_post_meta( $query->post->ID, 'soy_hide_vid', true );
				$show_title     = get_post_meta( $query->post->ID, 'soy_show_title', true );
				$show_desc      = get_post_meta( $query->post->ID, 'soy_show_desc', true );
				$box_size       = get_field( 'box_size' );
				/*
				 * Set the embed size for videos and the size for featured images using the
				 * box size gathered from the page
				 */
				if ( strcmp( $box_size, 'medium' ) === 0 ) {
					$my_size    = 'size-Medium';
					$embed_size = '495';
				} else if ( strcmp( $box_size, 'large' ) === 0 ) {
					$my_size    = 'size-Large';
					$embed_size = '670';
				} else if ( strcmp( $box_size, 'tiny' ) === 0 ) {
					$my_size    = 'size-Tiny';
					$embed_size = '145';
				} else {
					$my_size    = 'size-Default';
					$embed_size = '320';
				}
				/*
				 * Display a tile for the post only if there is some image/video to display
				 */
				if ( $embed_code || $vid_url || ( has_post_format( 'gallery' ) && ! $_force_feat_img ) || has_post_thumbnail() ) {
					/*
					 * Create a loop to get all the category names
					 */
					$category_classes = '';
					foreach ( ( get_the_category() ) as $category ) {
						$category_classes .= $category->category_nicename . ' ';
					}
					/*
					 * Set up an <a> tag to link the whole tile div to the post permalink
						* Assign class names including tile, the category, size, and post name of the
					 * tile
					 */
					if ( get_field( 'exclude' ) !== "yes" ) {
						?>
                        <div class="tile touchable slideable <?php
						echo $category_classes . $my_size . ' ' . $query->post->post_name;
						?>">
							<?php
							// Display video if available
							if ( ( $embed_code || $vid_url ) && ! $force_feat_img ) {
								if ( $vid_url ) {
									echo '<div class="vid-container">' . apply_filters( 'the_content', '[embed width="' . $embed_size . '"]' . $vid_url . '[/embed]' ) . '</div>';
								} else {
									echo '<div class="vid-container">' . $embed_code . '</div>';
								}
								// Display gallery
							} elseif ( has_post_format( 'gallery' ) && ! $force_feat_img ) {
								get_template_part( 'includes/loop-gallery' );
								/* Display featured image using the url of the featured image instead of
								* a thumbnail to avoid formatting issues with inline styling
								* Display div with event complete image of apropriate size if event complete
								* with featured image in background
								*/
							} elseif ( has_post_thumbnail() ) {
								$img_url     = wp_get_attachment_image_src( get_post_thumbnail_id( $query->post->ID ), 'tile' )[0]; //absolute path
								$img_url_rel = str_replace( home_url(), "", $img_url ); //relative path ?>
                                <img class="<?php echo $my_size; ?>" src="<?php echo $img_url_rel; ?>">
							<?php } ?>
                            <header>
                                <img src="/wp-content/uploads/2016/11/W_button.png">
                                <h2><?php the_title(); ?></h2>
                            </header>
                            <section class="copy">
								<?php
								$days = get_field( "schedule_day" );
								if ( $days ) {
									if ( strcmp( $query->post->post_name, "tuck-fest-prep-week-schedule" ) == 0 ) {
										echo "<p><span class='time'>Monday, April 17</span></p>";
										echo "<p><a href='http://tuckfest.org/competitions-demos/tuck-fest-prep-week/#recover-ride'>Recover Ride</a> - <span class='time'>6:00 pm</span></p>";
										echo "<p><span class='time'>Tuesday, April 18</span></p>";
										echo "<p><a href='http://tuckfest.org/competitions-demos/tuck-fest-prep-week/#six-pack-adventure-attack'>Six-Pack Adventure Attack</a> - <span class='time'>7:00 pm</span></p>";
										echo "<p><span class='time'>Wednesday, April 19</span></p>";
										echo "<p><a href='http://tuckfest.org/competitions-demos/tuck-fest-prep-week/#tigers-eye'>Tiger’s Eye</a> - <span class='time'>7:30 pm</span></p>";
										echo "<p><span class='time'>Thursday, April 20</span></p>";
										echo "<p><a href='http://tuckfest.org/competitions-demos/tuck-fest-prep-week/#afternoon-spin'>Afternoon Spin</a> - <span class='time'>3:30 pm</span></p>";
										echo "<p><a href='http://tuckfest.org/competitions-demos/tuck-fest-prep-week/#bloc-party'>Bloc Party</a> - <span class='time'>5:00 pm</span></p>";
									} else {
										$query_tile = new WP_Query( array( 'category__in'   => $days,
										                                   'post_type'      => 'post',
										                                   'order'          => 'ASC',
										                                   'posts_per_page' => '-1'
										) );
										if ( $query_tile->have_posts() ) {
											$ordered_titles = array();
											$ordered_times  = array();
											$end_ordered_times  = array();
											$ordered_am_pm  = array();
											$end_ordered_am_pm  = array();
											$ordered_12     = array();
											$end_ordered_12     = array();
											while ( $query_tile->have_posts() ) {
												$query_tile->the_post();
												for ( $j = 0; $j < count( $days ); $j ++ ) {
													$time = get_field( strtolower( get_category( $days[ $j ] )->slug ) . "_time" );
													if ( $time ) {
														$times = preg_split( "/,/", $time );
														$dashed_times = array();
														$end_dashed_times = array();
														for($i=0;$i<count($times);$i++){
															$dash_splits = preg_split( "/-/", $times[$i] );
															$dashed_times[] = $dash_splits[0];
															$end_dashed_times[] = isset($dash_splits[1]) ? $dash_splits[1]: "12 am";
                                                        }
														for ( $i = 0; $i < count( $times ); $i ++ ) {
															$time = $dashed_times[ $i ];
															$full_time = $times[$i];
															$end_time = $end_dashed_times[$i];
															$cats = array();
															foreach ( get_the_category() as $cat ) {
																$cats[] = $cat->category_nicename;
															}
															if ( in_array( "music-saturday", $cats ) || in_array( "music-friday", $cats ) || in_array( "music-sunday", $cats ) ) {
																$ordered_titles[] = "<p><a href='" . get_permalink() . "'><span class='highlight-music'>" . get_the_title() . "</span></a> - <span class='highlight-music time'>" . $time . "</span></p>";
															} else {
																$ordered_titles[] = "<p><a href='" . get_permalink() . "'>" . get_the_title() . "</a> - <span class='time'>" . $full_time . "</span></p>";
															}

															$unformatted_time = intval( preg_replace( "/[^0-9]/", "", $time ) );
															$end_unformatted_time = intval( preg_replace( "/[^0-9]/", "", $end_time ) );
															if ( preg_match( "/\d{3}|\d{4}/", $unformatted_time ) === 1 ) {
																$ordered_times[] = $unformatted_time;
															} else {
																$ordered_times[] = $unformatted_time * 100;
															}
															if ( preg_match( "/\d{3}|\d{4}/", $end_unformatted_time ) === 1 ) {
																$end_ordered_times[] = $end_unformatted_time;
															} else {
																$end_ordered_times[] = $end_unformatted_time * 100;
															}
															$ordered_am_pm[] = preg_replace( "/.*(am|pm).*/i", "$1", $time );
															$end_ordered_am_pm[] = preg_replace( "/.*(am|pm).*/i", "$1", $end_time );
															if ( preg_match( "/.*12.*/", $time ) === 1 ) {
																$ordered_12[] = 0;
															} else {
																$ordered_12[] = 1;
															}
															if ( preg_match( "/.*12.*/", $end_time ) === 1 ) {
																$end_ordered_12[] = 0;
															} else {
																$end_ordered_12[] = 1;
															}
														}
													}
												}
											}
											array_multisort( $ordered_am_pm, SORT_ASC, SORT_STRING, $ordered_12, SORT_ASC, SORT_NUMERIC, $ordered_times, SORT_ASC, SORT_NUMERIC, $end_ordered_am_pm, SORT_ASC, SORT_STRING, $end_ordered_12, SORT_ASC, SORT_NUMERIC, $end_ordered_times, SORT_ASC, SORT_NUMERIC, $ordered_titles, SORT_ASC, SORT_STRING );
											foreach ( $ordered_titles as $item ) {
												echo $item;
											}
										}
									}
								}
								wp_reset_postdata(); ?>
                            </section>
                        </div>
					<?php }
				}
			} //end of while
			?>
        </div>
	<?php } //end of if have posts 
	else {
		//if there are no posts then say nothing
	}
	wp_reset_postdata();
}

?>