<?php
$title = $post->post_title;
$name = $post->post_name;
$headline = get_post_meta($post->ID, '_cmb2_headline', true);
$summary = get_post_meta($post->ID, '_cmb2_summary', true);
$body = apply_filters('the_content', $post->post_content);
$featured_image = \Firebelly\Media\get_post_thumbnail($post->ID, 'large');
?>

<article id="<?= $name ?>" class="page-section intro-section page-nav-section">
  <div class="content">
    <h1 class="section-title page-nav-title">Overview</h1>
    <div class="summary">
      <h2><?= $headline ?></h2>
      <?= !empty($summary) ? '<p>'.$summary.'</p>' : ''; ?>
    </div>
    <div class="body user-content">
      <!-- todo: style image -->
      <img src="<?= $featured_image ?>">
    </div>
  </div>
</article>

<div class="active-grid-item-container">
  <div class="post-nav">
    <div class="previous-post">Previous company &gt;</div>
    <div class="next-post">&lt; Next company</div>
  </div>
  <button class="grid-item-deactivate grid-item-toggle plus-button close"><div class="plus"></div></button>
  <div class="grid-item-content">
    <h1 class="section-title">Company</h1>
    <div class="grid-item-data-container">

    </div>
  </div>
</div>

<div class="page-sections">
  <section class="page-section grid-wrap companies page-nav-section">
    <h1 class="section-title page-nav-title">Portfolio</h1>
    <?= \Firebelly\PostTypes\Company\get_companies(); ?>
  </section>
</div>