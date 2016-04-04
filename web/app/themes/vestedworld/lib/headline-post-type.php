<?php
/**
 * Headline post type
 */

namespace Firebelly\PostTypes\Headlines;

// Register Custom Post Type
function post_type() {

  $labels = array(
    'name'                => 'Headlines',
    'singular_name'       => 'Headline',
    'menu_name'           => 'Headlines',
    'parent_item_colon'   => '',
    'all_items'           => 'All Headlines',
    'view_item'           => 'View Headline',
    'add_new_item'        => 'Add New Headline',
    'add_new'             => 'Add New',
    'edit_item'           => 'Edit Headline',
    'update_item'         => 'Update Headline',
    'search_items'        => 'Search Headlines',
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
    'label'               => 'headline',
    'description'         => 'Headlines',
    'labels'              => $labels,
    'supports'            => array( 'title', 'thumbnail', 'editor'),
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
  register_post_type( 'headline', $args );

}
add_action( 'init', __NAMESPACE__ . '\post_type', 0 );

// Custom admin columns for post type
function edit_columns($columns){
  $columns = array(
    'cb' => '<input type="checkbox" />',
    'title' => 'Title',
    'featured_image' => 'Image',
    '_cmb2_links_to' => 'Links To'
  );
  return $columns;
}
add_filter('manage_headline_posts_columns', __NAMESPACE__ . '\edit_columns');

function custom_columns($column){
  global $post;
  if ( $post->post_type == 'headline' ) {
    $custom = get_post_custom();
    if ( $column == 'featured_image' )
      echo the_post_thumbnail( 'thumbnail' );
    elseif ( $column == '_cmb2_links_to' ) {
      if ($pages = get_pages(array('include' => $custom[$column][0]))) {
        foreach($pages as $page) {
          $pages_on[] = $page->post_title;
        }
        echo implode(',', $pages_on);
      }
    }
    elseif ( $column == 'content' )
      echo the_content();
    else {
      if (array_key_exists($column, $custom))
        echo $custom[$column][0];
    }
  }
}
add_action('manage_posts_custom_column',  __NAMESPACE__ . '\custom_columns');

// Custom CMB2 fields for post type
function metaboxes( array $meta_boxes ) {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $meta_boxes['headline_metabox'] = array(
    'id'            => 'headline_metabox',
    'title'         => __( 'Link', 'cmb2' ),
    'object_types'  => array( 'headline', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
    'fields'        => array(
    	array(
    	    'name'    => 'Links to',
    	    'id'      => $prefix . 'links_to',
    	    'type'    => 'select',
    	    'options' => cmb2_get_post_options( array(
            'post_type' => array('page','post'),
            'numberposts' => 0,
            'post_parent' => null
          ) ),
    	),
    ),
  );

  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );

function cmb2_get_post_options( $query_args ) {

    $args = wp_parse_args( $query_args, array(
        'post_type'   => 'post',
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

// Get Headline
function get_headlines() {
  $output = '';

  $args = array(
    'numberposts' => -1,
    'post_type' => 'headline',
    );

  $headline_posts = get_posts($args);
  if (!$headline_posts) return false;

  foreach ($headline_posts as $post):
    $thumb = get_the_post_thumbnail($post->ID, 'full');
    $body = strip_tags($post->post_content);
    $links_to = get_permalink(get_post_meta($post->ID, '_cmb2_links_to', true));

    $output .= <<<HTML
       <div class="slide-item">
         <article>
            {$thumb}
            <div class="slide-text">
              <h3 class="tab"><a href="{$links_to}">{$post->post_title}</a></h3>
              <p class="headline h1">{$body}</p>
              <div class="learn-more-wrap">
                <a class="button learn-more" href="{$links_to}">Learn More</a>
              </div>
            </div>
        </article>
       </div>
HTML;
  endforeach;

  return $output;
}