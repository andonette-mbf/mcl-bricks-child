<?php
/**
 * Template part for displaying posts
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Seventeen
 * @since 1.0
 * @version 1.2
 */

?>

<?php if(is_product()){?>

<article class="col-12 col-md-6 to-animate">

<?php } else { ?>

<article class="col-12 to-animate">

<?php } ?>

	<div class="training-post float-left w-100 position-relative" id="training-post-<?php the_ID(); ?>">
        <div class="post-content float-right position-relative">
            <h3 class="float-left w-100 position-relative" title="<?php the_title();?>"><a href="<?php the_permalink();?>"><?php the_title();?></a></h3>
            
			<div class="inner-content float-left w-100 position-relative">
				<?php if(get_field('attendee_requirements')){?>
				<div class="float-left content requirments">
					<b>Attendee Requirements : </b>
					<?php the_field('attendee_requirements');?>
				</div>
				<?php } ?>

				<?php if(get_field('course_duration')){?>
				<div class="float-right content duration">
					<b>Course Duration : </b>
					<p><?php the_field('course_duration');?></p>
				</div>
				<?php } ?>
			</div>
            
            <a href="<?php the_permalink();?>" class="cta-button float-right">View Course Details</a>
        </div>
    </div>
</article>
