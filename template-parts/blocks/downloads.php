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
$className = 'downloads-section';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$title = get_field('title') ?: 'Your Title Here...';
?>

<div class="container-fluid flexible-row-block <?php echo esc_attr($className); ?>" id="<?php echo esc_attr($id); ?>">
    <div class="container">
    	<?php if(!empty($title)) { ?>
    	<div class="row title-row accordions-title">
        	<div class="col-12">
            	<span class="title"><?php echo $title;?></span>
            </div>
        </div>
        <?php } ?>
        <div class="row">
        	<?php
            // loop through the rows of data
            while ( have_rows('downloads') ) : the_row();?>

                <article class="col-12">
                    <div class="download-post">

                        <?php
                        $attachment_id = get_sub_field('file');
                        $url = wp_get_attachment_url( $attachment_id );
                        $title = get_the_title( $attachment_id );

                        // part where to get the filesize
                        $filesize = filesize( get_attached_file( $attachment_id ) );
                        $filesize = size_format($filesize);
                        $fileextension = pathinfo($url, PATHINFO_EXTENSION);
                        $filename = basename($url);

                        // show custom field
                        if (get_sub_field('file')): ?>

                        <a href="<?php echo $url;?>" target="_blank">
                            <span class="download-icon <?php echo $fileextension;?>"></span>

                            <div class="download-content">
                                <span class="main-title"><?php the_sub_field('title');?></span>
                                <span class="sub-title"><?php the_sub_field('sub_title');?></span>
                            </div>

                            <div class="download-meta title">
                                <span>Filesize : <?php echo $filesize;?></span>
                                <span>Filetype : <?php echo $fileextension;?></span>
                            </div>

                            <div class="cta-button">
                                <i class="fas fa-download"></i>
                            </div>
                        </a>

                        <?php endif; ?>
                </article>

            <?php   
            endwhile;
            ?>
        </div>
    </div>
</div>