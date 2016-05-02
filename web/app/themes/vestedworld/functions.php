<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
  'lib/assets.php',  // Scripts and stylesheets
  'lib/extras.php',  // Custom functions
  'lib/setup.php',   // Theme setup
  'lib/titles.php',  // Page titles
  'lib/wrapper.php'  // Theme wrapper class
];

$firebelly_includes = [
  'lib/disable-comments.php',          // Disables WP comments in admin and frontend
  'lib/fb_init.php',                   // FB theme setups
  'lib/fb_assets.php',                 // FB assets
  'lib/fb_metatags.php',               // FB metatags / ogtags
  'lib/media.php',                     // FB media
  'lib/ajax.php',                      // AJAX functions
  'lib/custom-functions.php',          // Rando utility functions and miscellany
  'lib/cmb2-custom-fields.php',        // Custom CMB2
  'lib/person-post-type.php',          // People
  'lib/faq-post-type.php',             // FAQs
  'lib/glossary-post-type.php',        // Glossaries
  'lib/headline-post-type.php',        // Headlines
  'lib/applicant-post-type.php',       // Applicants
  //'lib/company-post-type.php',       // Companies
  //'lib/country-post-type.php',       // Countries
  'lib/featured.php',                  // Featured Post Capability
  'lib/page-meta-boxes.php',           // Various tweaks for pages
  'lib/site-options.php',              // Site Options page with CMB2 fields
  // 'lib/post-meta-boxes.php',        // Various tweaks for posts
];

$sage_includes = array_merge($sage_includes, $firebelly_includes);

foreach ($sage_includes as $file) {
  if (!$filepath = locate_template($file)) {
    trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
  }

  require_once $filepath;
}
unset($file, $filepath);
