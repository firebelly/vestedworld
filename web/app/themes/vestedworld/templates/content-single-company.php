<?php
// Single Company
$photo = get_the_post_thumbnail($post->ID, 'grid-large');
$headquarters = get_post_meta($post->ID, '_cmb2_headquarters', true);
$industry = get_post_meta($post->ID, '_cmb2_industry', true);
$website = get_post_meta($post->ID, '_cmb2_website', true);
$callout = get_post_meta($post->ID, '_cmb2_callout', true);

$news_links = get_post_meta($post->ID, '_cmb2_news_links', true);
$video_links_parsed = get_post_meta($post->ID, '_cmb2_video_links_parsed', true);
$image_slideshow = get_post_meta($post->ID, '_cmb2_image_slideshow', true);

$body = apply_filters('the_content', $post->post_content);
?>

<article id="<?= $post->post_name ?>" class="grid-item-data single company image-is-logo" data-id="<?= $post->ID ?>" data-page-title="<?= $post->post_title ?>" data-page-url="<?= get_permalink($post) ?>">
  <div class="-left">
    <div class="grid-item-inner">
      <div class="grid-item-image">
        <div class="grid-item-image-inner">
          <h1 class="section-title">Company</h1>
          <?= $photo ?>
        </div>
      </div>
      <div class="grid-item-text">
        <h3 class="tab">Overview</h3>

        <ul class="stats">
          <li><h3>Company Name</h3><?= $post->post_title ?></li>

          <?php if (!empty($headquarters)): ?>
            <li>
              <h3>Headquarters</h3>
              <?= $headquarters ?>
            </li>
          <?php endif; ?>

          <?php if (!empty($website)): ?>
            <li>
              <h3>Website</h3>
              <a target="_blank" href="<?= $website ?>"><?= parse_url($website, PHP_URL_HOST); ?></a>
            </li>
          <?php endif; ?>

          <?php if (!empty($industry)): ?>
            <li>
              <h3>Industry</h3>
              <?= $industry ?>
            </li>
          <?php endif; ?>
        </ul>

        <?php if(!empty($callout)): ?>
          <blockquote class="callout"><p><?= strip_tags($callout) ?></p></blockquote>
        <?php endif; ?>
      </div><!-- END .grid-item-text -->

      <?php if (!empty($image_slideshow) || !empty($video_links_parsed)): ?>
      <div class="grid-item-text">
        <h3 class="tab">Multimedia</h3>
        <?= \Firebelly\Utils\video_slideshow($video_links_parsed) ?>
        <?= \Firebelly\Utils\image_slideshow($image_slideshow) ?>
      </div>
      <?php endif; ?>

      <?php if ($news_links): ?>
      <div class="grid-item-text">
        <h3 class="tab">News</h3>
        <div class="user-content">
          <?= apply_filters('the_content', $news_links) ?>
        </div>
      </div><!-- END .grid-item-text -->
      <?php endif; ?>
    </div><!-- END .grid-item-inner -->
  </div><!-- END .-left -->
  <div class="grid-item-body">
    <h1 class="section-title">Key Information</h1>
    <div class="body-inner">
      <div class="user-content">
        <?= $body ?>
      </div>
    </div>
  </div>
</article>