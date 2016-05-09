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
    'menu_name'           => 'Companies',
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

  $meta_boxes['company_details'] = array(
    'id'            => 'company_details',
    'title'         => __( 'Company Details', 'cmb2' ),
    'object_types'  => array( 'company', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
    'fields'        => array(
      array(
        'name' => 'Headquarters',
        'desc' => 'e.g. Nairobi, Kenya',
        'id'   => $prefix . 'headquarters',
        'type' => 'text_medium',
      ),
      array(
        'name' => 'Website',
        'id'   => $prefix . 'website',
        'type' => 'text_url',
      ),
      array(
        'name' => 'Industry',
        'desc' => 'e.g. Manufacturing (Automotive)',
        'id'   => $prefix . 'industry',
        'type' => 'text_medium',
      ),
      array(
        'name' => 'Callout Text',
        'desc' => 'A callout quote or summary',
        'id'   => $prefix . 'callout',
        'type' => 'wysiwyg',
        'options' => array(
          'textarea_rows' => 8,
        ),
      ),
      array(
        'name' => 'Video Links',
        'desc' => 'List of related Vimeo video URLs (e.g. https://vimeo.com/106786952 — one per line)',
        'id'   => $prefix . 'video_links',
        'type' => 'textarea',
        'options' => array(
          'textarea_cols' => 8,
        ),
      ),
      array(
        'name' => 'Image Slideshow',
        // 'desc' => 'List of related Vimeo video URLs (e.g. https://vimeo.com/106786952 — one per line)',
        'id'   => $prefix . 'image_slideshow',
        'type' => 'file_list',
      ),
      array(
        'name' => 'News Links',
        'desc' => 'List of related news links',
        'id'   => $prefix . 'news_links',
        'type' => 'wysiwyg',
        'options' => array(
          'textarea_rows' => 8,
        ),
      ),
    ),
  );

  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );

/**
 * Parse video_links on save
 */
function parse_video_links($post_id, $post='') {
  $video_links = get_post_meta($post_id, '_cmb2_video_links', true);
  $video_links_parsed = '';
  if ($video_links) {
    $video_lines = explode(PHP_EOL, trim($video_links));
    foreach ($video_lines as $line) {
      // Extract vimeo video ID and pull large thumbnail from API
      $vimeo_url = trim($line);
      $img_url = '';
      if (preg_match('/vimeo.com\/(\d+)/', $line, $m)) {
        $img_id = $m[1];
        $hash = unserialize(file_get_contents('http://vimeo.com/api/v2/video/' . $img_id . '.php'));
        $img_url = $hash[0]['thumbnail_large'];
      }

      // If we found an image, show link to video and build new_lines
      if ($img_url) {
        $video_links_parsed .= $vimeo_url.'::'.$img_url."\n";
      }
    }
    // Store parsed links in hidden post meta field
    if ($video_links_parsed) {
      update_post_meta($post_id, '_cmb2_video_links_parsed', $video_links_parsed);
    }
  }
}
add_action('save_post_company', __NAMESPACE__ . '\\parse_video_links', 20, 2);


/**
 * Get Companies
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

  $output = '<ul class="grid-items companies">';
  ob_start();
  foreach ( $company_posts as $post ):
    echo '<li class="grid-item company">';
    include(locate_template('templates/article-company.php'));
    echo '</li>';
  endforeach;
  $output .= ob_get_clean();
  $output .= '</ul>';

  return $output;
}
