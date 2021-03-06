<?php

namespace Firebelly\Utils;

/**
 * Bump up # search results
 */
function search_queries( $query ) {
  if ( !is_admin() && is_search() ) {
    $query->set( 'posts_per_page', 40 );
  }
  return $query;
}
add_filter( 'pre_get_posts', __NAMESPACE__ . '\\search_queries' );

/**
 * Custom li'l excerpt function
 */
function get_excerpt( $post, $length=30, $force_content=false ) {
  $excerpt = trim($post->post_excerpt);
  if (!$excerpt || $force_content) {
    $excerpt = $post->post_content;
    $excerpt = strip_shortcodes( $excerpt );
    $excerpt = apply_filters( 'the_content', $excerpt );
    $excerpt = str_replace( ']]>', ']]&gt;', $excerpt );
    $excerpt_length = apply_filters( 'excerpt_length', $length );
    $excerpt = wp_trim_words( $excerpt, $excerpt_length );
  }
  return $excerpt;
}

/**
 * Get top ancestor for post
 */
function get_top_ancestor($post){
  if (!$post) return;
  $ancestors = $post->ancestors;
  if ($ancestors) {
    return end($ancestors);
  } else {
    return $post->ID;
  }
}

/**
 * Get first term for post
 */
function get_first_term($post, $taxonomy='category') {
  $return = false;
  if ($terms = get_the_terms($post->ID, $taxonomy))
    $return = array_pop($terms);
  return $return;
}

/**
 * Get page content from slug
 */
function get_page_content($slug) {
  $return = false;
  if ($page = get_page_by_path($slug))
    $return = apply_filters('the_content', $page->post_content);
  return $return;
}

/**
 * Get category for post
 */
function get_category($post) {
  if ($category = get_the_category($post)) {
    return $category[0];
  } else return false;
}

/**
 * Get num_pages for category given slug + per_page
 */
function get_total_pages($category, $per_page) {
  $cat_info = get_category_by_slug($category);
  $num_pages = ceil($cat_info->count / $per_page);
  return $num_pages;
}

/**
 * Get Page Blocks
 */
function get_page_blocks($post) {
  $output = '';
  $page_blocks = get_post_meta($post->ID, '_cmb2_page_blocks', true);
  if ($page_blocks) {
    foreach ($page_blocks as $page_block) {
      if (empty($page_block['hide_block'])) {
        $block_title = $block_body = '';
        if (!empty($page_block['title']))
          $block_title = $page_block['title'];
        if (!empty($page_block['body'])) {
          $block_body = apply_filters('the_content', $page_block['body']);
          $output .= '<div class="page-block">';
          if ($block_title) {
            $output .= '<h2 class="flag">' . $block_title . '</h2>';
          }
          $output .= '<div class="user-content">' . $block_body . '</div>';
          $output .= '</div>';
        }
      }
    }
  }
  return $output;
}

/**
 * Shortcode to get query var (or default)
 * [querystring param="foo" default="bar"]
 */
function get_query_string( $atts ) {
  extract(shortcode_atts(array(
       'param' => '',
       'default_value' => '',
    ), $atts));

  if (!empty($_GET[$param])) {
    return $_GET[$param];
  } else if (empty($_GET[$param]) && !empty($default_value)) {
    return $default_value;
  } else {
    return '';
  }
}
add_shortcode( 'querystring', __NAMESPACE__ .'\get_query_string' );

/**
 * Get Parent URL for a Post
 */
function get_parent_url($post) {
  $parent_url = '/';

  if ($post->post_type == 'company') {
    $parent_url = '/portfolio/';
  } elseif ($post->post_type == 'country') {
    $parent_url = '/resources/country-profiles/';
  } elseif ($post->post_type == 'industry') {
    $parent_url = '/resources/industry-profiles/';
  } elseif ($post->post_type == 'person') {
    $type = get_post_meta($post->ID, '_cmb2_member_type', true);
    if ($type) {
      if (preg_match('/(management)|(board)/i', $type)) {
        $parent_url = '/about-us/';
      } else {
        $parent_url = '/community/';
      }
    }
  }

  return $parent_url;
}

/**
 * Spit out video slideshow
 */
function video_slideshow($video_links_parsed) {
  $output = '';
  if ($video_links_parsed) {
    $output .= '<div class="videos slider-mini">';
    $video_lines = explode(PHP_EOL, trim($video_links_parsed));
    foreach ($video_lines as $line) {
      list($vimeo_url,$img_url,$title) = explode('¶', $line);
      $output .= '<div class="slide-item"><a class="lightbox" href="'.$vimeo_url.'" title="'.$title.'"><img src="'.$img_url.'" title="'.$title.'"><span>Watch Video</span></a></div>';
    }
    $output .= '</div>';
  }
  return $output;
}

/**
 * Spit out image slideshow
 */
function image_slideshow($image_slideshow) {
  $output = '';
  if ($image_slideshow) {
    $output .= '<div class="images slider-mini">';
    foreach ((array)$image_slideshow as $attachment_id => $attachment_url) {
      $medium = wp_get_attachment_image_src($attachment_id, 'grid-thumb');
      if ($medium) {
        $title = get_post_field('post_excerpt', $attachment_id);
        $large = wp_get_attachment_image_src($attachment_id, 'large');
        if ($large):
          $output .= '<div class="slide-item"><a class="lightbox" rel="gallery" href="'.$large[0].'" title="'.$title.'"><img src="'.$medium[0].'" title="'.$title.'"></a></div>';
        else:
          $output .= '<div class="slide-item"><img src="'.$medium[0].'" title="'.$caption.'"></div>';
        endif;
      }
    }
    $output .= '</div>';
  }
  return $output;
}
