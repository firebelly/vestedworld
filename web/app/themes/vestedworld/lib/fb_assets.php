<?php

namespace Firebelly\Assets;

/**
 * Scripts and stylesheets
 */

// function crufty_ie_scripts() {
//   // <IE9 js (from http://stackoverflow.com/a/16221114/1001675)
//   $conditional_scripts = [
//     'svg4everybody'       => \Roots\Sage\Assets\asset_path('scripts/respond.js'),
//     'respond'             => \Roots\Sage\Assets\asset_path('scripts/svg4everybody.js')
//   ];
//   foreach ($conditional_scripts as $handle => $src) {
//     wp_enqueue_script($handle, $src, [], null, false);
//   }
//   add_filter('script_loader_tag', function($tag, $handle) use ($conditional_scripts) {
//     return (array_key_exists($handle, $conditional_scripts)) ? "<!--[if lt IE 9]>$tag<![endif]-->\n" : $tag;
//   }, 10, 2 );
// }
// add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\crufty_ie_scripts', 100);

// function scripts() {
// }
// add_action('wp_enqueue_scripts', __NAMESPACE__.'\\scripts', 100);

class ThumbnailUpscaler
{
  /** http://wordpress.stackexchange.com/questions/50649/how-to-scale-up-featured-post-thumbnail **/
  static function image_resize_dimensions($default, $orig_w, $orig_h, $new_w, $new_h, $crop)
  {
    if(!$crop)
      return null; // let the wordpress default function handle this

    $size_ratio = max($new_w / $orig_w, $new_h / $orig_h);

    $crop_w = round($new_w / $size_ratio);
    $crop_h = round($new_h / $size_ratio);

    $s_x = floor( ($orig_w - $crop_w) / 2 );
    $s_y = floor( ($orig_h - $crop_h) / 2 );

    if(is_array($crop)) {

      //Handles left, right and center (no change)
      if($crop[ 0 ] === 'left') {
        $s_x = 0;
      } else if($crop[ 0 ] === 'right') {
        $s_x = $orig_w - $crop_w;
      }

      //Handles top, bottom and center (no change)
      if($crop[ 1 ] === 'top') {
        $s_y = 0;
      } else if($crop[ 1 ] === 'bottom') {
        $s_y = $orig_h - $crop_h;
      }
    }

    return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
  }
}

add_filter('image_resize_dimensions', array(__NAMESPACE__ . '\ThumbnailUpscaler', 'image_resize_dimensions'), 10, 6);
