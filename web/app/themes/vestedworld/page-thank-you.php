<?php
/*
Template Name: Thank You
*/

?>

<div class="row">
  <div class="page-content">
    <h3 class="tab">Thank you</h3>
    <?= apply_filters('the_content', $post->post_content); ?>
  </div>
  <div class="form-content">
    <form action="" class="application-form">
      <h3>Follow-up form here.</h3>
    </form>
  </div>
</div>