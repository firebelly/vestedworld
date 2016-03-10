<?php /* Template Name: About Us - Article */

$thumb = get_the_post_thumbnail($page->ID, 'large');
$title = $page->post_title;
$headline = get_post_meta($page->ID, '_cmb2_headline', true);
$summary = get_post_meta($page->ID, '_cmb2_summary', true);
$body = apply_filters('the_content', $page->post_content);

?>

<article id="<?= $title; ?> !empty($thumb) ? ">
	<?= !empty($thumb) ? '<div class="thumbnail">'.$thumb.'</div>' : ''; ?>
	<div class="content">
		<h1><?= $title ?></h1>
		<div class="summary">
			<h2><?= $headline ?></h2>
			<?= !empty($summary) ? '<p>'.$summary.'</p>' : ''; ?>
		</div>
		<div class="body user-content">
			<?= $body ?>
		</div>
	</div>
</article>