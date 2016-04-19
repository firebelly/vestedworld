<header class="news-filter">
  <form action="<?= get_permalink(get_option('page_for_posts')) ?>" method="get">
    <h1>News</h1>
    <fieldset>
      <div class="filter">
        <label>Filter</label>
        <?php wp_dropdown_categories(); ?>
      </div>
      <div class="search">
        <label class="sr-only">Search</label>
        <input type="text" name="s" placeholder="Search">
      </div>
    </fieldset>
  </form>
</header>
