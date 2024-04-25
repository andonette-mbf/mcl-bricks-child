<?php $current_post = get_post($current_post_id);?>

<div class="container-fluid modal-container-block" id="other-dates">
	<div class="container">
    	<div class="row">
        	<div class="col-12">
            	<div class="modal-inner-block vhboth">
					<div class="modal-inner-main">
						<div class="title-container">
							<span class="title">Require Different Dates?</span>
							<a href="#" class="close-modal fal fa-times"></a>
						</div>

						<div class="form-container">
						<?php echo do_shortcode('[contact-form-7 id="8101" title="Modal Form - Other Dates"]');?>
						</div>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">

	jQuery(document).ready(function(){
		var currentPageTitle = "<?php echo get_the_title($current_post_id);?>";
		var currentPageLink = "<?php echo get_permalink($current_post_id);?>";
		var currentLocation = "<?php echo get_field('select_address', $current_post_id);?>";
		jQuery('#other-dates .modal-inner-block input#currentPage').attr('value', currentPageTitle);
		jQuery('#other-dates .modal-inner-block input#currentPageLink').attr('value', currentPageLink);
		jQuery('#other-dates .modal-inner-block input#currentLocation').attr('value', currentLocation);
    });

</script>