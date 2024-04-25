<div class="container-fluid post-list latest-news-block">
	<div class="container">
        <div class="row title-row">
            <div class="col-12 anime-scroll">
                <span class="title to-animate">Latest news and updates</span>
				
				<a href="<?php echo get_term_link('scafftalks', 'news_categories');?>" class="float-right title-link">ScaffTALK Updates</a>
				<a href="<?php echo get_post_type_archive_link( 'news' ); ?>" class="float-right title-link">All News</a>
            </div>
        </div>
    	<div class="row anime-scroll">
            <?php
            global $post;
            $args = array( 'posts_per_page' => 2, 'orderby' => 'date', 'order' => 'DESC', 'post_type' => 'news' );

            $myposts = get_posts( $args );
            foreach ( $myposts as $post ) : setup_postdata( $post ); ?>

                <?php include( get_stylesheet_directory() . '/template-parts/post/content-news.php');?>

            <?php endforeach; 
            wp_reset_postdata();?>
        </div>
    </div>
</div>