<?php
namespace Firebelly\Ajax;

/**
 * Add wp_ajax_url variable to global js scope
 */
function wp_ajax_url() {
  wp_localize_script('sage/js', 'wp_ajax_url', admin_url( 'admin-ajax.php'));
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\\wp_ajax_url', 100);

/**
 * Silly ajax helper, returns true if xmlhttprequest
 */
function is_ajax() {
  return (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
}

/**
 * AJAX load more posts
 */
function load_more_posts() {
  $post_type = (!empty($_REQUEST['post_type'])) ? $_REQUEST['post_type'] : 'news';
  // Get page offsets
  $page = !empty($_REQUEST['page']) ? $_REQUEST['page'] : 1;
  $per_page = !empty($_REQUEST['per_page']) ? $_REQUEST['per_page'] : get_option('posts_per_page');
  $offset = ($page-1) * $per_page;
  $args = [
    'offset' => $offset,
    'posts_per_page' => $per_page,
  ];

  // Filter by Category?
  $args['category'] = !empty($_REQUEST['category']) ? (int)$_REQUEST['category'] : '';

  // Search?
  if (!empty($_REQUEST['s'])) {
    $args['s'] = $_REQUEST['s'];
  }

  // Count query for Load More updates (and wp_query likes 'cat' instead of 'category' wtf)
  $count_query = new \WP_Query(array_merge($args, ['cat' => $args['category'], 'posts_per_page' => -1, 'fields' => 'ids']));
  $total_pages = ($count_query->post_count > 0) ? ceil($count_query->post_count / $per_page) : 1;

  // Actually pull posts
  $posts = get_posts($args);

  if ($posts):
		ob_start();
    foreach ($posts as $post) {
      // Set local var for post — avoiding using $post in global namespace
      $news_post = $post;
      include(locate_template('templates/article-'.$post_type.'.php'));
    }
    $posts_html = ob_get_clean();
    wp_send_json_success(['posts_html' => $posts_html, 'total_pages' => $total_pages]);
  else:
  	wp_send_json_success(['posts_html' => '<div class="alert alert-warning"><p>Sorry, no results were found.</p></div>', 'total_pages' => 0]);
  endif;
}
add_action( 'FB_AJAX_load_more_posts', __NAMESPACE__ . '\\load_more_posts' );
add_action( 'FB_AJAX_nopriv_load_more_posts', __NAMESPACE__ . '\\load_more_posts' );

/**
 * Load post in modal
 */
function load_post_modal() {

  if(!empty($_REQUEST['post_url'])) {
    $post_id = url_to_postid($_REQUEST['post_url']);
    if ($post_id) {
      $post = get_post($post_id);
      $post_type = get_post_type($post);
      $page_name = $post->post_name;

      if ($post_type == 'post') {
        $news_post = $post;
        include(locate_template('templates/article-news.php'));
      } elseif ($post_type == 'page') {
        include(locate_template('page-'.$page_name.'.php'));
      } else {
        include(locate_template('templates/content-single-'.$post_type.'.php'));
      }
    } else {
      echo 'Post not found.';
    }
  } else {
    echo 'Post not found.';
  }

  if (is_ajax()) die();
}
add_action( 'FB_AJAX_load_post_modal', __NAMESPACE__ . '\\load_post_modal' );
add_action( 'FB_AJAX_nopriv_load_post_modal', __NAMESPACE__ . '\\load_post_modal' );
