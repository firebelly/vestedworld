<?php
$photo = get_the_post_thumbnail($post->ID, 'grid-thumb');
$headquarters = get_post_meta( $post->ID, '_cmb2_headquarters', true );
$callout = get_post_meta( $post->ID, '_cmb2_callout', true );
$body = apply_filters('the_content', $post->post_content);
?>

<article id="<?= $post->post_name ?>" class="grid-item-data modal" data-id="<?= $post->ID ?>" data-page-title="<?= $post->post_title ?>" data-page-url="<?= get_permalink($post) ?>">
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
          <?= !empty($headquarters) ? '<div class="headquarters">'.$headquarters.'</div>' : ''; ?>
          <a class="btn -gray grid-item-activate" href="<?= get_permalink($post) ?>">Profile</a>
        </div>
      </div>
  </div>
</article>