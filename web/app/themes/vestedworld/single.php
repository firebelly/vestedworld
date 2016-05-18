<?php
// Let's give the X close link a real href for a single page
$close_link = '/';
if ($post->post_type == 'company') {
	$close_link = '/portfolio/';
} elseif ($post->post_type == 'country') {
	$close_link = '/resources/countries/';
} elseif ($post->post_type == 'person') {
  $close_link = '/community/';
}
?>
<div class="active-grid-item-container -active">
  <a href="<?= $close_link ?>" class="grid-item-deactivate grid-item-toggle plus-button close"><div class="plus"></div></a>
  <?php get_template_part('templates/content-single', get_post_type()); ?>
</div>
