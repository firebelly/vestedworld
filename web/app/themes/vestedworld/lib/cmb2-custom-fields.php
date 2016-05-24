<?php
/**
 * CMB2 custom fields
 */

namespace Firebelly\CMB2;

/**
 * Get post options for CMB2 select
 */
function get_post_options( $query_args ) {

    $args = wp_parse_args( $query_args, array(
        'post_type'   => 'post',
        'orderby' => 'title',
        'order' => 'ASC',
        'numberposts' => 10,
        'post_parent' => 0,
    ) );

    $posts = get_posts( $args );

    $post_options = array();
    if ( $posts ) {
        foreach ( $posts as $post ) {
          $post_options[ $post->ID ] = $post->post_title;
        }
    }

    return $post_options;
}

/**
 * Custom CMB2 Field: timeline_date
 */
add_filter( 'cmb2_render_timeline_date', __NAMESPACE__ . '\\cmb2_render_timeline_date_field_callback', 10, 5 );
function cmb2_render_timeline_date_field_callback( $field, $value, $object_id, $object_type, $field_type ) {
    // Make sure we specify each part of the value we need.
    $value = wp_parse_args( $value, array(
        'date_title' => '',
        'date_description' => '',
    ) );
    ?>
    <?php echo $field_type->input( array(
        'class' => 'cmb2_text',
        'placeholder' => 'Date Label',
        'name'  => $field_type->_name( '[date_title]' ),
        'id'    => $field_type->_id( '_date_title' ),
        'value' => $value['date_title'],
        'desc'  => '',
    ) ); ?>
    <?php echo $field_type->textarea( array(
        'class' => 'cmb2-textarea-small',
        'placeholder' => 'Date Description',
        'rows' => 3,
        'style' => 'display:block',
        'name'  => $field_type->_name( '[date_description]' ),
        'id'    => $field_type->_id( '_date_description' ),
        'value' => $value['date_description'],
        'desc'  => '',
    ) ); ?>
    <?php
    echo $field_type->_desc( true );

}

/**
 * The following snippets are required for allowing the timeline_date field
 * to work as a repeatable field, or in a repeatable group
 */
function cmb2_sanitize_timeline_date_field( $check, $meta_value, $object_id, $field_args, $sanitize_object ) {
    // if not repeatable, bail out.
    if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
        return $check;
    }
    foreach ( $meta_value as $key => $val ) {
        if ( empty( implode( '',$val ) ) ) {
            unset( $meta_value[$key] );
        } else {
            $meta_value[ $key ] = array_map( 'sanitize_text_field', $val );
        }
    }
    return $meta_value;
}
add_filter( 'cmb2_sanitize_timeline_date', __NAMESPACE__ . '\\cmb2_sanitize_timeline_date_field', 10, 5 );
function cmb2_types_esc_timeline_date_field( $check, $meta_value, $field_args, $field_object ) {
    // if not repeatable, bail out.
    if ( ! is_array( $meta_value ) || ! $field_args['repeatable'] ) {
        return $check;
    }
    foreach ( $meta_value as $key => $val ) {
        $meta_value[ $key ] = array_map( 'esc_attr', $val );
    }
    return $meta_value;
}
add_filter( 'cmb2_types_esc_timeline_date', __NAMESPACE__ . '\\cmb2_types_esc_timeline_date_field', 10, 4 );

function cmb2_custom_sortables() {
?>
<script type='text/javascript'>
  jQuery(document).ready(function($) {
    if (jQuery('#_cmb2_timeline_repeat').length) {
        // sortable timeline_dates
        jQuery('#_cmb2_timeline_repeat').sortable({ items: '.cmb-row' })
    }
  });
</script>
<?php
}
add_action( 'admin_head', __NAMESPACE__ . '\cmb2_custom_sortables' );
