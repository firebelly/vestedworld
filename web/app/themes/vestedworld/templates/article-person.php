<?php
$photo = get_the_post_thumbnail($post->ID, 'medium');
$title = get_post_meta( $post->ID, '_cmb2_title', true );
$subtitle = get_post_meta( $post->ID, '_cmb2_subtitle', true );
$body = apply_filters('the_content', $post->post_content);
?>

<article id="<?= $post->post_name ?>" class="person-data">
  <div class="person-text">  
    <header>      
      <h1><?= $post->post_title ?></h1>
      <h2><?= !empty($title) ? '<span class="title">'.$title.'</span>' : ''; ?><br>
      <?= !empty($subtitle) ? '<span class="sub-title">'.$subtitle.'</span>' : ''; ?></h2>
    </header>
    <button class="btn person-activate">Profile</button>
    <div class="bio user-content">
      <?= $body ?>
    </div>
  </div>
  <div class="person-image">
    <?= $photo ?>
  </div>
</article>