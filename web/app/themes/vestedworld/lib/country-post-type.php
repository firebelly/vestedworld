<?php
/**
 * Country post type
 */

namespace Firebelly\PostTypes\Country;
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
    'object_types'  => array( 'country', ),
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
    'object_types'  => array( 'country', ),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    'fields'        => array(
      array(
        'name' => 'Intro Text',
        'id'   => $prefix . 'country_overview_intro',
        'type' => 'wysiwyg',
        'options' => [ 'textarea_rows' => 6 ],
      ),
      array(
        'name' => 'Current Population',
        'desc' => 'in millions, e.g. 41.6',
        'id'   => $prefix . 'population',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Projected Population by 2050',
        'desc' => 'in millions, e.g. 80.1',
        'id'   => $prefix . 'projected_population',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Median Age',
        'id'   => $prefix . 'median_age',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Poverty',
        'desc' => 'percentage, e.g. 43.4',
        'id'   => $prefix . 'poverty',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Poverty Label',
        'desc' => 'e.g. Poverty (2012)',
        'id'   => $prefix . 'poverty_label',
        'type' => 'text',
      ),
      array(
        'name' => 'Workforce Participation',
        'desc' => 'percentage, e.g. 67.4',
        'id'   => $prefix . 'workforce_participation',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Income Level Classification',
        'id'   => $prefix . 'income_level_classification',
        'type' => 'radio_inline',
        'options' => get_income_levels()
      ),
    ),
  );

  // Economic Outlook
  $meta_boxes['country_economic_outlook'] = array(
    'id'            => 'country_economic_outlook',
    'title'         => __( 'Economic Outlook', 'cmb2' ),
    'object_types'  => array( 'country', ),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    'fields'        => array(
      array(
        'name' => 'Intro Text',
        'id'   => $prefix . 'economic_outlook_intro',
        'type' => 'wysiwyg',
        'options' => [ 'textarea_rows' => 6 ],
      ),
      array(
        'name' => 'Gross GDP',
        'desc' => 'in billions, e.g. 63.1',
        'id'   => $prefix . 'gross_gdp',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Per Capita GDP',
        'desc' => 'e.g. 3300',
        'id'   => $prefix . 'per_capita_gdp',
        'type' => 'text_small',
      ),
      array(
        'name' => 'GDP Growth Percentage',
        'desc' => 'e.g. 5.4',
        'id'   => $prefix . 'gdp_growth',
        'type' => 'text_small',
      ),
      array(
        'name' => 'GDP Growth Comparison 1 Percentage',
        'desc' => 'e.g. 3.4 (used for chart on single Country pages)',
        'id'   => $prefix . 'gdp_growth_comparison_1',
        'type' => 'text_small',
      ),
      array(
        'name' => 'GDP Growth Comparison 1 Label',
        'desc' => 'e.g. Sub-Saharan Africa',
        'id'   => $prefix . 'gdp_growth_comparison_1_label',
        'type' => 'text',
      ),
      array(
        'name' => 'GDP Growth Comparison 2 Percentage',
        'desc' => 'e.g. 2.4',
        'id'   => $prefix . 'gdp_growth_comparison_2',
        'type' => 'text_small',
      ),
      array(
        'name' => 'GDP Growth Comparison 2 Label',
        'desc' => 'e.g. World',
        'id'   => $prefix . 'gdp_growth_comparison_2_label',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Inflation',
        'desc' => 'percentage, e.g. 6.4',
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
        'name' => '3-letter Currency Code',
        'desc' => 'e.g. KES—used for API calls and shown next to Avg Exchange Rate on single countries',
        'id'   => $prefix . 'currency_code',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Current Average Exchange Rate',
        'desc' => 'Updated automatically from CurrencyLayer API, based on Currency Code above',
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

  // Key Sectors
  $meta_boxes['country_key_sectors'] = array(
    'id'            => 'country_key_sectors',
    'title'         => __( 'Key Sectors', 'cmb2' ),
    'object_types'  => array( 'country', ),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    'fields'        => array(
      array(
        'name' => 'Intro Text',
        'id'   => $prefix . 'key_sectors_intro',
        'type' => 'wysiwyg',
        'options' => [ 'textarea_rows' => 6 ],
      ),
      array(
        'name' => 'Agriculture % of GDP',
        'id'   => $prefix . 'gdp_percent_agriculture',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Service % of GDP',
        'id'   => $prefix . 'gdp_percent_service',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Industry % of GDP',
        'id'   => $prefix . 'gdp_percent_industry',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Agriculture % of Workforce',
        'id'   => $prefix . 'workforce_percent_agriculture',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Service % of Workforce',
        'id'   => $prefix . 'workforce_percent_service',
        'type' => 'text_small',
      ),
      array(
        'name' => 'Industry % of Workforce',
        'id'   => $prefix . 'workforce_percent_industry',
        'type' => 'text_small',
      ),
    ),
  );


  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );


/**
 * Get Countries
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

  $output = '<ul class="grid-items countries-grid">';
  ob_start();
  foreach ( $country_posts as $post ):
    echo '<li class="grid-item country">';
    include(locate_template('templates/article-country.php'));
    echo '</li>';
  endforeach;
  $output .= ob_get_clean();
  $output .= '</ul>';

  return $output;
}

/**
 * Use CurrencyLayer API to update Average Currency Rate for each country
 */
function update_exchange_rates() {
  $currency_code_arr = [];

  // Get Currency Layer API Key
  $currency_layer_api_key = getenv('CURRENCY_LAYER_API_KEY');
  if (!$currency_layer_api_key) return;

  // Pull all Country posts
  $country_posts = get_posts([ 'numberposts' => -1, 'post_type' => 'country' ]);
  if (!$country_posts) return;

  // Loop through countries and pull currency_code values into array
  foreach($country_posts as $country_post) {
    $currency_code = get_post_meta($country_post->ID, '_cmb2_currency_code', true);
    if ($currency_code) {
      $currency_code_arr[$currency_code] = $country_post->ID;
    }
  }
  if (empty($currency_code_arr)) return;

  // Call Currency Layer API
  $ch = curl_init('http://apilayer.net/api/live?access_key=' . $currency_layer_api_key . '&currencies=' . implode(',', array_keys($currency_code_arr)) . '&source=USD&format=1');
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  $json = curl_exec($ch);
  curl_close($ch);

  // Decode JSON response:
  $exchangeRates = json_decode($json, true);

  // debug
  wp_mail('nate@firebellydesign.com', 'VestedWorld CurrencyLayer cron', print_r($exchangeRates,1));

  // Update each country's exchange rate
  foreach ($exchangeRates['quotes'] as $key => $value) {
    update_post_meta($currency_code_arr[str_replace('USD','',$key)], '_cmb2_average_exchange_rate', sprintf('%0.2f', $value));
  }
}

// Register cronjob
register_activation_hook(__FILE__, __NAMESPACE__ . '\activate_cron');
function activate_cron() {
  if (!wp_next_scheduled('update_country_exchange_rates')) {
    wp_schedule_event(time(), 'twicedaily', 'update_country_exchange_rates');
  }
}
add_action('update_country_exchange_rates', __NAMESPACE__ . '\update_exchange_rates');
