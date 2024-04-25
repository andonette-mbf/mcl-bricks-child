<div class="container-fluid training-centre-map">
	<div class="container">
        <div class="row title-row training-title-row">
            <div class="col-12 anime-scroll">
                <span class="title to-animate">Our Training Centres</span>
				
				<a href="<?php echo get_permalink(1480);?>" class="float-right title-link">See all training locations</a>
				
				<div class="content float-left w-100">
				<?php echo get_field('global_map_content', '1480');?>
				</div>
            </div>
        </div>
    	<div class="row anime-scroll">
            <div class="col-12 col-md-10">
				<div class="map-main-block">
					<?php
					global $post;
					$args = array( 'posts_per_page' => 999, 'orderby' => 'date', 'order' => 'DESC', 'post_type' => 'locations' );

					$myposts = get_posts( $args );
					foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
                    
                        <?php if(get_field('map_pointer_colour')){?>
					
						<a href="#" data-map-location="<?php the_ID(); ?>" class="circle-marker" style="background:<?php the_field('map_pointer_colour');?>; left:<?php the_field('map_left_%');?>%; top:<?php the_field('map_top_%');?>%;"></a>
							
                        <div class="map-inner-modal position-absolute" id="map-modal-<?php the_ID(); ?>" style="left:<?php the_field('map_left_%');?>%; top:<?php the_field('map_top_%');?>%;">
							<a href="#" class="close-map-modal fal fa-times"></a>
                            <div class="post-content float-right position-relative">
                                <h3 class="float-left w-100 position-relative" title="<?php the_title();?>"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>

                                <div class="inner-content float-left w-100 position-relative">
                                    <?php if(get_field('location')){?>
                                    <div class="meta float-left w-100">
                                        <i class="fas fa-map-marker-alt"></i>

                                        <span class="address">
                                            <?php the_field('location', false, false); ?>
                                        </span>
                                    </div>
                                    <?php } ?>

                                    <?php if(get_field('phone_number')){?>
                                    <div class="meta float-left">
                                        <i class="fas fa-phone-alt"></i>

                                        <a href="tel:<?php the_field('phone_number');?>">
                                            <?php the_field('phone_number');?>
                                        </a>
                                    </div>
                                    <?php } ?>

                                    <?php if(get_field('email_address')){?>
                                    <div class="meta float-left">
                                        <i class="fas fa-envelope"></i>

                                        <a href="mailto:<?php the_field('email_address');?>">
                                            <?php the_field('email_address');?>
                                        </a>
                                    </div>
                                    <?php } ?>

                                    <?php if(get_field('website_address')){?>
                                    <div class="meta float-left">
                                        <i class="fas fa-globe"></i>

                                        <a href="https://<?php the_field('website_address');?>" target="_blank">
                                            <?php the_field('website_address');?>
                                        </a>
                                    </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    
                        <?php } ?>

					<?php endforeach; 
					wp_reset_postdata();?>
				</div>
			</div>
			
			<div class="col-12 col-md-2">
				<div class="map-key-block float-left w-100">
					<?php
					global $post;
					$args = array( 'posts_per_page' => 999, 'orderby' => 'date', 'order' => 'DESC', 'post_type' => 'locations' );

					$myposts = get_posts( $args );
					foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
					
                        <?php if(get_field('map_key_short_name')){?>
						<div class="key-block float-left w-100">
							<span class="circle-marker" style="background: <?php the_field('map_pointer_colour');?>;"></span>
							<a href="<?php the_permalink();?>" class="title float-left w-100"><?php the_field('map_key_short_name');?></a>
						</div>
                        <?php } ?>

					<?php endforeach; 
					wp_reset_postdata();?>
				</div>
			</div>
        </div>
    </div>
</div>