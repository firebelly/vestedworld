<?php 
$photo = get_the_post_thumbnail($post->ID, 'thumbnail');
$title = get_post_meta( $post->ID, '_cmb2_title', true );
$subtitle = get_post_meta( $post->ID, '_cmb2_subtitle', true );
$body = apply_filters('the_content', $post->post_content);

//dunno if this is gonna be a popup or a link, so I left this minimal--can always regrab from plant
?>

<article id="<?= $post->post_name ?>">
  <?= $photo ?>
  <hgroup>
    <h1><?= $post->post_title ?></h1>
    <h2><?= !empty($title) ? '<span class="title">'.$title.'</span>' : ''; ?></h2>
    <h3><?= !empty($subtitle) ? '<span class="sub-title">'.$subtitle.'</span>' : ''; ?></h3>
  </hgroup>
  <button>Read More</button><!--doesn't do anything, toggle classes from plant are: class="person-data person-activate person-toggle" -->
<!--  kept just in case we do popup:  <div class="bio user-content">
    <?= $body ?>
  </div> -->
</article>
