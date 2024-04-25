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


global $product;
?>

<?php if(is_product() || is_product_category() || is_ajax()){?>
<article class="col-12 col-md-4 col-lg-3 to-animate">
<?php } else { ?>
<article class="col-12 col-md-6 col-lg-4 to-animate">
<?php } ?>

	<div class="product-post float-left w-100 h-100 position-relative" id="product-post-<?php the_ID(); ?>">
    	<a href="<?php the_permalink();?>" class="post-thumbnail-outer float-left w-100 position-relative">
            <div class="post-thumbnail-outer float-left w-100 position-relative">
                <?php if(get_field('preview_thumbnail')){?>
                	
                    <?php 
					$fullAttachmentId = get_field('preview_thumbnail');
					$fullSize = 'mediumTileUncropped';
					$fullImage = wp_get_attachment_image_src($fullAttachmentId, $fullSize); 
					?>
					<img class="vhboth" src="<?php echo $fullImage[0];?>" alt="<?php echo get_post_meta($fullAttachmentId, '_wp_attachment_image_alt', true) ?>"/>
                    
                <?php } else { ?>
                
                    <?php if (has_post_thumbnail( $post->ID ) ) { ?>
                        <?php 
                        $thumbnail_id = get_post_thumbnail_id( $post->ID ); 
                        $alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
                        $postImage = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'mediumTileUncropped');
                        ?>
                        <img class="vhboth" src="<?php echo $postImage[0];?>" alt="<?php echo $alt; ?>" />
                    <?php } else { ?>
                        <div class="vertical missing-img title">
                            Sorry, we don't currently have an image for this post
                        </div>
                    <?php } ?>
                
                <?php } ?>
            </div>
        
            <div class="post-content float-left w-100 position-relative">
                <h3 class="float-left w-100 position-relative" title="<?php the_title();?>"><?php the_title();?></h3>
                
                <p class="price title float-left w-100 position-relative"><?php echo $product->get_price_html();?></p>
            </div>
            
            <span class="cta-button secondary float-right">Find out more</span>
        </a>
    </div>
</article>


