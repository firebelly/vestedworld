<?php
/**
 * Country post type
 */

namespace Firebelly\PostTypes\Country;
use Firebelly\Utils;


/**
 * Register Custom Post Type
 */
function post_type() {

  $labels = array(
    'name'                => 'Countries',
    'singular_name'       => 'Country',
    'menu_name'           => 'Countries',
    'parent_item_colon'   => '',
    'all_items'           => 'All Countries',
    'view_item'           => 'View Country',
    'add_new_item'        => 'Add New Country',
    'add_new'             => 'Add New',
    'edit_item'           => 'Edit Country',
    'update_item'         => 'Update Country',
    'search_items'        => 'Search Countries',
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
    'label'               => 'country',
    'description'         => 'Country Profiles',
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
  register_post_type( 'country', $args );

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
add_filter('manage_country_posts_columns', __NAMESPACE__ . '\edit_columns');

function custom_columns($column){
  global $post;
  if ( $post->post_type == 'country' ) {
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

  // Details
  $meta_boxes['country_details'] = array(
    'id'            => 'country_details',
    'title'         => __( 'Details', 'cmb2' ),
    'object_types'  => array( 'country', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
    'fields'        => array(
      array(
        'name' => 'Timeline',
        'id'   => 'timeline',
        // 'description' => 'e.g. 1963 or 4,000 B.C.—250 B.C.',
        'type' => 'timeline_date',
        'repeatable' => true,
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
    )
  );

  // Overview
  $meta_boxes['country_overview'] = array(
    'id'            => 'country_overview',
    'title'         => __( 'Overview', 'cmb2' ),
    'object_types'  => array( 'country', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
    'fields'        => array(
      array(
        'name' => 'Current Population',
        'desc' => 'in millions, e.g. 41.6',
        'id'   => $prefix . 'population',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Median Age',
        'id'   => $prefix . 'median_age',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Poverty',
        'id'   => $prefix . 'poverty',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Workforce Participation',
        'id'   => $prefix . 'workforce_participation',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Income Level Classification',
        'id'   => $prefix . 'income_level_classification',
        'type' => 'radio_inline',
        'options' => array(
            'low' => 'Low',
            'lower_middle'   => 'Lower Middle',
            'higher_middle'   => 'Higher Middle',
            'high'   => 'High',
        ),
      ),
    ),
  );

  // Economic Outlook
  $meta_boxes['country_economic_outlook'] = array(
    'id'            => 'country_economic_outlook',
    'title'         => __( 'Economic Outlook', 'cmb2' ),
    'object_types'  => array( 'country', ), // Post type
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true, // Show field names on the left
    'fields'        => array(
      array(
        'name' => 'Gross GDP',
        'desc' => 'in billions, e.g. 63.1',
        'id'   => $prefix . 'gross_gdp',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Per Capita GDP',
        'desc' => 'e.g. $3,300',
        'id'   => $prefix . 'per_capita_gdp',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Inflation',
        'desc' => 'e.g. 6.4%',
        'id'   => $prefix . 'inflation',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Foreign Direct Investments',
        'desc' => 'in millions, e.g. 944',
        'id'   => $prefix . 'foreign_direct_investments',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Ease of Doing Business Ranking',
        'desc' => 'e.g. 108/189',
        'id'   => $prefix . 'ease_of_doing_business_ranking',
        'type' => 'text_small',
      ),
      array(
        'name' => 'World Corruption Ranking',
        'desc' => 'e.g. 139/168',
        'id'   => $prefix . 'world_corruption_ranking',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Average Exchange Rate for previous year',
        'desc' => 'e.g. 99.73',
        'id'   => $prefix . 'average_exchange_rate',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Currency Description',
        'desc' => 'e.g. Kenyan Shilling (KES)',
        'id'   => $prefix . 'currency_description',
        'type' => 'text_medium',
      ),
    ),
  );


  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );




/**
 * Get Peop;e
 */
function get_countries() {
  $output = '';

  $args = array(
    'numberposts' => -1,
    'post_type' => 'country',
    'orderby' => 'menu_order',
    );

  $country_posts = get_posts($args);
  if (!$country_posts) return false;

  $output = '<ul class="countries-grid">';

  foreach ( $country_posts as $post ):
    $output .= '<li class="country">';
    ob_start();
    include(locate_template('templates/article-country.php'));
    $output .= ob_get_clean();
    $output .= '</li>';
  endforeach;

  $output .= '</ul>';

  return $output;
}
