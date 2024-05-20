<?php
/**
 * Template part for displaying the sidebar of single product
 */
global $product;
?>

<div class="training-sidebar">
    <div class="sidebar-inner">
        <div class="sidebar-selections">
            <h3 class="brxe-heading">Your Selection</h3>
            <?php if ( $product->is_type ( 'booking' ) ) { ?>
                <!-- meta class uses js --->
            <div class="meta">
                <b>Course: </b><br>
                <span>
                    <?php echo get_the_title ( $product_group_id ); ?>
                </span>
            </div>

            <div class="meta">
                <b>Venue</b>
                <span>
                    <?php the_field ( 'location' ); ?>
                </span>
            </div>

            <div class="meta" id="course-date-meta">
                <b>Course Date</b><br>
                <span class="title">
                    <span class="start-date">
                        <div class="dd"></div>/<div class="mm">/</div>/<div class="yyyy"></div>
                    </span>
                </span>
            </div>
            <?php } ?>

            <?php if ( $product->is_type ( 'booking' ) ) { ?>
            <p class="from-price price"></p>
            <p class="total-price price">
                <span class="price title">Total - £<span id="total-cost"></span></span </p>
                <?php } ?>
        </div>
    </div>
</div>