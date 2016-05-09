<?php
$photo = get_the_post_thumbnail($post->ID, 'grid-thumb');
$headquarters = get_post_meta( $post->ID, '_cmb2_headquarters', true );
$callout = get_post_meta( $post->ID, '_cmb2_callout', true );
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
          <header>
            <h1><?= $post->post_title ?></h1>
          </header>
          <?= !empty($callout) ? '<div class="callout">'.$callout.'</div>' : ''; ?>
          <!-- todo: style headquarter in grid-item -->
          <?= !empty($headquarters) ? '<div class="headquarters">'.$headquarters.'</div>' : ''; ?>
          <button class="btn -white grid-item-activate">Profile</button>
        </div>
      </div>
  </div>
  <div class="bio">
    <h1 class="section-title">
    foo
    </h1>
    <div class="bio-inner">
      <div class="user-content">
        <?= $body ?>
      </div>
    </div>
  </div>
</article>