  
<?php if( get_field('full_width_background_image') == 'no' ) { ?>
     <div class="header-post-width">
     	<?php $singleImage = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'fullSizeLargeTileLandscape' ); ?>
     	<img class="horizontal" src="<?php echo $singleImage[0];?>" alt="<?php echo get_post_meta($post->ID, '_wp_attachment_image_alt', true) ?>" />
     </div>
<?php } ?>

<?php //the_content();?>

<?php if( get_field('testimonial_name') ): ?>
 	<div class="testimonials-single-meta">
         <b><?php the_field('testimonial_name');?>, <?php the_field('testimonial_position');?></b>
         <b><a href="<?php echo the_field('testimonial_company_url');?>" rel="external" target="_blank"><?php the_field('testimonial_company');?></a></b>
    </div>	
<?php endif; ?>
 
<div class="post-navigation title">
	<div class="col-6 no-padding" style="text-align:left;">
		<div class="meta" style="float:left;">
			<b>previous</b>
			<?php
			$prev_post = get_previous_post();
			if (!empty( $prev_post )) { ?>
				<a class="title" rel="prev" href="<?php echo $prev_post->guid ?>"><?php echo $prev_post->post_title ?></a>
			<?php } else { echo "<p>Sorry, we don't have any more posts</p>"; } ?>
		</div>
	</div>
                
	<div class="col-6 no-padding" style="text-align:right;">
		<div class="meta" style="float:right;">
			<b>next</b>
			<?php
			$next_post = get_next_post();
			if (!empty( $next_post )) { ?>
				<a class="title" rel="next" href="<?php echo $next_post->guid ?>"><?php echo $next_post->post_title; ?></a> 
			<?php } else { echo "<p>Sorry, we don't have any more posts</p>"; } ?>
		</div>
	</div>
</div>