<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.2
 */

?>

<article class="col-12 to-animate">
	<div class="centre-post float-left w-100 position-relative" id="centre-post-<?php the_ID(); ?>">
		<a href="<?php the_permalink();?>" class="post-thumbnail-outer float-left position-absolute">
			<div class="post-thumbnail-outer float-left w-100 h-100">
				<?php if(get_field('preview_thumbnail')){?>

					<?php 
					$fullAttachmentId = get_field('preview_thumbnail');
					$fullSize = 'mediumTile';
					$fullImage = wp_get_attachment_image_src($fullAttachmentId, $fullSize); 
					?>
					<div class="image float-left w-100 h-100" style="background: url(<?php echo $fullImage[0];?>) no-repeat center center; background-size: cover;"></div>

				<?php } else { ?>

					<?php if (has_post_thumbnail( $post->ID ) ) { ?>
				
						<?php 
						$thumbnail_id = get_post_thumbnail_id( $post->ID ); 
						$alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
						$postImage = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'mediumTile');
						?>
						<div class="image float-left w-100 h-100" style="background: url(<?php echo $postImage[0];?>) no-repeat center center; background-size: cover;"></div>
				
					<?php } else { ?>
						<div class="vertical missing-img title">
							Sorry, we don't currently have an image for this post
						</div>
					<?php } ?>

				<?php } ?>
			</div>
		</a>
		
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
            
            <a href="<?php the_permalink();?>" class="cta-button float-right">View Centre</a>
        </div>
    </div>
</article>
