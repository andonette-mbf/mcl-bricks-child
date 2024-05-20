<?php
/**
 * Template part for displaying the header of the shop
 */
global $product;
?>

<section class="brxe-section brxe-wc-section mcl-hero">
    <div class="brxe-container mcl-flex--col mcl-hero__inner mcl-padding">
        <div class="brxe-div mcl-hero__content mcl-flex--col">
            <h1 class="brxe-heading mcl-hero__title"><?php if ( get_field ( 'display_title' ) ) { ?>
                    <?php the_field ( 'display_title' ); ?>
                </h1>
            <?php } else { ?>
                <h1 class="brxe-heading mcl-hero__title"><?php the_title (); ?></h1>
            <?php } ?>

            <h5>
                Duration: <?php if ( get_field ( 'course_duration_custom' ) ) { ?>
                    <span><?php the_field ( 'course_duration_custom' ); ?></span>
                <?php } else { ?>
                    <span class="float-left w-100"><?php echo $duarionTime[ 0 ]; ?>     <?php echo $duarionType[ 0 ]; ?></span>
                <?php } ?>
                <br>
                Certification:
                <?php echo get_field ( 'certification_meta', $product_group_id ); ?>
                <br>
                Grants & Funding: <?php if ( get_field ( 'grants_funding', $product_group_id ) ) { ?>
                    <span>Options available</span>
                <?php } else { ?>
                    <span>Options not available</span>
                <?php } ?>
                <br>
                Availability: See dates below
            </h5>
        </div>
    </div>

    <div class="brxe-container mcl-hero__inner--absolute">
        <div class="brxe-div mcl-hero__overlay mcl-absolute--full"></div>
        <div class="brxe-div mcl-hero__image" style="background-image: url('<?php the_field ( 'hero_image' ); ?>');">
        </div>
        <div class="brxe-div mcl-hero__tagline-box">
            <h3 class="brxe-heading mcl-hero__tagline-box-heading">We've<br>got<br>you.</h3>
        </div>
    </div>
</section>