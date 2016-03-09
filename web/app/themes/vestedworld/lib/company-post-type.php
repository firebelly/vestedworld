<?php
/**
 * Company post type
 */

namespace Firebelly\PostTypes\Company;
use Firebelly\Utils;


/**
 * Register Custom Post Type
 */
function post_type() {

  $labels = array(
    'name'                => 'Companies',
    'singular_name'       => 'Company',
    'menu_name'           => '**Companies',
    'parent_item_colon'   => '',
    'all_items'           => 'All Companies',
    'view_item'           => 'View Company',
    'add_new_item'        => 'Add New Company',
    'add_new'             => 'Add New',
    'edit_item'           => 'Edit Company',
    'update_item'         => 'Update Company',
    'search_items'        => 'Search Companies',
    'not_found'           => 'Not found',
    'not_found_in_trash'  => 'Not found in Trash',
  );
  $rewrite = array(
    'slug'                => '',
    'with_front'          => false,
    'pages'               => false,
    'feeds'               => false,
  );
  $args = array(
    'label'               => 'company',
    'description'         => 'Company Profiles',
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor', 'thumbnail', ),
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'menu_position'       => 20,
    'menu_icon'           => 'dashicons-admin-post',
    'can_export'          => false,
    'has_archive'         => false,
    'exclude_from_search' => false,
    'publicly_queryable'  => true,
    'rewrite'             => $rewrite,
  );
  register_post_type( 'company', $args );

}
add_action( 'init', __NAMESPACE__ . '\post_type', 0 );

/**
 * Custom admin columns for post type
 */
function edit_columns($columns){
  $columns = array(
    'cb' => '<input type="checkbox" />',
    'title' => 'Name',
    'content' => 'Content',
    'featured_image' => 'Image',
  );
  return $columns;
}
add_filter('manage_company_posts_columns', __NAMESPACE__ . '\edit_columns');

function custom_columns($column){
  global $post;
  if ( $post->post_type == 'company' ) {
    if ( $column == 'featured_image' )
      echo the_post_thumbnail('thumbnail');
    elseif ( $column == 'content' )
      echo Utils\get_excerpt($post);
    else {
      $custom = get_post_custom();
      if (array_key_exists($column, $custom))
        echo $custom[$column][0];
    }
  };
}
add_action('manage_posts_custom_column',  __NAMESPACE__ . '\custom_columns');

// Custom CMB2 fields for post type
function metaboxes( array $meta_boxes ) {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  // $meta_boxes['company_details'] = array(
  //   'id'            => 'company_details',
  //   'title'         => __( 'Company Details', 'cmb2' ),
  //   'object_types'  => array( 'company', ), // Post type
  //   'context'       => 'normal',
  //   'priority'      => 'high',
  //   'show_names'    => true, // Show field names on the left
  //   'fields'        => array(
  //     array(
  //       'name' => 'Title',
  //       'desc' => 'e.g. Co-Founder',
  //       'id'   => $prefix . 'title',
  //       'type' => 'text_medium',
  //     ),   
  //   ),
  // );

  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );




/**
 * Get Peop;e
 */
function get_companies() {
  $output = '';

  $args = array(
    'numberposts' => -1,
    'post_type' => 'company',
    'orderby' => 'menu_order',
    );

  $company_posts = get_posts($args);
  if (!$company_posts) return false;

  $output = '<ul class="companies-grid">';

  foreach ( $company_posts as $post ):
    $output .= '<li class="company">';
    ob_start();
    include(locate_template('templates/article-company.php'));
    $output .= ob_get_clean();
    $output .= '</li>';
  endforeach;

  $output .= '</ul>';

  return $output;
}
