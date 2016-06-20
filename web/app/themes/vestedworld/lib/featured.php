<?php

namespace Firebelly\Featured;

/**
 * Custom CMB2 fields for post type
 */
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

/**
 * All the types of posts that can be featured
 */
function get_featured_types() {
  return [ 'post', 'person', 'company', 'country' ];
}

/**
 * Get featured content
 */
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

  $parent_url_text = [
    'country' => 'All Profiles',
    'industry' => 'All Profiles',
    'person' => 'Meet the Team',
    'post' => 'All Posts',
  ];

  $tab_text = [
    'country' => 'Resources',
    'industry' => 'Resources',
    'person' => 'Our Team',
    'post' => 'News',
  ];

  $output .= '<h3 class="tab -white">' . $tab_text[$featured_posts[0]->post_type] . '</h3>';
  $output .= '<div class="slider-featured">';

  foreach ($featured_posts as $post) {
    $type = $post->post_type;
    $link = get_permalink($post->ID);
    $post_url = get_permalink($post);
    $parent_url = \Firebelly\Utils\get_parent_url($post);
    $excerpt = \Firebelly\Utils\get_excerpt($post);
    $output .= <<<HTML
      <div class="slide-item">
        <article data-tab-text="{$tab_text[$type]}" data-parent-url="{$parent_url}" data-parent-url-text="{$parent_url_text[$type]}">
          <h3>{$type}</h3>
          <h2>{$post->post_title}</h2>
          <p>{$excerpt}</p>
          <p><a class="learn-more" href="{$post_url}">Learn More</a></p>
        </article>
      </div>
HTML;
  }

  $output .= '</div><a class="learn-more button" href="' . \Firebelly\Utils\get_parent_url($featured_posts[0]) . '">' . $parent_url_text[$featured_posts[0]->post_type] . '</a>';

  return $output;
}