<header class="site-header" role="banner">
  <div class="container">
    <h1 class="logo">
      <a href="<?= esc_url(home_url('/')); ?>">
        <svg class="icon icon-logo" role="img"><use xlink:href="#icon-logo"></use></svg><svg class="icon icon-vestedworld" role="img"><title><?php bloginfo('name'); ?></title><use xlink:href="#icon-vestedworld"></use></svg>
      </a>
    </h1>
    <nav class="site-nav" role="navigation">
      <?php
      if (has_nav_menu('primary_navigation')) :
        wp_nav_menu(['theme_location' => 'primary_navigation', 'menu_class' => 'nav']);
      endif;
      ?>
      <div class="nav-actions">
        <a class="sign-up" href="/sign-up/">Sign Up</a>
        <div class="investor-form-container"><?php include ('investor-form.php'); ?></div>
        <a class="show-search" href="/search/"><span class="sr-only">Search</span><svg class="icon icon-search"><use xlink:href="#icon-search"></svg></a>
      </div>
    </nav>
  </div>
</header>