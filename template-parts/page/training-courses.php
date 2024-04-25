

<div class="container-fluid product-panel training-list">
	<div class="container">
		<div class="row no-margin">
            <div class="row">
                <div class="col-12 col-md-7 col-lg-8">
                    <form class="filterable_search" autocomplete="off">

                    <input type="hidden" name="post_types[]" value="product" /> <!-- At least one must be defined -->
                    <input type="hidden" name="posts_per_page" value="7" /> <!-- Make sure this is the same as your archive or current page -->

                    <!-- Leave as below -->
                    <input type="hidden" name="append" value="false" />
                    <input type="hidden" name="paged" value="1" />
                    <input type="hidden" name="last_action" value="" />
                    <input type="hidden" name="sort_by" value="menu_order" />
					<input type="hidden" name="sort_dir" value="asc" />

                    <?php
                    //Detect changes from URL params
                    $trigger_change = false;
                    ?>

                    <!--
                    If you want to specify an override, do so here for example if you're outputting as table rows and don't want to use
                    the standard content-post-type.php file. By default the plugin will look for this in your template directory.
                    -->
                    <input type="hidden" name="template_override" value="template-parts/post/content-training.php" />

                    <!-- Pass in current product category -->
                    <?php if (isset($term_id) && $term_id !== "") {?>
                    <input type="hidden" name="default_tax[product_cat][]" value="<?php echo $term_id; ?>" />
                    <?php } ?>


                    <div class="row anime-view" id="filterable_search_results">
                        <?php if ( have_posts() ) :

                            while ( have_posts() ) : the_post();

                                include( get_stylesheet_directory() . '/template-parts/post/content-training.php');

                            endwhile;

                        else :

                            include( get_stylesheet_directory() .'/template-parts/post/content-none.php' );

                        endif;
                        ?>
                    </div>

                    <div class="form-overlay">
                        <div class="lds-ring vhboth">
                          <div></div>
                          <div></div>
                          <div></div>
                          <div></div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12 anime-view">
                            <?php
                            global $wp_query;
                            if ($wp_query->found_posts > 7) { ?>
                              <span class="cta-button secondary float-left to-animate" id="load_more">Load More Courses</span>
                            <?php } ?>
                        </div>
                    </div>
                    </form>
                </div>

                <div class="col-12 col-md-5 col-lg-4">
                    <div class="main-container-sidebar float-right position-relative anime-view">
<!--                    <a href="<?php the_field('intro_sidebar_cta_link', $taxonomy.'_'.$term_id);?>" class="cta-block-sidebar grey-bg text-center float-left w-100 position-relative to-animate">-->
                        <a href="tel:<?php echo the_field('main_telephone_number','option');?>" class="cta-block-sidebar grey-bg text-center d-flex flex-wrap justify-content-center float-left w-100 position-relative to-animate">
                            <span class="title float-left w-100 position-relative"><?php the_field('intro_sidebar_cta_title', $taxonomy.'_'.$term_id);?></span>

                            <div class="content float-left w-100 position-relative">
                            <?php the_field('intro_sidebar_cta_text', $taxonomy.'_'.$term_id);?>
                            </div>

                            <span class="read-more position-relative"><?php the_field('intro_sidebar_cta_link_text', $taxonomy.'_'.$term_id);?></span>
                             <span class="read-more position-absolute">Call us on <?php echo the_field('main_telephone_number','option');?></span>
                            
                        </a>

                        <div class="sidebar-form sticky float-left w-100">
                            <div class="title-container float-left w-100 position-relative">
                                <span class="title float-left w-100 position-relative">Got a question about our training courses?</span>
                            </div>

                            <div class="form-container">
                                <?php echo do_shortcode('[contact-form-7 id="519" title="Training Course Sidebar Form"]');?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
		</div>
	</div>
</div>

<?php if(get_field('seo_block_content', $taxonomy.'_'.$term_id) || get_field('seo_block_title', $taxonomy.'_'.$term_id)){?>
<div class="container-fluid seo-content-block">
    <div class="container">
        <div class="row title-row seo-content-title">
            <div class="col-12 anime-scroll">
                <span class="title to-animate"><?php the_field('seo_block_title', $taxonomy.'_'.$term_id);?></span>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12 anime-scroll">
                <div class="content to-animate float-left w-100">
                <?php the_field('seo_block_content', $taxonomy.'_'.$term_id);?>
                </div>
            </div>
        </div>
		
		<div class="row ctas-list">
			<?php         
            // loop through the rows of data
            while ( have_rows('introduction_cta_blocks', '2') ) : the_row();?>

                <div class="col-12 col-md-6 col-lg-6 col-xl-3">
					<div class="cta-block-post float-left w-100 h-100 text-center">
						<div class="icon-container float-left w-100">
							<?php 
							$iconAttachmentId = get_sub_field('icon');
							$iconSize = 'iconSizeDouble';
							$iconImage = wp_get_attachment_image_src($iconAttachmentId, $iconSize); 
							?>
							<div class="image w-100 h-100 position-relative" style="background: url(<?php echo $iconImage[0];?>) no-repeat center center; background-size:contain;"></div>
						</div>
						
						<span class="title float-left w-100"><?php the_sub_field('title');?></span>
						
						<div class="content float-left w-100">
						<?php the_sub_field('content');?>
						</div>
					</div>
				</div>

            <?php   
            endwhile;
            ?>
		</div>
    </div>
</div>
<?php } ?>