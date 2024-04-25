<div class="global-sidebar float-right anime-view">
    <aside class="sidebar-section share-block float-left w-100 to-animate">
        <span>Do you like this post? <br>Why not share it?</span>

        <?php echo do_shortcode('[social_share]');?>
    </aside>

    <aside class="sidebar-section search-form float-left w-100 to-animate">
        <b class="float-left w-100">Search our posts</b>

        <div class="search-form-main float-left w-100">
            <form role="search" method="get" class="search-form float-left w-100" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                <input type="search" id="<?php echo $unique_id; ?>" class="search-field float-left w-100" placeholder="<?php echo esc_attr_x( 'Search for something &hellip;', 'placeholder', 'twentyseventeen' ); ?>" value="<?php echo get_search_query(); ?>" name="s" />

                <button type="submit" class="search-submit"><i class="fal fa-search"></i></button>
            </form>
        </div>
    </aside>

    <aside class="sidebar-section recent-posts-sidebar float-left w-100 to-animate">
        <span class="title float-left w-100">Latest Posts</span>
        <div class="posts-inner float-left w-100">

            <?php
            global $post;
            $args = array( 'post_status' => 'publish', 'posts_per_page' => 3, 'post_type' => 'news', 'orderby' => 'date', 'order' => 'DESC', 'post__not_in' => array($post->ID));
            $myposts = get_posts( $args );
            foreach ( $myposts as $post ) : setup_postdata( $post ); ?>

                <?php include( get_stylesheet_directory() . '/template-parts/post/content-sidebar-post.php');?>

            <?php endforeach; 
            wp_reset_postdata();?>

        </div>
    </aside>
</div>