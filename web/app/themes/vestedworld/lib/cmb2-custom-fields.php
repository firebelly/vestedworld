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
 * Render timeline_date Field
 */
function cmb2_render_timeline_date_field_callback( $field, $value, $object_id, $object_type, $field_type ) {

    // make sure we specify each part of the value we need.
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
    <p>Description</p>
    <?php echo $field_type->textarea_small( array(
        'class' => 'cmb2_textarea',
        'placeholder' => 'Label',
        'name'  => $field_type->_name( '[date_description]' ),
        'id'    => $field_type->_id( '_date_description' ),
        'value' => $value['date_description'],
        'desc'  => '',
    ) ); ?>
    <?php
    echo $field_type->_desc( true );

}
add_filter( 'cmb2_render_timeline_date', __NAMESPACE__ . '\\cmb2_render_timeline_date_field_callback', 10, 5 );
