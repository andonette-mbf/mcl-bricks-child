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
$id = 'full-width-image-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'full-width-image';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$fullAttachmentId = get_field('image');
?>

<?php
$innercols = '<div class="col-12">';
?>

<div class="container-fluid flexible-row-block <?php echo esc_attr($className); ?>" id="<?php echo esc_attr($id); ?>">
	<div class="container">
        <div class="row">
            <?php echo $innercols;?>
                <div class="image-container">
					<?php $fullImage = wp_get_attachment_image_src($fullAttachmentId, 'fullWidthHeaderImage');?>
                    <img src="<?php echo $fullImage[0];?>" alt="<?php echo get_post_meta($fullAttachmentId, '_wp_attachment_image_alt', true) ?>"/>
                </div>
            </div>
        </div>
    </div>
</div>