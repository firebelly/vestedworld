<?php
$photo = get_the_post_thumbnail($post->ID, 'grid-thumb');
$title = get_post_meta( $post->ID, '_cmb2_title', true );
$subtitle = get_post_meta( $post->ID, '_cmb2_subtitle', true );
$member_type = get_post_meta( $post->ID, '_cmb2_member_type', true );
$quote = get_post_meta( $post->ID, '_cmb2_quote', true );
$callout = get_post_meta( $post->ID, '_cmb2_callout', true );
$body = apply_filters('the_content', $post->post_content);
?>

<article id="<?= $post->post_name ?>" class="grid-item-data <?= $member_type ?>">
  <div class="-left">
    <?php if ($member_type == 'management') { ?>
      <div class="grid-item-inner">
        <div class="grid-item-text">
          <header>
            <h1><?= $post->post_title ?></h1>
            <h2><?= !empty($title) ? '<span class="title">'.$title.'</span>' : ''; ?><br>
            <?= !empty($subtitle) ? '<span class="sub-title">'.$subtitle.'</span>' : ''; ?></h2>
          </header>
          <button class="btn grid-item-activate">Profile</button>
        </div>
        <div class="grid-item-image">
          <div class="grid-item-image-inner">
            <?= $photo ?>
          </div>
        </div>
      </div>
      <?= !empty($quote) ? '<div class="quote">'.$quote.'</div>' : ''; ?>
      <?= !empty($callout) ? '<div class="callout"><div class="callout-inner">'.$callout.'</div></div>' : ''; ?>
    <?php } else { ?>
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
          <button class="btn -white grid-item-activate">Profile</button>
        </div>
      </div>
    <?php } ?>
  </div>
  <div class="grid-item-body">
    <h1 class="section-title">
      <?php if ($member_type == 'vested_angel') echo 'Q&A'; else if ($member_type == 'vested_advisor') echo 'Overview'; else echo 'Bio';  ?>
    </h1>
    <div class="body-inner">
      <div class="user-content">
        <?= $body ?>
      </div>
    </div>
  </div>
</article>