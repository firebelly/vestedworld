<?php /* Template Name: Front Page */ ?>

<!-- headline carousel -->
<section>
  <div class="slider home-slider">
    <?= \Firebelly\PostTypes\Headlines\get_headlines(); ?>
  </div>
</section>
