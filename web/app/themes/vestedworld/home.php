<?php
/**
 * News landing page
 */

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$per_page = get_option('posts_per_page');
$total_posts = $GLOBALS['wp_query']->found_posts;
$total_pages = ($total_posts > 0) ? ceil($total_posts / $per_page) : 1;
$cat = get_query_var('cat');
// $page = get_page_by_path('/resources/news/'); // if we need this to pull editable metadata from page for og tags/etc

include(locate_template('templates/news-header.php'));
?>

<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <p>Sorry, no results were found.</p>
  </div>
<?php else: ?>
  <div class="article-list load-more-container">
  <?php
    while (have_posts()) : the_post();
      $news_post = $post;
      include(locate_template('templates/article-news.php'));
    endwhile;
  ?>
  </div><!-- END .article-list -->

  <div class="load-more" data-page-at="<?= $paged ?>" data-per-page="<?= $per_page ?>" data-total-pages="<?= $total_pages ?>" data-category="<?= $cat ?>" data-s=""><a href="#">Load More</a></div>

  <?php the_posts_navigation(); ?>
<?php endif; ?>
