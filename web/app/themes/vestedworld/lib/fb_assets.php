<?php

namespace Firebelly\Assets;

/**
 * Scripts and stylesheets
 */

function crufty_ie_scripts() {
  // <IE9 js (from http://stackoverflow.com/a/16221114/1001675)
  $conditional_scripts = [
    'svg4everybody'       => \Roots\Sage\Assets\asset_path('scripts/respond.js'),
    'respond'             => \Roots\Sage\Assets\asset_path('scripts/svg4everybody.js')
  ];
  foreach ($conditional_scripts as $handle => $src) {
    wp_enqueue_script($handle, $src, [], null, false);
  }
  add_filter('script_loader_tag', function($tag, $handle) use ($conditional_scripts) {
    return (array_key_exists($handle, $conditional_scripts)) ? "<!--[if lt IE 9]>$tag<![endif]-->\n" : $tag;
  }, 10, 2 );
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\crufty_ie_scripts', 100);

function scripts() {
  
}
add_action('wp_enqueue_scripts', __NAMESPACE__.'\\scripts', 100);
