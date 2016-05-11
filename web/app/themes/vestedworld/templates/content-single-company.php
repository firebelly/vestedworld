<?php
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
              <?= $website ?>
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

      <?php if (!empty($image_slideshow) || !empty($video_links)): ?>
      <div class="grid-item-text">
          <h3 class="tab">Multimedia</h3>
          <?php
            if ($image_slideshow) {
              echo '<ul class="images">';
              foreach ((array)$image_slideshow as $attachment_id => $attachment_url) {
                $large = wp_get_attachment_image_src($attachment_id, 'large');
                $medium = wp_get_attachment_image_src($attachment_id, 'grid-thumb');
                if ($large && $medium) {
                  echo '<li><a href="'.$large[0].'"><img src="'.$medium[0].'"></a></li>';
                }
              }
              echo '</ul>';
            }
          ?>

          <?php
            if ($video_links_parsed) {
              echo '<ul class="videos">';
              $video_lines = explode(PHP_EOL, trim($video_links_parsed));
              foreach ($video_lines as $line) {
                list($vimeo_url,$img_url) = explode('::', $line);
                echo '<li><a href="'.$vimeo_url.'"><img src="'.$img_url.'"></a></li>';
              }
              echo '</ul>';
            }
          ?>
      </div><!-- END .grid-item-text -->
      <?php endif; ?>

      <?php if ($news_links): ?>
      <div class="grid-item-text">
        <h3 class="tab">News</h3>
        <?= apply_filters('the_content', $news_links) ?>
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