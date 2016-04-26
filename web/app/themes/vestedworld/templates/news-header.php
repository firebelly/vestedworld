<header class="news-filter">
  <form action="<?= get_permalink(get_option('page_for_posts')) ?>" method="get">
    <h1><a href="<?= get_permalink(get_option('page_for_posts')) ?>">News</a></h1>
    <fieldset>
      <div class="filter">
        <label>Filter</label>
        <div class="select-wrapper"><?php wp_dropdown_categories(['show_option_none'=>'All']); ?></div>
      </div>
      <div class="search">
        <label class="sr-only">Search</label>
        <input type="text" name="s" placeholder="Search" value="<?= get_search_query() ?>">
      </div>
    </fieldset>
  </form>
</header>
