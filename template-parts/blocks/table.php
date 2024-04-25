<?php

/**
 * Content Block Template.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */

// Create id attribute allowing for custom "anchor" value.
$id = 'table-' . $block['id'];
if( !empty($block['anchor']) ) {
    $id = $block['anchor'];
}

// Create class attribute allowing for custom "className" and "align" values.
$className = 'table-section';
if( !empty($block['className']) ) {
    $className .= ' ' . $block['className'];
}
if( !empty($block['align']) ) {
    $className .= ' align' . $block['align'];
}

// Load values and assign defaults.
$table = get_field('table') ?: 'Your Content Here...';
?>

<?php
$innercols = '<div class="col-12">';
?>

<div class="container-fluid flexible-row-block table-section <?php echo esc_attr($className); ?>" id="<?php echo esc_attr($id); ?>">
    <div class="container">
        <div class="row">
            <?php echo $innercols;?>
                <div class="table-section">
                    <?php
                    if ( ! empty ( $table ) ) {
                    
                        echo '<table class="table">';
                    
                            if ( ! empty( $table['caption'] ) ) {
                    
                                echo '<caption>' . $table['caption'] . '</caption>';
                            }
                    
                            if ( ! empty( $table['header'] ) ) {
                    
                                echo '<thead>';
                    
                                    echo '<tr>';
                    
                                        foreach ( $table['header'] as $th ) {
                    
                                            echo '<th>';
                                                echo $th['c'];
                                            echo '</th>';
                                        }
                    
                                    echo '</tr>';
                    
                                echo '</thead>';
                            }
                    
                            echo '<tbody>';
                    
                                foreach ( $table['body'] as $tr ) {
                    
                                    echo '<tr>';
                    
                                        foreach ( $tr as $td ) {
                    
                                            echo '<td>';
                                                echo $td['c'];
                                            echo '</td>';
                                        }
                    
                                    echo '</tr>';
                                }
                    
                            echo '</tbody>';
                    
                        echo '</table>';
                    }?>
                </div>
            </div>
        </div>
    </div>
</div>