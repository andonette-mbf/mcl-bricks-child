<div class="container-fluid accreditation-list">
	<div class="container">
    	<div class="row">
        	<div class="accredications-list-inner">
   				<?php
                        
                // loop through the rows of data
                while ( have_rows('accreditations__associated_logos', 'option') ) : the_row();?>
                	
                    <div class="col-12 col-md-3 col-lg-2">
                    
					   <?php 
                        $accredAttachmentId = get_sub_field('image', 'option');
                        $accredSize = 'accreditationLogoSize';
                        $accredImage = wp_get_attachment_image_src($accredAttachmentId, $accredSize); 
                       ?>
                    
                    	<a class="accred-logo-contain" rel="external" href="<?php echo the_sub_field('link_if_applicable','option');?>" target="_blank">
                        	<img src="<?php echo $accredImage[0];?>" class="vhboth" alt="<?php echo get_post_meta($accredAttachmentId, '_wp_attachment_image_alt', true) ?>"/>
                        </a>
                    </div>
            
				 <?php   
         		endwhile;
                ?>
            </div>
        </div>
    </div>
</div>