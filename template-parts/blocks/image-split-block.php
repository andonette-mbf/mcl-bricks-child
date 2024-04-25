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
$id = 'image-split-block-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'image-content-block';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$contentImageAttachmentId = get_field('split_image');
$imagePosition = get_field('image_position');
?>

<div class="container-fluid flexible-row-block <?php echo $imagePosition;?>-image <?php echo esc_attr($className); ?>" id="<?php echo esc_attr($id); ?>">
    <div class="container">
        <div class="row <?php if($imagePosition == "right"){?>flex-row-reverse<?php } ?>">
        	
            <div class="col-12 col-md-6">
                <div class="content-image-container">
                    <?php $contentImage = wp_get_attachment_image_src($contentImageAttachmentId, 'largeTile');?>
                    <img src="<?php echo $contentImage[0];?>" alt="<?php echo get_post_meta($contentImageAttachmentId, '_wp_attachment_image_alt', true) ?>"/>
                </div>
            </div>
        
            <div class="col-12 col-md-6">
                <div class="column-content-text content vertical">
                	<?php if(get_field('split_title')){?>
                	<span class="title"><?php the_field('split_title');?></span>
                    <?php } ?>
                    
                    <?php the_field('split_content');?>
                </div>
            </div>
            
        </div>
    </div>
</div>