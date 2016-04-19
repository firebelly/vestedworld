<?php
/**
 * News landing page
 */

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
$per_page = get_option('posts_per_page');
$total_posts = $GLOBALS['wp_query']->found_posts;
$total_pages = ($total_posts > 0) ? ceil($total_posts / $per_page) : 1;
$page = get_page_by_path('/resources/news/'); // may use this down the line to pull editable metadata from page for og tags/etc
?>

<header class="news-filter">
  <form action="<?= get_permalink(get_option('page_for_posts')) ?>" method="get">
    <h1>News</h1>
    <fieldset>
      <div class="filter">
        <label>Filter</label>
        <?php wp_dropdown_categories(); ?>
      </div>
      <div class="search">
        <label class="sr-only">Search</label>
        <input type="text" name="s" placeholder="Search">
      </div>
    </fieldset>
  </form>
</header>

<?php if (!have_posts()) : ?>
  <div class="alert alert-warning">
    <?php _e('Sorry, no results were found.', 'sage'); ?>
  </div>
<?php else: ?>
  <div class="article-list">
  <?php
    while (have_posts()) : the_post();
      $news_post = $post;
      include(locate_template('templates/article-news.php'));
    endwhile;
  ?>

  <?php if ($total_pages>1): ?>
    <div class="load-more" data-page-at="<?= $paged ?>" data-per-page="<?= $per_page ?>" data-total-pages="<?= $total_pages ?>"><a href="#"><span>Load More News</span> <span><button class="plus-button"><div class="plus"></div></button></span></a></div>
  <?php endif ?>

  </div><!-- END .article-list -->

  <?php the_posts_navigation(); ?>
<?php endif; ?>
