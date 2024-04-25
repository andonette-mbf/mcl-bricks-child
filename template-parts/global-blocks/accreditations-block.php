<div class="container-fluid accreditation-list">
	<div class="container">
    	<div class="row justify-content-center">
            <?php
            // loop through the rows of data
            while ( have_rows('accreditation_logos_top', 'option') ) : the_row();?>

                <div class="col-12 col-md-by5 anime-scroll">
                    <a href="<?php the_sub_field('link');?>" target="_blank" class="accred-logo to-animate">
                        <?php 
                        $accredAttachmentId = get_sub_field('select_image', 'option');
                        $accredSize = 'accreditationLogoSize';
                        $accredImage = wp_get_attachment_image_src($accredAttachmentId, $accredSize); 
                        ?>

                        <img src="<?php echo $accredImage[0];?>" class="vhboth w-auto h-auto" width="0" height="0" alt="<?php echo get_post_meta($accredAttachmentId, '_wp_attachment_image_alt', true) ?>"/>
                    </a>
                </div>

             <?php   
            endwhile;
            ?>
        </div>
        
    	<div class="row justify-content-center">
            <?php
            // loop through the rows of data
            while ( have_rows('accreditation_logos', 'option') ) : the_row();?>

                <div class="col-12 col-md-by5 anime-scroll">
                    <div class="accred-logo to-animate">
                        <?php 
                        $accredAttachmentId = get_sub_field('select_image', 'option');
                        $accredSize = 'accreditationLogoSize';
                        $accredImage = wp_get_attachment_image_src($accredAttachmentId, $accredSize); 
                        ?>

                        <img src="<?php echo $accredImage[0];?>" class="vhboth w-auto h-auto" width="0" height="0" alt="<?php echo get_post_meta($accredAttachmentId, '_wp_attachment_image_alt', true) ?>"/>
                    </div>
                </div>

             <?php   
            endwhile;
            ?>
        </div>
    </div>
</div>