<?php
/**
 * Faq post type
 */

namespace Firebelly\PostTypes\Faq;
use Firebelly\Utils;

// Register Custom Post Type
function post_type() {

  $labels = array(
    'name'                => 'FAQs',
    'singular_name'       => 'FAQ',
    'menu_name'           => 'FAQs',
    'parent_item_colon'   => '',
    'all_items'           => 'All FAQs',
    'view_item'           => 'View FAQ',
    'add_new_item'        => 'Add New FAQ',
    'add_new'             => 'Add New',
    'edit_item'           => 'Edit FAQ',
    'update_item'         => 'Update FAQ',
    'search_items'        => 'Search FAQs',
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
    'label'               => 'FAQ',
    'description'         => 'FAQs',
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
  register_post_type( 'faq', $args );

}
add_action( 'init', __NAMESPACE__ . '\post_type', 0 );

// Register custom taxonomy for post type
register_taxonomy( 'faq_cat',
  array('faq'),
  array('hierarchical' => true, // if this is true, it acts like categories
    'labels' => array(
      'name' => 'FAQ Categories',
      'singular_name' => 'FAQ Category',
      'search_items' =>  'Search FAQ Categories',
      'all_items' => 'All FAQ Categories',
      'parent_item' => 'Parent FAQ Category',
      'parent_item_colon' => 'Parent FAQ Category:',
      'edit_item' => 'Edit FAQ Category',
      'update_item' => 'Update FAQ Category',
      'add_new_item' => 'Add New FAQ Category',
      'new_item_name' => 'New FAQ Category',
    ),
    'show_admin_column' => true,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array(
      'slug' => 'faqs/category',
      'with_front' => false
    ),
  )
);
// remove category list from sidebar as we are using a custom CMB2 dropdown below
function remove_faq_cat_meta() {
  remove_meta_box( 'faq_catdiv', 'faq', 'side' );
}
add_action( 'admin_menu' , __NAMESPACE__ . '\remove_faq_cat_meta' );

// Custom admin columns for post type
function edit_columns($columns){
  $columns = array(
    'cb' => '<input type="checkbox" />',
    'title' => 'Question',
    'content' => 'Answer',
    'taxonomy-faq_cat' => 'Category',
  );
  return $columns;
}
add_filter('manage_faq_posts_columns', __NAMESPACE__ . '\edit_columns');

function custom_columns($column){
  global $post;
  if ( $post->post_type == 'faq' ) {
    if ( $column == 'content' )
      echo Utils\get_excerpt($post);
  }
}
add_action('manage_posts_custom_column',  __NAMESPACE__ . '\custom_columns');

// Custom CMB2 fields for post type
function metaboxes( array $meta_boxes ) {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $meta_boxes['faq_metabox'] = array(
    'id'            => 'faq_metabox',
    'title'         => __( 'Extra Fields', 'cmb2' ),
    'object_types'  => array( 'faq', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
    'fields'        => array(
      array(
          'name' => 'Category',
          'id' => $prefix . 'faq_cat_select',
          'taxonomy' => 'faq_cat',
          'type' => 'taxonomy_select',
      ),
    ),
  );

  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );

// Shortcode [faqs]
add_shortcode('faqs', __NAMESPACE__ . '\shortcode');
function shortcode($atts) {
  extract(shortcode_atts(array(
       'category' => '',
    ), $atts));
  $output = '';

  $args = array(
    'numberposts' => -1,
    'post_type' => 'faq',
    'orderby' => 'menu_order',
    );
  if ($category != '') {
    $args['tax_query'] = array(
      array(
        'taxonomy' => 'faq_cat',
        'field' => 'slug',
        'terms' => $category
      )
    );
  }

  $faq_posts = get_posts($args);
  if (!$faq_posts) return false;

  $output .= '<ul class="resource-list accordion">';
  $i = 0;
  foreach ($faq_posts as $post):
    $i++;
    $question = $post->post_title;
    $answer = apply_filters('the_content', $post->post_content);
    $output .= <<<HTML
      <li class="accordion-item">
        <h2><a href="#answer-{$category}-{$i}" class="accordion-trigger">{$question} <svg class="icon icon-arrow-right" role="img"><use xlink:href="#icon-arrow-right"></use></svg><svg class="icon icon-close" role="img"><use xlink:href="#icon-close"></use></svg></a></h2>
        <div id="answer-{$category}-{$i}" class="item-content accordion-content user-content">{$answer}</div>
      </li>
HTML;
  endforeach;
  $output .= "</ul>";

  return $output;
}
