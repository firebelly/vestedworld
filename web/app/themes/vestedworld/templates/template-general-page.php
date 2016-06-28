<?php
/* Template name: General Page */

$title = $post->post_title;
$name = $post->post_name;
$headline = get_post_meta($post->ID, '_cmb2_headline', true);
$summary = get_post_meta($post->ID, '_cmb2_summary', true);
$body = apply_filters('the_content', $post->post_content);

// Get child pages
$args = array(
    'post_type' => 'page',
    'post_parent' => $post->ID,
    'order' => 'ASC'
  );
$child_pages = get_posts($args);
?>

<article id="<?= $name ?>" class="page-section intro-section <?= $child_pages ? 'page-nav-section' : '' ?>">
  <?php if ($thumb = \Firebelly\Media\get_post_thumbnail($post->ID, 'large')): ?>
    <div class="image-wrap parallax-parent" style="background-image:url('<?= $thumb ?>');"></div>
  <?php endif; ?>
  <div class="content">
    <h1 class="section-title page-nav-title"><?= $title ?></h1>
    <div class="summary">
      <h2><?= $headline ?></h2>
      <?= !empty($summary) ? '<p>'.$summary.'</p>' : ''; ?>
    </div>
    <div class="body user-content">
      <?= $body ?>
    </div>
  </div>
</article>

<?php
// If any child pages, include each
if ($child_pages) {
  foreach($child_pages as $child_page) {
    include(locate_template("templates/child-page.php"));
  }
}
?>
