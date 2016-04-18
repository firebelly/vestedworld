<?php
/**
 * Applicant post type
 */

namespace Firebelly\PostTypes\Applicant;

// Register Custom Post Type
function post_type() {

  $labels = array(
    'name'                => 'Applicants',
    'singular_name'       => 'Applicant',
    'menu_name'           => 'Applicants',
    'parent_item_colon'   => '',
    'all_items'           => 'All Applicants',
    'view_item'           => 'View Applicant',
    'add_new_item'        => 'Add New Applicant',
    'add_new'             => 'Add New',
    'edit_item'           => 'Edit Applicant',
    'update_item'         => 'Update Applicant',
    'search_items'        => 'Search Applicants',
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
    'label'               => 'applicant',
    'description'         => 'Applicants',
    'labels'              => $labels,
    'supports'            => array( 'title', ),
    'hierarchical'        => false,
    'public'              => true,
    'show_ui'             => true,
    'show_in_menu'        => true,
    'show_in_nav_menus'   => true,
    'show_in_admin_bar'   => true,
    'menu_applicant'       => 20,
    'menu_icon'           => 'dashicons-admin-post',
    'can_export'          => false,
    'has_archive'         => false,
    'exclude_from_search' => true,
    'publicly_queryable'  => true,
    'rewrite'             => $rewrite,
    'capability_type'     => 'applicant',
    'map_meta_cap'        => true
  );
  register_post_type( 'applicant', $args );

}
add_action( 'init', __NAMESPACE__ . '\post_type', 0 );

/**
 * Add capabilities to control permissions of Post Type via roles
 */
function add_capabilities() {
  $role_admin = get_role('administrator');
  $role_admin->add_cap('edit_applicant');
  $role_admin->add_cap('read_applicant');
  $role_admin->add_cap('delete_applicant');
  $role_admin->add_cap('edit_applicants');
  $role_admin->add_cap('edit_others_applicants');
  $role_admin->add_cap('publish_applicants');
  $role_admin->add_cap('read_private_applicants');
  $role_admin->add_cap('delete_applicants');
  $role_admin->add_cap('delete_private_applicants');
  $role_admin->add_cap('delete_published_applicants');
  $role_admin->add_cap('delete_others_applicants');
  $role_admin->add_cap('edit_private_applicants');
  $role_admin->add_cap('edit_published_applicants');
  $role_admin->add_cap('create_applicants');
}
add_action('load-themes.php', __NAMESPACE__ . '\add_capabilities');

// Custom admin columns for post type
function edit_columns($columns){
  $columns = array(
    'cb' => '<input type="checkbox" />',
    'title' => 'Title',
    '_cmb2_application_type' => 'Applicant Type',
    '_cmb2_accredited' => 'Accredited Investor?',
    'date' => 'Date',
  );
  return $columns;
}
add_filter('manage_applicant_posts_columns', __NAMESPACE__ . '\edit_columns');

function custom_columns($column){
  global $post;
  if ( $post->post_type == 'applicant' ) {
    if ( $column == 'featured_image' ) {
      echo the_post_thumbnail('thumbnail');
    } else {
      $custom = get_post_custom();
      if (array_key_exists($column, $custom))
        echo $custom[$column][0];
    }
  }
}
add_action('manage_posts_custom_column',  __NAMESPACE__ . '\custom_columns');

// Custom CMB2 fields for post type
function metaboxes( array $meta_boxes ) {
  $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

  $meta_boxes['applicant_details'] = array(
    'id'            => 'applicant_details',
    'title'         => __( 'Details', 'cmb2' ),
    'object_types'  => array( 'applicant', ),
    'context'       => 'normal',
    'priority'      => 'high',
    'show_names'    => true,
    'fields'        => array(
      array(
        'name' => 'First Name',
        'id'   => $prefix . 'first_name',
        'type' => 'text',
      ),
      array(
        'name' => 'Last Name',
        'id'   => $prefix . 'last_name',
        'type' => 'text',
      ),
      array(
        'name' => 'Email',
        'id'   => $prefix . 'email',
        'type' => 'text',
      ),
      array(
        'name' => 'Phone',
        'id'   => $prefix . 'phone',
        'type' => 'text',
      ),
      array(
        'name' => 'Company',
        'id'   => $prefix . 'company',
        'type' => 'text',
      ),
      array(
        'name' => 'Application Type',
        'id'   => $prefix . 'application_type',
        'type' => 'text',
      ),
      array(
        'name' => 'Accredited Investor',
        'id'   => $prefix . 'accredited',
        'type' => 'radio',
        'options'          => array(
          'yes' => __( 'Yes', 'cmb2' ),
          'no'   => __( 'No', 'cmb2' ),
        ),
      ),
      array(
        'name' => 'Staff Notes',
        'desc' => 'Not shown on front end of site',
        'id'   => $prefix . 'notes',
        'type' => 'wysiwyg',
        'options' => array(
          'textarea_rows' => 6,
        ),
      ),
    ),
  );

  return $meta_boxes;
}
add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );

function has_applied($email, $application_type='Sitewide Dropdown', $position_id='') {
  $args = [
    'post_status' => 'all',
    'post_type' => 'applicant',
    'meta_query' => [
      [
        'key' => '_cmb2_email',
        'value' => $email,
        'compare' => '='
      ],
      [
        'key' => '_cmb2_application_type',
        'value' => $application_type,
        'compare' => '='
      ]
    ]
  ];
  return get_posts($args);
}

function new_applicant() {
  $errors = [];
  $notification_email = \Firebelly\SiteOptions\get_option('notification_email', 'nate@firebellydesign.com');
  $application_type = $_POST['application_type'];
  $name = $_POST['application_first_name'] .' '. $_POST['application_last_name'];

  if (has_applied($_POST['application_email'], $application_type)) {
    return ["We've already received your application."];
  }

  $applicant_post = array(
    'post_title'    => 'Application from ' . $name,
    'post_type'     => 'applicant',
    'post_author'   => 1,
    'post_status'   => 'publish',
  );
  $post_id = wp_insert_post($applicant_post);
  if ($post_id) {

    update_post_meta($post_id, '_cmb2_application_type', $application_type);
    update_post_meta($post_id, '_cmb2_first_name', $_POST['application_first_name']);
    update_post_meta($post_id, '_cmb2_last_name', $_POST['application_last_name']);
    update_post_meta($post_id, '_cmb2_email', $_POST['application_email']);
    update_post_meta($post_id, '_cmb2_phone', $_POST['application_phone']);
    update_post_meta($post_id, '_cmb2_company', $_POST['application_company']);
    update_post_meta($post_id, '_cmb2_accredited', $_POST['application_accredited']);

    // Send email if notification_email was set
    if ($notification_email) {
      $subject = 'New lead ('.$application_type.'): '.$name;
      $headers = ['From: VestedWorld <www-data@vestedworld.com>'];
      $message .= "You’ve got a new lead:\n\n";
      $message .= $name . "\n";
      $message .= 'Email: ' . $_POST['application_email'] . "\n";
      $message .= 'Phone: ' . $_POST['application_phone'] . "\n";
      if (!empty($_POST['application_company']))
        $message .= 'Company: ' . $_POST['application_company'] . "\n";
      $message .= 'Accredited Investor: ' . $_POST['application_accredited'] . "\n";
      $message .= "\nEdit in WordPress:\n" . get_edit_post_link($post_id, 'email') . "\n";
      wp_mail($notification_email, $subject, $message, $headers);
    }

    // Send quick receipt email to applicant
    $applicant_message = "Thank you for your interest in VestedWorld. We’ll be in touch soon with next steps for joining our community.";
    wp_mail($_POST['application_email'], 'Thank you for your interest in VestedWorld', $applicant_message, ['From: VestedWorld <info@vestedworld.com>']);

  } else {
    $errors[] = 'Error inserting post';
  }

  if (empty($errors)) {
    return true;
  } else {
    return $errors;
  }
}


/**
 * AJAX Application submissions
 */
function application_submission() {
  if($_SERVER['REQUEST_METHOD']==='POST' && !empty($_POST['application_form_nonce'])) {
    if (wp_verify_nonce($_POST['application_form_nonce'], 'application_form')) {

      // Server side validation of required fields
      $required_fields = ['application_type',
                          'application_first_name',
                          'application_last_name',
                          'application_email',
                          'application_phone'];
      foreach($required_fields as $required) {
        if (empty($_POST[$required])) {
          $required_txt = ucwords(str_replace('_', ' ', str_replace('application_','',$required)));
          wp_send_json_error(['message' => 'Please enter a value for '.$required_txt]);
        }
      }

      // Check for valid Email
      if (!is_email($_POST['application_email'])) {
        wp_send_json_error(['message' => 'Invalid email']);
      } else {

        // Try to save new Applicant post
        $return = new_applicant();
        if (is_array($return)) {
          wp_send_json_error(['message' => 'There was an error: '.implode("\n", $return)]);
        } else {
          wp_send_json_success(['message' => 'Application was saved OK']);
        }

      }
    } else {
      // Bad nonce, man!
      wp_send_json_error(['message' => 'Invalid form submission (bad nonce)']);
    }
  }
  wp_send_json_error(['message' => 'Invalid post']);
}
add_action('FB_AJAX_application_submission', __NAMESPACE__ . '\\application_submission');
add_action('FB_AJAX_nopriv_application_submission', __NAMESPACE__ . '\\application_submission');
