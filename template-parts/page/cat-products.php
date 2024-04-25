

<div class="container-fluid product-list post-type-archive-product">
    <div class="container">
        <form class="filterable_search" style="position: relative;">
            <div class="row">
                <input type="hidden" name="post_types[]" value="product" /> <!-- At least one must be defined -->
                <input type="hidden" name="posts_per_page" value="12" /> <!-- Make sure this is the same as your archive or current page -->
                <input type="hidden" name="sort_by" value="title" /> <!-- Make sure this matches your default sort on page load -->
                <input type="hidden" name="sort_dir" value="asc" /> <!-- Make sure this matches your default sort order on page load -->
                
                <!-- Pass in current product category -->
                <?php if (isset($term) && $term !== "") {?>
                <input type="hidden" name="default_tax[product_cat][]" value="<?php echo $term->term_id; ?>" />
                <?php } ?>

                <!-- Leave as below -->
                <input type="hidden" name="append" value="false" />
                <input type="hidden" name="paged" value="1" />

                <input type="hidden" name="template_override" value="template-parts/post/content-product.php" />
                
                 <!-- Filter Options -->
                <?php
                global $wp_query;
                
                //Get existing args, and get all posts
                $filter_args = $wp_query->query;
                $filter_args['posts_per_page'] = -1;
                
                //Requery
                $filterable_search_query = new WP_Query($filter_args);
                ?>

                <div class="col-12">
                    <div class="row anime-view" id="filterable_search_results">
                        <?php 
                        if ( have_posts() ) :
                        while ( have_posts() ) : the_post();
                            include( get_stylesheet_directory() . '/template-parts/post/content-product.php');
                        endwhile;
                        else :
                            include( get_stylesheet_directory() .'/template-parts/post/content-none.php' );
                        endif;
                        ?>
                    </div>

                    <div class="form-overlay"></div>
                    
                    <div class="row">
                        <div class="col-12 anime-view">
                            <?php
                            global $wp_query;
                            if ($wp_query->found_posts > 12) { ?>
                              <span class="cta-button float-right to-animate" id="load_more">Load More</span>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<?php if(get_field('seo_block_content', $taxonomy.'_'.$term_id) || get_field('seo_block_title', $taxonomy.'_'.$term_id)){?>
<div class="container-fluid seo-content-block standard-cat-seo">
    <div class="container">
        <div class="row title-row seo-content-title">
            <div class="col-12 anime-scroll">
                <span class="title to-animate"><?php the_field('seo_block_title', $taxonomy.'_'.$term_id);?></span>
            </div>
        </div>
        
        <div class="row">
            <div class="col-12 anime-scroll">
                <div class="content to-animate float-left w-100">
                <?php the_field('seo_block_content', $taxonomy.'_'.$term_id);?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php } ?>