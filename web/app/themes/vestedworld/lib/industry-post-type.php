<?php
/**
 * Industry post type
 */

namespace Firebelly\PostTypes\Industry;
use Firebelly\Utils;

function get_income_levels() {
  return [
    'low'           => 'Low',
    'lower_middle'  => 'Lower Middle',
    'higher_middle' => 'Higher Middle',
    'high'          => 'High',
  ];
}

/**
 * Register Custom Post Type
 */
function post_type() {

  $labels = array(
    'name'                => 'Industries',
    'singular_name'       => 'Industry',
    'menu_name'           => 'Industries',
    'parent_item_colon'   => '',
    'all_items'           => 'All Industries',
    'view_item'           => 'View Industry',
    'add_new_item'        => 'Add New Industry',
    'add_new'             => 'Add New',
    'edit_item'           => 'Edit Industry',
    'update_item'         => 'Update Industry',
    'search_items'        => 'Search Industries',
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
    'label'               => 'industry',
    'description'         => 'Industry Profiles',
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
  register_post_type( 'industry', $args );

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
add_filter('manage_industry_posts_columns', __NAMESPACE__ . '\edit_columns');

function custom_columns($column){
  global $post;
  if ( $post->post_type == 'industry' ) {
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
  $meta_boxes['industry_details'] = array(
    'id'            => 'industry_details',
    'title'         => __( 'Details', 'cmb2' ),
    'object_types'  => array( 'industry', ),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    'fields'        => array(
      array(
        'name' => 'Timeline',
        'id'   => $prefix . 'timeline',
        'type' => 'timeline_date',
        'repeatable' => true,
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
    )
  );

  // Statistics
  $meta_boxes['industry_statistics'] = array(
    'id'            => 'industry_statistics',
    'title'         => __( 'Statistics', 'cmb2' ),
    'object_types'  => array( 'industry', ),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    'fields'        => array(
      array(
        'name' => 'Regional Label',
        'desc' => 'e.g. Sub-Saharan Africa; Latin America; Developing World',
        'id'   => $prefix . 'regional_label',
        'type' => 'text_medium',
      ),
      array(
        'name' => 'Total Current Size (Region)',
        'desc' => 'e.g. 313B',
        'id'   => $prefix . 'total_current_size_region',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Total Current Size (World)',
        'desc' => 'e.g. 5T',
        'id'   => $prefix . 'total_current_size_world',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Expected Size by 2030 (Region)',
        'desc' => 'e.g. 1T',
        'id'   => $prefix . 'expected_size_region',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Expected Size by 2030 (World)',
        'id'   => $prefix . 'expected_size_world',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Percentage of GDP (Region)',
        'desc' => 'e.g. 32',
        'id'   => $prefix . 'percentage_gdp_region',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Percentage of GDP (World)',
        'id'   => $prefix . 'percentage_gdp_world',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Percentage of Workforce (Region)',
        'id'   => $prefix . 'percentage_workforce_region',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Percentage of Workforce (World)',
        'id'   => $prefix . 'percentage_workforce_world',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Percentage of Consumer Spending (Region)',
        'id'   => $prefix . 'percentage_consumer_spending_region',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Percentage of Consumer Spending (World)',
        'id'   => $prefix . 'percentage_consumer_spending_world',
        'type' => 'text_small',
      ),
    ),
  );

  // Industry Content
  $meta_boxes['industry_content'] = array(
    'id'            => 'industry_content',
    'title'         => __( 'Subsectors', 'cmb2' ),
    'object_types'  => array( 'industry', ),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    'fields'        => array(
      array(
        'name' => 'Subsectors',
        'id'   => $prefix . 'subsectors',
        'type' => 'wysiwyg',
        'options' => array(
          'textarea_rows' => 8,
        ),
      ),
      array(
        'name' => 'Global Leaders',
        'id'   => $prefix . 'global_leaders',
        'type' => 'wysiwyg',
        'options' => array(
          'textarea_rows' => 8,
        ),
      ),
      array(
        'name' => 'Trends',
        'id'   => $prefix . 'trends',
        'type' => 'wysiwyg',
        'options' => array(
          'textarea_rows' => 8,
        ),
      ),
      array(
        'name' => 'Risks & Challenges',
        'id'   => $prefix . 'risks_challenges',
        'type' => 'wysiwyg',
        'options' => array(
          'textarea_rows' => 8,
        ),
      ),
      array(
        'name' => 'Future Outlook',
        'id'   => $prefix . 'future_outlook',
        'type' => 'wysiwyg',
        'options' => array(
          'textarea_rows' => 8,
        ),
      ),
    )
  );

  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );


/**
 * Get Industries
 */
function get_industries() {
  $output = '';

  $args = array(
    'numberposts' => -1,
    'post_type' => 'industry',
    'orderby' => 'menu_order',
    );

  $industry_posts = get_posts($args);
  if (!$industry_posts) return false;

  $output = '<ul class="grid-items industries-grid">';
  ob_start();
  foreach ( $industry_posts as $post ):
    echo '<li class="grid-item industry">';
    include(locate_template('templates/article-industry.php'));
    echo '</li>';
  endforeach;
  $output .= ob_get_clean();
  $output .= '</ul>';

  return $output;
}
