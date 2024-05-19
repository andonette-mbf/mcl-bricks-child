<?php
global $product;
?>
<div class="step-layouts">
    <div class="layouts" id="layout-list">
        <div class="calendar-list">
            <div class="table-section">
                <?php
                $select_address       = get_field_object ( 'select_address' );
                $select_address_value = $select_address[ 'value' ];

                // Check if we have some future availability dates
                if ( ! empty ( $futureAvailabilityDates ) ) {
                    ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Start Date</th>
                                <th>Location</th>
                                <th>Availability</th>
                                <th class="add-td"></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ( $futureAvailabilityDates as $futureAvailabilityDate ) {
                                $start_date_formatted = $futureAvailabilityDate[ 'from' ]->format ( 'd/m/Y' );
                                $end_date_formatted   = $futureAvailabilityDate[ 'to' ]->format ( 'd/m/Y' );
                                $day                  = $futureAvailabilityDate[ 'from' ]->format ( 'j' );
                                $month                = $futureAvailabilityDate[ 'from' ]->format ( 'n' );
                                $year                 = $futureAvailabilityDate[ 'from' ]->format ( 'Y' );

                                // Determine the location based on the selected address value
                                $location = 'East London Training Centre';
                                if ( $select_address_value == 'Redwood Park Estate, Stallingborough NE, Lincolnshire, DN41 8TH' ) {
                                    $location = 'Humber Training Centre';
                                    } elseif ( $select_address_value == '11 Thornsett Works, Thornsett Road, Wandsworth, London, SW18 4EW' ) {
                                    $location = 'South London Training Centre';
                                    }
                                ?>
                                <tr data-start="<?php echo esc_attr ( $start_date_formatted ); ?>"
                                    data-end="<?php echo esc_attr ( $end_date_formatted ); ?>">
                                    <td><?php echo esc_html ( $start_date_formatted ); ?></td>
                                    <td><?php echo esc_html ( $location ); ?></td>
                                    <td>Bookings Available</td>
                                    <td class="add-td">
                                        <a href="#" data-day="<?php echo esc_attr ( $day ); ?>"
                                            data-month="<?php echo esc_attr ( $month ); ?>"
                                            data-year="<?php echo esc_attr ( $year ); ?>"
                                            class="cta-button book-now-button float-right">Select Date</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                <?php } ?>
            </div>
        </div>
    </div>
    <div class="layouts float-left w-100 position-relative" id="layout-calendar">
        <?php
        /**
         * Hook: woocommerce_single_product_summary.
         *
         * @hooked woocommerce_template_single_title - 5
         * @hooked woocommerce_template_single_rating - 10
         * @hooked woocommerce_template_single_price - 10
         * @hooked woocommerce_template_single_excerpt - 20
         * @hooked woocommerce_template_single_add_to_cart - 30
         * @hooked woocommerce_template_single_meta - 40
         * @hooked woocommerce_template_single_sharing - 50
         * @hooked WC_Structured_Data::generate_product_data() - 60
         */
        do_action ( 'woocommerce_single_product_summary' );
        ?>
    </div>
</div>