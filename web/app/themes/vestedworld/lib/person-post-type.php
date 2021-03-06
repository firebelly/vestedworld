<?php
/**
 * Person post type
 */

namespace Firebelly\PostTypes\Person;
use Firebelly\Utils;

/**
 * Register Custom Post Type
 */
function post_type() {

  $labels = array(
    'name'                => 'People',
    'singular_name'       => 'Person',
    'menu_name'           => 'People',
    'parent_item_colon'   => '',
    'all_items'           => 'All People',
    'view_item'           => 'View Person',
    'add_new_item'        => 'Add New Person',
    'add_new'             => 'Add New',
    'edit_item'           => 'Edit Person',
    'update_item'         => 'Update Person',
    'search_items'        => 'Search People',
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
    'label'               => 'person',
    'description'         => 'Management, Advisory Board, VestedAngels, VestedAdvisors',
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
  register_post_type( 'person', $args );

}
add_action( 'init', __NAMESPACE__ . '\post_type', 0 );

/* define member types here for the sake of DRYness */
function get_member_type_array() {
  return array(
    'management' => 'Management',
    'board' => 'Advisory Board',
    'vested_angel' => 'VestedAngel',
    'vested_advisor' => 'VestedAdvisor',
  );
}

/**
 * Custom admin columns for post type
 */
function edit_columns($columns){
  $columns = array(
    'cb' => '<input type="checkbox" />',
    'title' => 'Name',
    '_cmb2_member_type' => 'Type',
    '_cmb2_title' => 'Title',
    '_cmb2_subtitle' => 'Subtitle',
    'content' => 'Bio',
    'featured_image' => 'Image',
  );
  return $columns;
}
add_filter('manage_person_posts_columns', __NAMESPACE__ . '\edit_columns');

function custom_columns($column){
  global $post;
  if ( $post->post_type == 'person' ) {
    if ( $column == 'featured_image' )
      echo the_post_thumbnail('thumbnail');
    elseif ( $column == 'content' )
      echo Utils\get_excerpt($post);
    elseif ( $column == '_cmb2_member_type' ) {
      $type_name = get_member_type_array();
      $custom = get_post_custom();
      if (array_key_exists($column, $custom))
        echo '<a href="/wp/wp-admin/edit.php?post_type=person&member_type=' . array_search($type_name[$custom[$column][0]], get_member_type_array()) . '">' . $type_name[$custom[$column][0]] . '</a>';
    } else {
      $custom = get_post_custom();
      if (array_key_exists($column, $custom))
        echo $custom[$column][0];
    }
  };
}
add_action('manage_posts_custom_column',  __NAMESPACE__ . '\custom_columns');

/**
 * Allow filtering by person type
 */
function add_member_type_filter_to_people($query){
  global $post_type, $pagenow;
  // Check if on edit person page
  if($pagenow == 'edit.php' && $post_type == 'person'){
    if(isset($_GET['member_type'])){

      // Add member_type to query
      $member_type = sanitize_text_field($_GET['member_type']);
      $query->query_vars['meta_query'] = array(
        array(
          'key' => '_cmb2_member_type',
          'value' => $member_type,
        )
      );
    }
  }
}
add_action('pre_get_posts',__NAMESPACE__.'\add_member_type_filter_to_people');


// Custom CMB2 fields for post type
function metaboxes( array $meta_boxes ) {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $meta_boxes['person_details'] = array(
    'id'            => 'person_details',
    'title'         => __( 'Person Details', 'cmb2' ),
    'object_types'  => array( 'person', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
    'fields'        => array(
      array(
        'name' => 'Member Type',
        'id'   => $prefix . 'member_type',
        'type' => 'radio_inline',
        'default' => 'management',
        'options' => get_member_type_array(),
      ),
      array(
        'name' => 'Image is a logo?',
        'desc' => 'If checked, will style the featured image as a logo',
        'id'   => $prefix . 'image_is_logo',
        'type' => 'checkbox',
      ),
      array(
        'name' => 'Title',
        'desc' => 'e.g. Co-Founder',
        'id'   => $prefix . 'title',
        'type' => 'text_medium',
      ),
      array(
        'name' => 'Subtitle',
        'desc' => 'e.g. Chief Executive Officer',
        'id'   => $prefix . 'subtitle',
        'type' => 'text_medium',
      ),
      array(
        'name' => 'Quote',
        'desc' => 'A quote associated with a management team member',
        'id'   => $prefix . 'quote',
        'type' => 'wysiwyg',
      ),
      array(
        'name' => 'Callout Text',
        'desc' => 'A callout quote or summary for the full bio view',
        'id'   => $prefix . 'callout',
        'type' => 'wysiwyg',
      ),
    ),
  );

  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );

/**
 * Get People
 */
function get_people($options=[]) {
  $output = '';

  $args = array(
    'numberposts' => -1,
    'post_type' => 'person',
    'orderby' => 'menu_order',
    '_cmb2_member_type' => $options['member_type'],
    );
  $args['meta_query'] = [
    [
      'key' => '_cmb2_member_type',
      'value' => $options['member_type'],
    ]
  ];

  $person_posts = get_posts($args);
  if (!$person_posts) return false;

  $output = '<ul class="grid-items people-grid">';

  foreach ( $person_posts as $post ):
    $output .= '<li class="grid-item person">';
    ob_start();
    include(locate_template('templates/article-person.php'));
    $output .= ob_get_clean();
    $output .= '</li>';
  endforeach;

  $output .= '</ul>';

  return $output;
}

/**
 * Redirect people posts to proper landing page
 */
function single_person_redirect() {
  if (is_single()) {
    global $post;

    if (!empty($post->ID) && $post->post_type == 'person') {
      $parent_url = \Firebelly\Utils\get_parent_url($post);
      if ($parent_url) {
        wp_redirect($parent_url . '#' . $post->post_name, 301);
        exit();
      }
    }
  }
}
add_action('template_redirect', __NAMESPACE__ . '\single_person_redirect');
