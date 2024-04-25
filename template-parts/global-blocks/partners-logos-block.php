<div class="container-fluid partners-logo-block">
	<div class="container">
        <div class="row title-row">
            <div class="col-12 anime-scroll">
                <span class="title white to-animate">We’re proud to have worked with...</span>
            </div>
        </div>
    	<div class="row justify-content-center">
            <?php
            // loop through the rows of data
            while ( have_rows('partner_logos', 'option') ) : the_row();?>

                <div class="col-12 col-md-by5 anime-scroll">
                    <div class="partner-logo to-animate">
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