
<div class="container-fluid sub-cats">
	<div class="container">
    	<div class="row justify-content-center anime-view">
        	<?php
			if (is_shop()) {
				$parent_cat_arg = array('hide_empty' => false, 'parent' => 0, 'exclude' => '24');
			} else {
				$parent_cat_arg = array('hide_empty' => false, 'parent' => $term_id, 'exclude' => '24' );
			}

			$parent_cat = get_terms( 'product_cat', $parent_cat_arg );
			foreach ($parent_cat as $sub_term) {
				
				$taxonomy = $sub_term->taxonomy; 
				$term_id = $sub_term->term_id; 
                
                include(get_stylesheet_directory() . '/template-parts/post/content-category.php');
			
			} ?>
        </div>
    </div>
</div>
