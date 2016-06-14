<?php
$photo = get_the_post_thumbnail($post->ID, 'grid-thumb');
$title = get_post_meta($post->ID, '_cmb2_title', true);
$subtitle = get_post_meta($post->ID, '_cmb2_subtitle', true);
$member_type = get_post_meta($post->ID, '_cmb2_member_type', true);
$quote = get_post_meta($post->ID, '_cmb2_quote', true);
$callout = get_post_meta($post->ID, '_cmb2_callout', true);
$image_is_logo = get_post_meta($post->ID, '_cmb2_image_is_logo', true) ? 'image-is-logo' : '';
$body = apply_filters('the_content', $post->post_content);
$parent_url = \Firebelly\Utils\get_parent_url($post);
?>

<article id="<?= $post->post_name ?>" class="grid-item-data <?= $image_is_logo ?> <?= $member_type ?>" data-id="<?= $post->ID ?>" data-page-title="<?= $post->post_title ?>" data-page-url="<?= get_permalink($post) ?>" data-parent-url="<?= $parent_url  ?>">
  <div class="-left">
    <?php if ($member_type == 'management') { ?>
      <div class="grid-item-inner">
        <div class="grid-item-text">
          <header>
            <h1><?= $post->post_title ?></h1>
            <h2><?= !empty($title) ? '<span class="title">'.$title.'</span>' : ''; ?><br>
            <?= !empty($subtitle) ? '<span class="sub-title">'.$subtitle.'</span>' : ''; ?></h2>
          </header>
          <a class="btn grid-item-activate" href="<?= get_permalink($post) ?>">Profile</a>
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
          <a class="btn -white grid-item-activate" href="<?= get_permalink($post) ?>">Profile</a>
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