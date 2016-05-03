<?php
/**
 * Glossary post type
 */

namespace Firebelly\PostTypes\Glossary;
use Firebelly\Utils;

// Register Custom Post Type
function post_type() {

  $labels = array(
    'name'                => 'Glossary',
    'singular_name'       => 'Glossary',
    'menu_name'           => 'Glossary',
    'parent_item_colon'   => '',
    'all_items'           => 'All Terms',
    'view_item'           => 'View Term',
    'add_new_item'        => 'Add New Term',
    'add_new'             => 'Add New',
    'edit_item'           => 'Edit Term',
    'update_item'         => 'Update Term',
    'search_items'        => 'Search Terms',
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
    'label'               => 'Glossary',
    'description'         => 'Terms',
    'labels'              => $labels,
    'supports'            => array( 'title', 'editor', ),
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
    'capability_type'     => 'page',
  );
  register_post_type( 'glossary', $args );

}
add_action( 'init', __NAMESPACE__ . '\post_type', 0 );

// Custom admin columns for post type
function edit_columns($columns){
  $columns = array(
    'cb' => '<input type="checkbox" />',
    'title' => 'Term',
    'content' => 'Definition',
  );
  return $columns;
}
add_filter('manage_glossary_posts_columns', __NAMESPACE__ . '\edit_columns');

function custom_columns($column){
  global $post;
  if ( $post->post_type == 'glossary' ) {
    if ( $column == 'content' )
      echo Utils\get_excerpt($post);
  }
}
add_action('manage_posts_custom_column',  __NAMESPACE__ . '\custom_columns');
