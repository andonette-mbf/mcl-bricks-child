<div class="contact-page left">
	<span class="title">Get in touch with us</span>
	 <?php the_content();?>

	<span class="title">Our contact details</span>
	<div class="contact-meta">
    	<div class="meta-inner">
        	<i class="fa fa-phone"></i>
            <a href="tel:<?php echo the_field('main_telephone_number','option');?>">
            	<?php echo the_field('main_telephone_number','option');?>
            </a>
        </div>
        <div class="meta-inner">
        	<i class="fa fa-envelope"></i>
            <a href="mailto:<?php echo the_field('main_email_address','option');?>">
            	<?php echo the_field('main_email_address','option');?>
            </a>
        </div>
    	 <div class="meta-inner">
         	<i class="fa fa-home"></i>
            <a href="<?php echo the_field('google_map_link','option');?>" target="_blank" class="address">
            	<?php the_field('company_name', 'option'); ?>
            	<?php echo the_field('address','option');?>
            </a>
         </div>
                
        
        <ul class="social-media">
   			<?php
			   // loop through the rows of data
                while ( have_rows('social_media','option') ) : the_row();?>
            
                   <li>
                       <a href="<?php echo the_sub_field('social_media_link','option');?>" target="blank">
                            <i class="fa fa-<?php echo the_sub_field('social_media_type','option');?>"></i>
                        </a>
                    </li>
            
             <?php   
     			endwhile;
            ?>
        </ul>
    </div>   
    
    <div class="map-iframe-container">
<iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d75645.52864697108!2d-1.610754457423522!3d53.66617756751919!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2suk!4v1498222293802" width="100%" height="100%" frameborder="0" style="border:0" allowfullscreen></iframe>
    </div> 
</div>

<div class="contact-page right">
	<?php echo do_shortcode('[contact-form-7 id="166" title="Contact Page Contact Form"]');?>
</div>



 