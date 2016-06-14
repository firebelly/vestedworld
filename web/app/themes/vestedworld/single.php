<?php
// Let's give the X close link a real href for a single page
$parent_url = \Firebelly\Utils\get_parent_url($post);
?>
<div class="active-grid-item-container -active">
  <a href="<?= $parent_url ?>" class="grid-item-deactivate grid-item-toggle plus-button close"><div class="plus"></div></a>
  <?php get_template_part('templates/content-single', get_post_type()); ?>
</div>
