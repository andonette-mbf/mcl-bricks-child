<div class="course-step" id="step-4">
  <div class="title-row step-title">
    <span class="title">Step 4 - <?php echo get_field ( 'step_1_title', $product_group_id ); ?></span>
    <a href="#" data-step="4" class="previous-stept">Previous step</a>

    <div class="content">
      <?php echo get_field ( 'step_1_content', $product_group_id ); ?>
    </div>
  </div>

  <div>
    <?php
    // loop through the rows of data
    while ( have_rows ( 'step_1_questions', $product_group_id ) ) :
      the_row (); ?>
      <div class="step-field qualification-field">
        <span class="qual-label"><?php the_sub_field ( 'label' ); ?></span>
        <a href="#">Yes</a>

        <div class="qual-listing">
          <div class="content">
            <ul>
              <?php
              // loop through the rows of data
              while ( have_rows ( 'details' ) ) :
                the_row (); ?>
                <li><?php the_sub_field ( 'options' ); ?></li>
              <?php endwhile; ?>
            </ul>
          </div>
        </div>
      </div>
    <?php endwhile; ?>
  </div>
</div>