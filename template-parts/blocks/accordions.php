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
$id = 'accordions-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'accordions-section';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$title = get_field('title') ?: 'Your Title Here...';
?>

<?php
$innercols = '<div class="col-12">';
?>

<div class="container-fluid flexible-row-block <?php echo esc_attr($className); ?>" id="<?php echo esc_attr($id); ?>">
    <div class="container">
    	<?php if(!empty($title)) { ?>
    	<div class="row title-row accordions-title">
        	<?php echo $innercols;?>
            	<span class="title"><?php echo $title;?></span>
            </div>
        </div>
        <?php } ?>
        <div class="row">
        	<?php echo $innercols;?>
                <div class="accordions">
                
                    <?php         
                    // loop through the rows of data
                    while ( have_rows('accordions') ) : the_row();?>
                
                    <div class="accordion-section">
                        <div class="acc-title">
                            <?php the_sub_field('title');?>
                        </div>
                       
                        <div class="acc-section content">
                            <?php the_sub_field('content');?>
                        </div>
                    </div>
                    
                    <?php   
                    endwhile;
                    ?>
                   
                </div>
            </div>
        </div>
    </div>
</div>