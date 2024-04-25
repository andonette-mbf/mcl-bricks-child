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
$id = 'blockquote-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'blockquote-block';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$text = get_field('content') ?: 'Your Content Here...';
$meta = get_field('meta');
?>

<?php
$innercols = '<div class="col-12">';
?>

<div class="container-fluid flexible-row-block standard-post <?php echo esc_attr($className); ?>" id="<?php echo esc_attr($id); ?>">
    <div class="container">
        <div class="row">
        	<?php echo $innercols;?>
                <article class="standard-post-content no-sidebar content">
                    <blockquote>
						<?php echo $text;?>
                    </blockquote>
                </article>
            </div>
            <div class="col-0 col-md-1"></div>
        </div>
    </div>
</div>