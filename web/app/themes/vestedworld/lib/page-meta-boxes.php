<?php
/**
 * Extra fields for Pages
 */

namespace Firebelly\PostTypes\Pages;

 // Custom CMB2 fields for post type
 function metaboxes( array $meta_boxes ) {
   $prefix = '_cmb2_'; // Start with underscore to hide from custom fields list

   $meta_boxes['page_approach'] = array(
     'id'            => 'page_approach',
     'title'         => __( 'Our Approach', 'cmb2' ),
     'object_types'  => array( 'page', ), // Post type
     'context'       => 'normal',
     'show_on'       => array( 'key' => 'page-template', 'value' => 'front-page.php'),
     'priority'      => 'high',
     'show_names'    => true, // Show field names on the left
     'fields'        => array(
    
       // General page fields
       array(
         'name' => 'Target',
         'desc' => 'Text under "Target" header on home page',
         'id'   => $prefix . 'target',
         'type' => 'textarea',
       ),
       array(
         'name' => 'Connect',
         'desc' => 'Copy under "Connect" header on home page',
         'id'   => $prefix . 'connect',
         'type' => 'textarea',
       ),
       array(
         'name' => 'Invest',
         'desc' => 'Copy under "Invest" header on home page',
         'id'   => $prefix . 'invest',
         'type' => 'textarea',
       ),
     ),
   );

   $meta_boxes['page_about_summary'] = array(
     'id'            => 'page_about_summary',
     'title'         => __( 'Summary Area', 'cmb2' ),
     'object_types'  => array( 'page', ), // Post type
     'context'       => 'normal',
     'show_on'       => array( 'key' => 'page-template', 'value' => 'templates/about-article.php'),
     'priority'      => 'high',
     'show_names'    => true, // Show field names on the left
     'fields'        => array(
       // General page fields
       array(
         'name' => 'Headline',
         'desc' => 'Headline on the left',
         'id'   => $prefix . 'headline',
         'type' => 'text',
       ),
       array(
         'name' => 'Summary',
         'desc' => 'Copy under left headline, summarizing article (optional)',
         'id'   => $prefix . 'summary',
         'type' => 'textarea',
       ),
     ),
   );


   return $meta_boxes;
 }
 add_filter( 'cmb2_meta_boxes', __NAMESPACE__ . '\metaboxes' );

/**
 * Hide editor on home page -- dont need it.
 * adapted from: https://gist.github.com/ramseyp/4060095
 */
add_action( 'do_meta_boxes', __NAMESPACE__ . '\hide_editor' );
function hide_editor() {
  	//leave if we are not editing a page...
  	if( get_current_screen()->id != 'page') return;

  	// Get the Post ID.
  	if (isset($_GET['post'])) 
  		$post_id = $_GET['post'];
  	elseif (isset($_POST['post_ID']))
  		$post_id = $_POST['post_ID'];
  	else $post_id = false;

  	//No post id? (e.g. editing a new, unsaved page) Get outta here!
  	if(!$post_id) return;
  
  	// Hide the editor on a page with a specific page template
  	// Get the name of the Page Template file.
  	$template_file = get_post_meta($post_id, '_wp_page_template', true); 
  	$post = get_post($post_id);
  	$slug = $post->post_name;
  	$exclude_on = array( // any template filenames or slugs here will be have the editor excluded
  		'front-page.php',
  		'about-us',
  		'resources',
      'faqs',
  		);
  	if(in_array($template_file,$exclude_on) || in_array($slug,$exclude_on)){ 
    	remove_post_type_support('page', 'editor');
  	}
}

//Notice: Undefined index: post_ID in /Users/bryant/Sites/vestedworld/web/app/themes/vestedworld/lib/page-meta-boxes.php on line 59

