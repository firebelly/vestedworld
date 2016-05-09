<?php
$photo = get_the_post_thumbnail($post->ID, 'medium');
$headquarters = get_post_meta($post->ID, '_cmb2_headquarters', true);
$industry = get_post_meta($post->ID, '_cmb2_industry', true);
$website = get_post_meta($post->ID, '_cmb2_website', true);
$callout = get_post_meta($post->ID, '_cmb2_callout', true);

$video_links = get_post_meta($post->ID, '_cmb2_video_links', true);
$image_slideshow = get_post_meta($post->ID, '_cmb2_image_slideshow', true);

$body = apply_filters('the_content', $post->post_content);
?>

<article id="<?= $post->post_name ?>" class="grid-item-data">
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
            <div class="callout"><?= $callout ?></div>
          <?php endif; ?>

          <?php
            if ($image_slideshow) {
              echo '<ul class="images">';
              foreach ((array)$image_slideshow as $attachment_id => $attachment_url) {
                $large = wp_get_attachment_image_src($attachment_id, 'large');
                $medium = wp_get_attachment_image_src($attachment_id, 'medium');
                if ($large && $medium) {
                  echo '<li><a href="'.$large[0].'"><img src="'.$medium[0].'"></a></li>';
                }
              }
              echo '</ul>';
            }
          ?>

          <?php
            if ($video_links) {
              echo '<ul class="videos">';
              $video_lines = explode(PHP_EOL, trim($video_links));
              $new_video_links = '';
              foreach ($video_lines as $line) {
                // Have we already "cached" vimeo API calls to get thumbnail?
                if (strpos($line, '::') > 0) {
                  list($vimeo_url,$img_url) = explode('::', $line);
                } else {
                  // Extract vimeo video ID and pull large thumbnail from API
                  $vimeo_url = trim($line);
                  if (preg_match('/vimeo.com\/(\d+)/', $line, $m)) {
                    $img_id = $m[1];
                    $hash = unserialize(file_get_contents('http://vimeo.com/api/v2/video/' . $img_id . '.php'));
                    $img_url = $hash[0]['thumbnail_large'];
                  }
                }
                // If we found an image, show link to video and build new_lines
                if ($img_url) {
                  $new_video_links .= $vimeo_url.'::'.$img_url."\n";
                  echo '<li><a href="'.$vimeo_url.'"><img src="'.$img_url.'"></a></li>';
                }
              }
              // If we changed lines, store post meta w/ parsed vimeo thumbnails to avoid multiple API calls
              if ($new_video_links != $video_lines) {
                echo '<textarea>'.$new_video_links.'</textarea>';
                update_post_meta($post->ID, '_cmb2_video_links', $new_video_links);
              }
              echo '</ul>';
            }
          ?>
        </div>
      </div>
  </div>
  <div class="grid-item-body">
    <h1 class="section-title">Key Information</h1>
    <div class="body-inner">
      <div class="user-content">
        <?= $body ?>
      </div>
    </div>
  </div>
</article>