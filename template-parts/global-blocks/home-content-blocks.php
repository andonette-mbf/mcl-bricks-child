<?php         
// loop through the rows of data
while ( have_rows('homepage_split_blocks', '2') ) : the_row();

//Capture data for rows
$contentImageAttachmentId = get_sub_field('split_image');
$imagePosition = get_sub_field('image_position');
?>

<div class="container-fluid <?php echo $imagePosition;?>-image image-content-block">
    <div class="container">
        <div class="row <?php if($imagePosition == "right"){?>flex-row-reverse<?php } ?>">
			<div class="col-12 col-md-5 anime-scroll">
                <div class="content-image-container to-animate float-left w-100 position-relative">
                    <?php $contentImage = wp_get_attachment_image_src($contentImageAttachmentId, 'largeTile');?>
                    <img src="<?php echo $contentImage[0];?>" alt="<?php echo get_post_meta($contentImageAttachmentId, '_wp_attachment_image_alt', true) ?>"/>
                </div>
            </div>
            
			<div class="col-12 col-md-7 align-self-center">
                <div class="column-content-text anime-scroll float-left w-100 position-relative">
                	<?php if(get_sub_field('split_title')){?>
                	<span class="title float-left to-animate w-100 position-relative"><?php the_sub_field('split_title');?></span>
                    <?php } ?>
                    
                    <div class="content to-animate float-left w-100 position-relative">
                    <?php the_sub_field('split_content');?>
                    </div>
                    
					<?php
                    // check if the repeater field has rows of data
                    if( have_rows('split_buttons') ):?>
                    <div class="buttons to-animate">
                        <?php
                        // loop through the rows of data
                        $buttonCount = 0;
                        while ( have_rows('split_buttons') ) : the_row();
                        $buttonCount++;
						?>
                        
                            <a href="<?php the_sub_field('button_url');?>" <?php if($buttonCount % 2){?>class="cta-button"<?php } else { ?>class="cta-button secondary"<?php } ?>><?php the_sub_field('button_text');?></a>
                        
                        <?php endwhile;?>
                    </div>
                    <?php endif;?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php   
endwhile;
?>