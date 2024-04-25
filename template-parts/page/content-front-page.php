<?php
/**
 * Displays content for front page
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.0
 */

?>

<?php 
if(current_user_can('administrator')){
    include( get_stylesheet_directory() . '/template-parts/post/content-slider-new.php');
} else {
    include( get_stylesheet_directory() . '/template-parts/post/content-slider.php');
}
?>

<div class="container-fluid intro-home-block">
	<div class="container">
	    <div class="row title-row center-title">
            <div class="col-12 anime-scroll">
                <span class="title to-animate"><?php the_field('introduction_title');?></span>
            </div>
        </div>
    	<div class="row intro-title-content">
            <div class="col-0 col-md-2">
            </div>
            <div class="col-12 col-md-8">
                <div class="content float-left w-100 text-center">
				<?php the_field('introduction_content');?>
				</div>
            </div>
            <div class="col-0 col-md-2">
            </div>
        </div>
		
		<div class="row ctas-list">
			<?php         
            // loop through the rows of data
            while ( have_rows('introduction_cta_blocks') ) : the_row();?>

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

<div class="container-fluid post-list featured-cats-listing">
	<div class="container">
        <div class="row title-row">
            <div class="col-12 anime-scroll">
                <span class="title to-animate">Our featured training courses</span>
				
				<a href="<?php echo get_term_link('training-courses', 'product_cat');?>" class="float-right title-link">All training courses</a>
            </div>
        </div>
		<div class="featured-courses row anime-scroll no-padding">
			<?php         
			// loop through the rows of data
			while ( have_rows('featured_courses') ) : the_row();?>

				<?php
					$id = get_sub_field('select_course');
					$temp = $post;
					$post = get_post( $id );
					setup_postdata( $post );
				?>

					<?php include( get_stylesheet_directory() . '/template-parts/post/content-training-featured.php');?>

				<?php
					wp_reset_postdata();
					$post = $temp;
				?>

			<?php   
			endwhile;
			?>
		</div>
    </div>
</div>

<?php include( get_stylesheet_directory() . '/template-parts/global-blocks/partners-logos-block.php');?>

<?php         
// loop through the rows of data
while ( have_rows('homepage_split_blocks') ) : the_row();

//Capture data for rows
$contentImageAttachmentId = get_sub_field('split_image');
$imagePosition = get_sub_field('image_position');
?>

<div class="container-fluid <?php echo $imagePosition;?>-image image-content-block">
    <div class="container">
        <div class="row <?php if($imagePosition == "right"){?>flex-row-reverse<?php } ?>">
			<div class="col-12 col-md-5 anime-scroll">
                <div class="content-image-container to-animate float-left w-100 position-relative">
                    <?php $contentImage = wp_get_attachment_image_src($contentImageAttachmentId, 'largeTile');?>
                    <img class="w-100 h-auto" width="0" height="0" src="<?php echo $contentImage[0];?>" alt="<?php echo get_post_meta($contentImageAttachmentId, '_wp_attachment_image_alt', true) ?>"/>
                </div>
            </div>
            
			<div class="col-12 col-md-7 align-self-center">
                <div class="column-content-text anime-scroll float-left w-100 position-relative">
                	<?php if(get_sub_field('split_title')){?>
                	<span class="title float-left to-animate w-100 position-relative"><?php the_sub_field('split_title');?></span>
                    <?php } ?>
                    
                    <div class="content to-animate float-left w-100 position-relative">
                    <?php the_sub_field('split_content');?>
                    </div>
                    
					<?php
                    // check if the repeater field has rows of data
                    if( have_rows('split_buttons') ):?>
                    <div class="buttons to-animate">
                        <?php
                        // loop through the rows of data
                        $buttonCount = 0;
                        while ( have_rows('split_buttons') ) : the_row();
                        $buttonCount++;
						?>
                        
                            <a href="<?php the_sub_field('button_url');?>" <?php if($buttonCount % 2){?>class="cta-button"<?php } else { ?>class="cta-button secondary"<?php } ?>><?php the_sub_field('button_text');?></a>
                        
                        <?php endwhile;?>
                    </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php   
endwhile;
?>

<?php include( get_stylesheet_directory() . '/template-parts/global-blocks/training-centres-block.php');?>

<?php include( get_stylesheet_directory() . '/template-parts/global-blocks/latest-news-block.php');?>

<?php include( get_stylesheet_directory() . '/template-parts/global-blocks/accreditations-block.php');?>