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
$id = 'form-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'form-block';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$title = get_field('title');
$form = get_field('form');
?>

<?php
$innercols = '<div class="col-12">';
?>

<div class="container-fluid flexible-row-block <?php echo esc_attr($className); ?>" id="<?php echo esc_attr($id); ?>">
    <div class="container">
    	<?php if(!empty($title)) { ?>
    	<div class="row title-row">
        	<?php echo $innercols;?>
            	<span class="title"><?php echo $title;?></span>
            </div>
        </div>
        <?php } ?>
        <div class="row">
            <?php echo $innercols;?>
                <div class="form-block" id="form-id-<?php echo $form;?>">
					<?php echo do_shortcode('[contact-form-7 id="'.$form.'"]');?> 
				</div>
            </div>
        </div>
    </div>
</div>