<?php
$title = $post->post_title;
$name = $post->post_name;
$headline = get_post_meta($post->ID, '_cmb2_headline', true);
$summary = get_post_meta($post->ID, '_cmb2_summary', true);
$body = apply_filters('the_content', $post->post_content);
$featured_image = \Firebelly\Media\get_post_thumbnail($post->ID, 'large');
?>

<article id="<?= $name ?>" class="page-section intro-section page-nav-section">
  <div class="content header-image">
    <h1 class="section-title page-nav-title">Overview</h1>
    <div class="header-image-wrap" style="background-image: url('<?= $featured_image ?>')"></div>
    <div class="summary">
      <h2><?= $headline ?></h2>
      <?= !empty($summary) ? '<p>'.$summary.'</p>' : ''; ?>
    </div>
  </div>
</article>

<div class="active-grid-item-container">
  <div class="grid-nav">
    <div class="previous-item">Previous company &gt;</div>
    <div class="next-item">&lt; Next company</div>
  </div>
  <button class="grid-item-deactivate grid-item-toggle plus-button close"><div class="plus"></div></button>
  <div class="grid-item-content">
    <div class="item-data-container"></div>
  </div>
</div>

<div class="page-sections">
  <section class="page-section grid-section companies page-nav-section">
    <h1 class="section-title page-nav-title">Portfolio</h1>
    <?= \Firebelly\PostTypes\Company\get_companies(); ?>
  </section>
</div>