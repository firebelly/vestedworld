<?php
$photo = get_the_post_thumbnail($post->ID, 'medium');
$title = get_post_meta( $post->ID, '_cmb2_title', true );
$subtitle = get_post_meta( $post->ID, '_cmb2_subtitle', true );
$member_type = get_post_meta( $post->ID, '_cmb2_member_type', true );
$quote = get_post_meta( $post->ID, '_cmb2_quote', true );
$callout = get_post_meta( $post->ID, '_cmb2_callout', true );
$body = apply_filters('the_content', $post->post_content);
?>

<article id="<?= $post->post_name ?>" class="person-data <?= $member_type ?>">
  <div class="-left">
    <?php if ($member_type == 'management') { ?>
      <div class="person-inner">
        <div class="person-text">  
          <header>      
            <h1><?= $post->post_title ?></h1>
            <h2><?= !empty($title) ? '<span class="title">'.$title.'</span>' : ''; ?><br>
            <?= !empty($subtitle) ? '<span class="sub-title">'.$subtitle.'</span>' : ''; ?></h2>
          </header>
          <button class="btn person-activate">Profile</button>
        </div>
        <div class="person-image">
          <div class="person-image-inner">
            <?= $photo ?>
          </div>
        </div>
      </div>
      <?= !empty($quote) ? '<div class="quote">'.$quote.'</div>' : ''; ?>
      <?= !empty($callout) ? '<div class="callout"><div class="callout-inner">'.$callout.'</div></div>' : ''; ?>
    <?php } else { ?>
      <div class="person-inner">
        <div class="person-image">
          <div class="person-image-inner">
            <?= $photo ?>
          </div>
        </div>
        <div class="person-text">  
          <header>      
            <h1><?= $post->post_title ?></h1>
          </header>
          <button class="btn -white person-activate">Profile</button>
        </div>
      </div>
    <?php } ?>
  </div>
  <div class="bio">
    <h1 class="section-title">Bio</h1>
    <div class="bio-inner">
      <div class="user-content">
        <?= $body ?>
      </div>
    </div>
  </div>
</article>