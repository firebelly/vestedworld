<header class="site-header" role="banner">
  <div class="container">
    <h1>
      <a class="brand" href="<?= esc_url(home_url('/')); ?>">
        <svg class="icon icon-logo" role="img"><use xlink:href="#icon-logo"></use></svg><svg class="icon icon-vestedworld" role="img"><title><?php bloginfo('name'); ?></title><use xlink:href="#icon-vestedworld"></use></svg>
      </a>
    </h1>
    <nav class="site-nav" role="navigation">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
      endif;
      ?>
    </nav>
  </div>
</header>