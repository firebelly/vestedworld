<?php
/**
 * Extra fields for Pages
 */

namespace Firebelly\PostTypes\Pages;

// // Custom CMB2 fields for post type
// function metaboxes( array $meta_boxes ) {
//   $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

//   $meta_boxes['page_metabox'] = array(
//     'id'            => 'page_metabox',
//     'title'         => __( 'Extra Fields', 'cmb2' ),
//     'object_types'  => array( 'page', ), // Post type
//     'context'       => 'normal',
//     'priority'      => 'high',
//     'show_names'    => true, // Show field names on the left
//     'fields'        => array(
      
//       // General page fields
//       array(
//         'name' => 'Secondary Content',
//         'desc' => 'Used on several pages for secondary content areas',
//         'id'   => $prefix . 'secondary_content',
//         'type' => 'wysiwyg',
//       ),
//     ),
//   );

//   return $meta_boxes;
// }
// add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );
