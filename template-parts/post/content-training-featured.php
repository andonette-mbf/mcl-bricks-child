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
<article class="col-12 col-md-4 to-animate">
	<div class="training-post-featured float-left w-100 h-100 position-relative" id="training-post-<?php the_ID(); ?>">
    	<a href="<?php the_permalink();?>" class="post-thumbnail-outer float-left position-relative">
            <div class="post-thumbnail-outer float-left w-100 h-100 position-absolute">
                <?php if(get_field('preview_thumbnail')){?>
                	
                    <?php 
					$fullAttachmentId = get_field('preview_thumbnail');
					$fullSize = 'xtraLargeTile';
					$fullImage = wp_get_attachment_image_src($fullAttachmentId, $fullSize); 
					?>
					<div class="image w-100 h-100 position-relative" style="background: url(<?php echo $fullImage[0];?>) no-repeat center center; background-size:cover;"></div>
                    
                <?php } else { ?>
                
                    <?php if (has_post_thumbnail( $post->ID ) ) { ?>
                        <?php 
                        $thumbnail_id = get_post_thumbnail_id( $post->ID ); 
                        $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                        $postImage = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'xtraLargeTile');
                        ?>
						<div class="image w-100 h-100 position-relative" style="background: url(<?php echo $postImage[0];?>) no-repeat center center; background-size:cover;"></div>
                    <?php } else { ?>
                        <div class="vertical missing-img title">
                            Sorry, we don't currently have an image for this post
                        </div>
                    <?php } ?>
                
                <?php } ?>
            </div>
			
			<div class="post-content float-right position-relative w-100 h-100">
				<div class="inner position-relative h-100 w-100 d-flex flex-wrap align-items-start flex-column">
					<h3 class="float-left w-100 position-relative" title="<?php the_title();?>"><?php the_title();?></h3>

					<div class="content float-left w-100 position-relative">
						<p><?php echo get_the_excerpt($post->ID); ?></p>
					</div>

					<span class="cta-button">Make a booking</span>
				</div>
			</div>
        </a>
    </div>
</article>
