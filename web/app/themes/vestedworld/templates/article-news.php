<?php
$category = \Firebelly\Utils\get_category($news_post);
$post_date_timestamp = strtotime($news_post->post_date);
$featured_image = \Firebelly\Media\get_post_thumbnail($news_post->ID, 'large');
?>
<article <?php post_class('article', $news_post->ID); ?> data-id="<?= $post->ID ?>" data-page-title="<?= $post->post_title ?>" data-page-url="<?= get_permalink($post) ?>">
  <h3 class="tab"><time class="article-date" datetime="<?= date('c', $post_date_timestamp); ?>"><?= date('m/d/Y', $post_date_timestamp); ?></time></h3>
  <div class="image-wrap">
  <?php if ($featured_image): ?>
    <img src="<?= $featured_image ?>">
  <?php endif; ?>
  </div>
  <div class="article-body">
    <header>
      <?php if ($category): ?><h3 class="article-category"><?= $category->name; ?></h3><?php endif; ?>
      <h1 class="article-title"><?php if (!is_single()) echo '<a href="'.get_the_permalink($news_post).'">' ?><?= $news_post->post_title ?><?php if (!is_single()) echo '</a>' ?></h1>
    </header>
    <div class="entry-content">
      <?php if (is_single()): ?>
        <?= apply_filters('the_content', $news_post->post_content) ?>
      <?php else:
        $post_extended = get_extended($news_post->post_content);
        echo apply_filters('the_content', $post_extended['main']);
        if (!empty($post_extended['extended'])) {
          echo '<div class="post-extended">' . $post_extended['extended'] . '</div>';
          echo '<a class="button read-more" href="'.get_the_permalink($news_post).'">Read More</a>';
        }
      ?>
      <?php endif; ?>
    </div>
  </div>
</article>
