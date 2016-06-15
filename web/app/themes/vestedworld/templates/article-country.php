<?php
$photo = get_the_post_thumbnail($post->ID, 'grid-thumb');
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
          <a class="btn -white grid-item-activate" href="<?= get_permalink($post) ?>">Profile</a>
        </div>
      </div>
  </div>
</article>