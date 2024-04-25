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

<?php
//Check if the parent category is training courses to show seperate layout
$parentcats = get_ancestors($term_id, 'product_cat');
?>

<article class="col-12 col-md-6 col-lg-4 to-animate">
	<div class="cat-post-featured float-left w-100 position-relative" id="cat-post-<?php echo $term_id;?>">
    	<a href="<?php echo get_term_link($term_id, 'product_cat')?>" class="post-thumbnail-outer float-left position-relative">
            <div class="post-thumbnail-outer float-left w-100 h-100 position-absolute">
                <?php 
                // get category image
                $thumbnail_id = get_woocommerce_term_meta( $sub_term->term_id, 'thumbnail_id', true ); 
                $postImage = wp_get_attachment_image_src( $thumbnail_id, 'mediumTileUncropped');
                ?>
                
                <?php if(!empty($postImage)){?>
                <div class="image float-left w-100 h-100" style="background: url(<?php echo $postImage[0];?>) no-repeat center center; background-size: cover;"></div>
                <?php } else { ?>
                <div class="vertical w-100 h-100 missing-img title">
                    Sorry, we don't currently have an image for this post
                </div>
                <?php } ?>
            </div>
			
			<div class="post-content float-right position-relative w-100 h-100">
				<div class="inner position-relative h-100 w-100">
					<h3 class="float-left w-100 position-relative" title="<?php echo $sub_term->name;?>"><?php echo $sub_term->name;?></h3>

					<?php /*?><div class="content float-left w-100 position-relative">
						<p><?php echo $sub_term->description;?></p>
					</div><?php */?>

					<span class="cta-button">See our courses</span>
				</div>
			</div>
        </a>
    </div>
</article>