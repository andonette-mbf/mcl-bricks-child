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
$id = 'grid-gallery-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'grid-gallery-block';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
// Load values and assign defaults.
$title = get_field('title');
$images = get_field('gallery');
$imageCount = count( $images );
?>

<div class="container-fluid development-gallery <?php echo esc_attr($className); ?>" id="<?php echo esc_attr($id); ?>">
	<div class="container">
        <?php if(!empty($title)) { ?>
    	<div class="row title-row">
        	<div class="col-12">
            	<span class="title"><?php echo $title;?></span>
            </div>
        </div>
        <?php } ?>
        
        <div class="row">
            <div class="col-12">
                <div class="image-slider-top">
                    <?php foreach( $images as $image ): ?>
                        
                        <?php $galleryImage = wp_get_attachment_image_src($image, 'lightboxImageSize');?>
                        <div class="image-block">
                            <img src="<?php echo $galleryImage[0];?>" alt="<?php echo get_post_meta($image, '_wp_attachment_image_alt', true) ?>"/>
                        </div>
                        
                    <?php endforeach; ?>
                </div>
            
                <div class="image-slide-bottom">
                    <div class="row">
                
                    <?php
                    $imageCounter = 0;
                    foreach( $images as $image ):
                    $imageCounter++;
                    ?>
                        
                        <div class="col-12 col-md-2">
                            <?php $galleryImage = wp_get_attachment_image_src($image, 'lightboxImageSize');?>
                            <a href="#" data-image-slide="<?php echo $imageCounter;?>" class="image-block">
                                <img src="<?php echo $galleryImage[0];?>" alt="<?php echo get_post_meta($image, '_wp_attachment_image_alt', true) ?>"/>
                            </a>
                        </div>
                    
                    <?php endforeach; ?>
                    
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>