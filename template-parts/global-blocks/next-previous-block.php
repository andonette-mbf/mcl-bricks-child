<div class="container-fluid news-previous-posts">
	<div class="container">
    	<div class="row next-posts">
			<div class="nav-posts prev col-12 col-sm-6 col-md-6 d-flex justify-content-start flex-wrap text-left align-items-start align-content-start">
				<span>Previous Post</span>
				<?php
				$prev_post = get_previous_post();
				if (!empty( $prev_post )) { ?>
				<a href="<?php echo get_permalink( $prev_post->ID ); ?>"><?php echo $prev_post->post_title ?></a>
				<?php } else { echo "<p>Sorry, no posts to show</p>"; } ?>
			</div>

			<div class="nav-posts next col-12 col-sm-6 col-md-6 d-flex justify-content-end flex-wrap text-right align-items-start align-content-start">
				<span>Next Post</span>
				<?php
				$next_post = get_next_post();
				if (!empty( $next_post )) { ?>
				<a href="<?php echo get_permalink( $next_post->ID ); ?>"><?php echo $next_post->post_title; ?></a>
				<?php } else { echo "<p>Sorry, no posts to show</p>"; } ?>
			</div>
        </div>
    </div>
</div>