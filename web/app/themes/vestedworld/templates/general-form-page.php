<?php
/*
Template Name: General Form Page
*/

$form_intro = get_post_meta($post->ID, '_cmb2_form_intro', true);
$form_id = get_post_meta($post->ID, '_cmb2_form_id', true);
?>

<div class="row">
  <div class="page-content">
    <h3 class="tab"><?= the_title(); ?></h3>
    <?= apply_filters('the_content', $post->post_content); ?>
  </div>
  <div class="form-content">
    <p><?= $form_intro ?></p>
    <?php gravity_form( $form_id, false, false, false, '', true); ?>
  </div>
</div>