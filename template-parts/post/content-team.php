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

<article class="col">
	<div class="team-post float-left w-100 position-relative" id="team-post-<?php the_ID(); ?>">
    	<a href="<?php echo get_permalink($post->ID);?>" class="post-thumbnail-outer float-left w-100 trueSquare">
            <div class="post-thumbnail-outer float-left w-100 h-100">
                <?php if(get_field('preview_thumbnail', $post->ID)){?>
                	
                    <?php 
					$fullAttachmentId = get_field('preview_thumbnail', $post->ID);
					$fullSize = 'largeTile';
					$fullImage = wp_get_attachment_image_src($fullAttachmentId, $fullSize); 
					?>
					<img src="<?php echo $fullImage[0];?>" alt="<?php echo get_post_meta($fullAttachmentId, '_wp_attachment_image_alt', true) ?>"/>
                    
                <?php } else { ?>
                
                    <?php if (has_post_thumbnail( $post->ID ) ) { ?>
                        <?php 
                        $thumbnail_id = get_post_thumbnail_id( $post->ID ); 
                        $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                        $postImage = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'largeTile');
                        ?>
                        <img class="vertical" src="<?php echo $postImage[0];?>" alt="<?php echo $alt; ?>" />
                    <?php } else { ?>
                        <div class="vertical missing-img title">
                            Sorry, we don't currently have an image for this post
                        </div>
                    <?php } ?>
                
                <?php } ?>
            </div>
        </a>
        
        <div class="post-content float-left w-100">
            <?php if(get_field('email', $post->ID)){?><a href="mailto:<?php echo get_field('email', $post->ID);?>" class="fal fa-envelope"></a><?php } ?>
            
            <h3 class="float-left w-100"><a href="<?php echo get_permalink($post->ID);?>"><?php echo get_the_title($post->ID);?> <?php if(get_field('position', $post->ID)){?><br><strong><?php echo get_field('position', $post->ID);?></strong><?php } ?></a></h3>

            <a href="<?php echo get_permalink($post->ID);?>" class="cta-button secondary">Read More</a>
        </div>
    </div>
</article>


