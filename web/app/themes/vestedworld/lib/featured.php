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
    $link = get_permalink($post->ID);
    $excerpt = \Firebelly\Utils\get_excerpt($post);
    $output .= <<<HTML
      <div class="slide-item">
        <article>
          <h3>{$type}</h3>
          <h2><a href="{$link}">{$post->post_title}</a></h2>
          <p>{$excerpt}</p>
          <a class="learn-more" href="{$link}">Learn More</a>

          <div class="learn-more-wrap">
            <a class="button learn-more" href="/{$type}/">All {$type}s</a>
          </div>
       </article>
      </div>
HTML;
  }

  return $output;
}