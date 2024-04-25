<div class="slider-main">
   <?php         
      // loop through the rows of data
      while ( have_rows('slider') ) : the_row();?>
      
		<?php 
             $sliderAttachmentId = get_sub_field('background_image');
             $sliderSize = 'fullWidthHeaderImage';
             $sliderImage = wp_get_attachment_image_src($sliderAttachmentId, $sliderSize); 
        ?>
      
      	<div class="slides" title="<?php echo get_post_meta($sliderAttachmentId, '_wp_attachment_image_alt', true) ?>">
        	<div class="overlay">
                <div class="container h-100 position-relative">
                    <div class="row h-100 position-relative">
                        <div class="col-12 col-md-7 align-self-center anime-view">
                            <div class="title-container to-animate float-left w-100 position-relative">
                                <h1 class="float-left w-100 position-relative"><?php the_sub_field('title');?></h1>
                            </div>
                            
                            <div class="content to-animate float-left w-100 position-relative">
                                <?php the_sub_field('content');?>
                            </div>
                            
                            <div class="slider-search-bar to-animate float-left w-100 position-relative">
								<b class="float-left w-100 title">Search our available courses...</b>
								
								<div class="form-inner float-left w-100">
									<form class="slider-form float-left w-100">
										<div class="fields dropdown-selects no-margin">
											
											<?php
											$query_args = array(
											   
											   
											 );?>
											
											<div class="fields half no-margin">
												<label>
													<select name="course" id="course">
													  	<option value="no-option">Select a course</option>
														
														<?php
														global $post;
														$args = array( 'post_status' => 'publish', 'posts_per_page' => 999, 'post_type' => 'product', 'orderby' => 'date', 'order' => 'DESC', 'tax_query' => array(
															array(
																'taxonomy' => 'product_type',
																'field'    => 'slug',
																'terms'    => 'grouped', 
															),
														));
														$myposts = get_posts( $args );
														foreach ( $myposts as $post ) : setup_postdata( $post ); 
                                                        
                                                        global $product;
                                                        $parent_group = $product;
                                                        $locations_to_use = [];
                                                        $locations = $parent_group->get_children();
                                                        foreach($locations as $location){
                                                            $location_string = get_field('select_address', $location);
                                                            if (str_contains($location_string, 'London')) {
                                                                $locations_to_use[] = 'nottingham';
                                                            } elseif (str_contains($location_string, 'Nottingham')) {
                                                                $locations_to_use[] = 'lincolnshire';
                                                            } elseif (str_contains($location_string, 'Lincolnshire')) {
                                                                $locations_to_use[] = 'london';
                                                            }
                                                        }
                                                        ?>
                                                        
                                                        	<?php /*?><?php $getTitle = get_the_title();?>
                                                            
                                                            <?php if($getTitle == 'CISRS PLETTAC Metrix Training') {?>
                                                            	
                                                                <?php $hideLocation = "hide-london";?>
                                                                
                                                            <?php } ?><?php */?>

															<option value="<?php the_permalink();?>" data-data='{"locations": "<?php echo implode(",", $locations_to_use);?>"}' class="location"><?php the_title();?></option>

														<?php endforeach; 
														wp_reset_postdata();?>

													</select>
												</label>
											</div>

											<div class="fields half right no-margin location-select disabled">
												<label>
													<select name="location" id="location">
													  	<option>Select location</option>
													  	<option value="humber">Humber</option>
													  	<option value="nottingham">Nottingham</option>
													  	<option value="london">London</option>
													</select>
												</label>
											</div>
										</div>
										<div class="fields last submit dropdown-submit no-margin">
											<button type="submit" class="search-submit cta-button"><i class="fal fa-search"></i></button>
										</div>
									</form>
								</div>
							</div>
                        </div>
                        
                        <div class="col-12 col-md-5 anime-view hidden-mobiles-class gliph-col-container">
                        	<div class="celebrating-30-years to-animate"></div>
							
							<div class="penrose-animation">
								<div class="penrose-lighting">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="lighting-triangle" preserveAspectRatio="xMidYMid" width="521.99" height="599.136" viewBox="0 0 521 598">
										<defs>
											<linearGradient id="lightingGradient" x2="0.35" y2="1">
												<stop offset="0%" stop-color="#1b1b1b" />
												<stop offset="100%" stop-color="#ffffff" />
											</linearGradient>
										</defs>
										<g>
											<path d="M82.000,182.000 C107.997,196.999 134.003,212.001 160.000,227.000 C162.000,304.326 164.000,381.674 166.000,459.000 C166.333,460.000 166.667,461.000 167.000,462.000 C283.322,393.673 399.678,325.327 516.000,257.000 C517.666,286.664 519.333,316.336 521.000,346.000 C378.681,429.992 236.319,514.008 94.000,598.000 C90.000,459.347 86.000,320.653 82.000,182.000 Z" class="cls-1"/>
											<path d="M0.000,48.000 C119.321,116.993 238.679,186.007 358.000,255.000 C333.336,269.665 308.664,284.335 284.000,299.000 C216.673,259.004 149.327,218.996 82.000,179.000 C81.333,179.333 80.667,179.667 80.000,180.000 C84.000,318.986 88.000,458.014 92.000,597.000 C66.669,582.335 41.331,567.665 16.000,553.000 C10.667,384.684 5.333,216.316 0.000,48.000 Z" class="cls-2"/>
											<path d="M76.000,0.000 C221.652,84.992 367.348,170.008 513.000,255.000 C398.012,323.327 282.988,391.673 168.000,460.000 C167.667,430.670 167.333,401.330 167.000,372.000 C232.327,332.337 297.673,292.663 363.000,253.000 C243.012,184.673 122.988,116.326 3.000,48.000 C27.331,32.002 51.669,15.998 76.000,0.000 Z" class="cls-3"/>
										</g>
									</svg>
								</div>
								<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" class="main-triangle" preserveAspectRatio="xMidYMid" width="521.99" height="599.136" viewBox="0 0 521 598">
									<defs>
										<pattern id="penroseBG" patternUnits="userSpaceOnUse" width="100%" height="120%">
											<image href="/wp-content/uploads/2022/02/51notts-1-scaled-e1644604800830.jpg" x="-10%" y="-10%" width="140%" height="140%"></image>
										</pattern>
									</defs>
									<g>
										<path d="M82.000,182.000 C107.997,196.999 134.003,212.001 160.000,227.000 C162.000,304.326 164.000,381.674 166.000,459.000 C166.333,460.000 166.667,461.000 167.000,462.000 C283.322,393.673 399.678,325.327 516.000,257.000 C517.666,286.664 519.333,316.336 521.000,346.000 C378.681,429.992 236.319,514.008 94.000,598.000 C90.000,459.347 86.000,320.653 82.000,182.000 Z" class="cls-1"/>
										<path d="M0.000,48.000 C119.321,116.993 238.679,186.007 358.000,255.000 C333.336,269.665 308.664,284.335 284.000,299.000 C216.673,259.004 149.327,218.996 82.000,179.000 C81.333,179.333 80.667,179.667 80.000,180.000 C84.000,318.986 88.000,458.014 92.000,597.000 C66.669,582.335 41.331,567.665 16.000,553.000 C10.667,384.684 5.333,216.316 0.000,48.000 Z" class="cls-2"/>
										<path d="M76.000,0.000 C221.652,84.992 367.348,170.008 513.000,255.000 C398.012,323.327 282.988,391.673 168.000,460.000 C167.667,430.670 167.333,401.330 167.000,372.000 C232.327,332.337 297.673,292.663 363.000,253.000 C243.012,184.673 122.988,116.326 3.000,48.000 C27.331,32.002 51.669,15.998 76.000,0.000 Z" class="cls-3"/>
									</g>
								</svg>
							</div>
                        </div>                        
                    </div>
                </div>
            </div>
            
        </div>
                        
  <?php   
     endwhile;
  ?>
</div>