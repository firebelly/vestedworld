<?php


namespace Firebelly\Featured;

// Custom CMB2 fields for post type
function metaboxes( array $meta_boxes ) {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $meta_boxes['featured'] = array(
    'id'            => 'featured',
    'title'         => __( 'Featured Post', 'cmb2' ),
    'object_types'  => get_featured_types(),
    'context'       => 'side',
    'priority'      => 'default',
    'show_names'    => false,
    'fields'        => array(
      array(
          'id'       => $prefix . 'featured',
          'type'     => 'checkbox',
          'desc'     => 'Feature on homepage slider',
          'name'     => 'Yes',
      ),
    ),
  );

  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );

//put all the types of posts that can be featured here, for DRYness sake
function get_featured_types() {
  return array( 'post', 'person', 'company', 'country');
}

// Get featured content
function get_featured() {
  $output = '';
  
  $args = array(
    'numberposts' => -1,
    'post_type' => get_featured_types(),
  );
  $args['meta_query']= array(
    array(
      'key' => '_cmb2_featured',
      'value' => 'on',
    ),
  );
  $featured_posts = get_posts($args);
  if(!$featured_posts) return false;
  
  foreach ($featured_posts as $post) {
    $type = $post->post_type;
    $output .= <<<HTML
      <div class="slide-item">
        <article>
          <h1>{$post->post_title}</h1>
          <p>!!templates for content here waiting on design!!</p>
        </article>
      </div>
HTML;
  }

  return $output;
}