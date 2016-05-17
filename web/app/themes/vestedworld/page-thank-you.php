<?php
/*
Template Name: Thank You
*/

$form_id = get_post_meta($post->ID, '_cmb2_form_id', true);
?>

<div class="row">
  <div class="page-content">
    <h3 class="tab">Thank you</h3>
    <?= apply_filters('the_content', $post->post_content); ?>
  </div>
  <div class="form-content">
    <?php gravity_form( $form_id, false, false, false, '', true); ?>
  </div>
</div>