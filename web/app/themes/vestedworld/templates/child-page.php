<?php
$title = $child_page->post_title;
$name = $child_page->post_name;
$headline = get_post_meta($child_page->ID, '_cmb2_headline', true);
$summary = get_post_meta($child_page->ID, '_cmb2_summary', true);
$body = apply_filters('the_content', $child_page->post_content);
?>

<article id="<?= $name ?>" class="page-section page-nav-section">
	<?php if ($thumb = \Firebelly\Media\get_post_thumbnail($child_page->ID, 'large')): ?>
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