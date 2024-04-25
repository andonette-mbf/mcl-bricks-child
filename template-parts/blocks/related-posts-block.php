<?php

/**
 * Content Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'related-posts-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'related-posts-section';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$title = get_field('title') ?: 'Latest news from St. Modwens Homes';
?>

<div class="container-fluid post-list <?php echo esc_attr($className); ?>" id="<?php echo esc_attr($id); ?>">
	<div class="container">
    	<?php if(!empty($title)) { ?>
    	<div class="row title-row accordions-title">
        	<div class="col-12">
            	<span class="title"><?php echo $title;?></span>
            </div>
        </div>
        <?php } ?>
    	<div class="row">
        
        	<div class="news-slider">
                
                <?php if( have_rows('custom_posts') ) { ?>
                    
                    <?php         
                    // loop through the rows of data
                    while ( have_rows('custom_posts') ) : the_row();?>
                    
                        <?php
                            $id = get_sub_field('select_post');
                            $temp = $post;
                            $post = get_post( $id );
                            setup_postdata( $post );
                        ?>
                        
                            <?php include( get_stylesheet_directory() . '/template-parts/post/content-news.php');?>
                            
                        <?php
                            wp_reset_postdata();
                            $post = $temp;
                        ?>
                    
                    <?php   
                    endwhile;
                    ?>
                
                <?php } else { ?>
            
                <?php
                global $post;
                $args = array( 'posts_per_page' => 3, 'orderby' => 'date', 'order' => 'DESC', 'post_type' => 'news' );
                
                $myposts = get_posts( $args );
                foreach ( $myposts as $post ) : setup_postdata( $post ); ?>
                
                    <?php include( get_stylesheet_directory() . '/template-parts/post/content-news.php');?>
                    
                <?php endforeach; 
                wp_reset_postdata();?>
                
                <?php } ?>
            
            </div>
            
        </div>
    </div>
</div>