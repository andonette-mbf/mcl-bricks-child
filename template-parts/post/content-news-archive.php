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
	<div class="news-post archive-news-post float-left w-100" id="news-post-<?php the_ID(); ?>">
    	<a href="<?php the_permalink();?>" class="post-thumbnail-outer float-left w-100">
			<div class="row">
				<div class="col-12 col-md-4">
					<div class="post-thumbnail-outer">
						<?php if(get_field('preview_thumbnail')){?>

							<?php 
							$fullAttachmentId = get_field('preview_thumbnail');
							$fullSize = 'mediumTile';
							$fullImage = wp_get_attachment_image_src($fullAttachmentId, $fullSize); 
							?>
							<img src="<?php echo $fullImage[0];?>" alt="<?php echo get_post_meta($fullAttachmentId, '_wp_attachment_image_alt', true) ?>"/>

						<?php } else { ?>

							<?php if (has_post_thumbnail( $post->ID ) ) { ?>
								<?php 
								$thumbnail_id = get_post_thumbnail_id( $post->ID ); 
								$alt = get_post_meta($thumbnail_id, '_wp_attachment_image_alt', true);
								$postImage = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'mediumTile');
								?>
								<img class="vertical" src="<?php echo $postImage[0];?>" alt="<?php echo $alt; ?>" />
							<?php } else { ?>
								<div class="vertical missing-img title">
									Sorry, we don't currently have an image for this post
								</div>
							<?php } ?>

						<?php } ?>
					</div>
				</div>
				
				<?php
				$category_names = array();
				//Get categories
				$categories = wp_get_post_terms($post->ID,'news_categories');
				foreach ($categories as $category) {
					$category_names[] = $category->name;	
				}?>
				
				<div class="col-12 col-md-8 align-self-center">
					<div class="post-content float-left w-100 position-relative">
						<h3 title="<?php the_title();?>"><?php the_title();?></h3>
						
						<?php if(has_excerpt()){?>
                        <div class="float-left w-100 content">
                            <?php the_excerpt();?>
                        </div>
                        <?php } ?>

						<div class="meta-bottom float-left w-100">
							<p class="float-left">Posted : <?php echo get_the_date( 'jS F Y' );?></p>
							<p class="float-left">Posted In : <?php echo implode(', ', $category_names);?></p>
						</div>
					</div>
				</div>
			</div>
        </a>
    </div>
</article>


