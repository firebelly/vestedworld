<?php
// Single Country
$photo = get_the_post_thumbnail($post->ID, 'grid-large');
$body = apply_filters('the_content', $post->post_content);

$timeline = get_post_meta($post->ID, '_cmb2_timeline', true);
$news_links = get_post_meta($post->ID, '_cmb2_news_links', true);

$country_overview_intro = get_post_meta($post->ID, '_cmb2_country_overview_intro', true);
$population = get_post_meta($post->ID, '_cmb2_population', true);
$median_age = get_post_meta($post->ID, '_cmb2_median_age', true);
$poverty = get_post_meta($post->ID, '_cmb2_poverty', true);
$workforce_participation = get_post_meta($post->ID, '_cmb2_workforce_participation', true);
$income_level_classification = get_post_meta($post->ID, '_cmb2_income_level_classification', true);

$economic_outlook_intro = get_post_meta($post->ID, '_cmb2_economic_outlook_intro', true);
$gross_gdp = get_post_meta($post->ID, '_cmb2_gross_gdp', true);
$per_capita_gdp = get_post_meta($post->ID, '_cmb2_per_capita_gdp', true);
$inflation = get_post_meta($post->ID, '_cmb2_inflation', true);
$foreign_direct_investments = get_post_meta($post->ID, '_cmb2_foreign_direct_investments', true);
$ease_of_doing_business_ranking = get_post_meta($post->ID, '_cmb2_ease_of_doing_business_ranking', true);
$world_corruption_ranking = get_post_meta($post->ID, '_cmb2_world_corruption_ranking', true);
$average_exchange_rate = get_post_meta($post->ID, '_cmb2_average_exchange_rate', true);
$currency_description = get_post_meta($post->ID, '_cmb2_currency_description', true);

$key_sectors_intro = get_post_meta($post->ID, '_cmb2_key_sectors_intro', true);
$gdp_percent_agriculture = get_post_meta($post->ID, '_cmb2_gdp_percent_agriculture', true);
$gdp_percent_service = get_post_meta($post->ID, '_cmb2_gdp_percent_service', true);
$gdp_percent_industry = get_post_meta($post->ID, '_cmb2_gdp_percent_industry', true);
$workforce_percent_agriculture = get_post_meta($post->ID, '_cmb2_workforce_percent_agriculture', true);
$workforce_percent_service = get_post_meta($post->ID, '_cmb2_workforce_percent_service', true);
$workforce_percent_industry = get_post_meta($post->ID, '_cmb2_workforce_percent_industry', true);
?>

<article id="<?= $post->post_name ?>" class="grid-item-data single country" data-id="<?= $post->ID ?>" data-page-title="<?= $post->post_title ?>" data-page-url="<?= get_permalink($post) ?>">
  <div class="-left">
    <div class="grid-item-inner">
      <div class="grid-item-image">
        <div class="grid-item-image-inner">
          <h1 class="section-title"><?= $post->post_title ?></h1>
          <?= $photo ?>
        </div>
      </div>

      <div class="intro-text">
        <?= $body ?>
      </div>

      <?php if ($timeline): ?>
      <div class="grid-item-text">
        <h3 class="tab">Timeline</h3>
        <dl class="timeline">
          <?php foreach($timeline as $timeline_date): ?>
            <dt><?= $timeline_date['date_title'] ?></dt>
            <dd><?= $timeline_date['date_description'] ?></dd>
          <?php endforeach; ?>
        </dl>
      </div><!-- END .grid-item-text -->
      <?php endif; ?>

      <?php if ($news_links): ?>
      <div class="grid-item-text">
        <h3 class="tab">News</h3>
        <div class="user-content">
          <?= apply_filters('the_content', $news_links) ?>
        </div>
      </div><!-- END .grid-item-text -->
      <?php endif; ?>
    </div><!-- END .grid-item-inner -->
  </div><!-- END .-left -->

  <div class="grid-item-body">
    <div class="body-inner">

      <h3 class="tab">Overview</h3>
      <div class="user-content">
        <?= apply_filters('the_content', $country_overview_intro) ?>
      </div>
      <div class="country-overview-stats">
        population: <?= $population ?><br>
        median_age: <?= $median_age ?><br>
        poverty: <?= $poverty ?><br>
        workforce_participation: <?= $workforce_participation ?><br>
        income_level_classification: <?= $income_level_classification ?><br>
      </div>

      <h3 class="tab">Outlook</h3>
      <div class="user-content">
        <?= apply_filters('the_content', $economic_outlook_intro) ?>
      </div>
      <div class="country-overview-stats">
        gross_gdp: <?= $gross_gdp ?><br>
        per_capita_gdp: <?= $per_capita_gdp ?><br>
        inflation: <?= $inflation ?><br>
        foreign_direct_investments: <?= $foreign_direct_investments ?><br>
        ease_of_doing_business_ranking: <?= $ease_of_doing_business_ranking ?><br>
        world_corruption_ranking: <?= $world_corruption_ranking ?><br>
        average_exchange_rate: <?= $average_exchange_rate ?><br>
        currency_description: <?= $currency_description ?><br>
      </div>

      <h3 class="tab">Key Sectors</h3>
      <div class="user-content">
        <?= apply_filters('the_content', $key_sectors_intro) ?>
      </div>
      <div class="country-overview-stats">
        gdp_percent_agriculture: <?= $gdp_percent_agriculture ?><br>
        gdp_percent_service: <?= $gdp_percent_service ?><br>
        gdp_percent_industry: <?= $gdp_percent_industry ?><br>
        workforce_percent_agriculture: <?= $workforce_percent_agriculture ?><br>
        workforce_percent_service: <?= $workforce_percent_service ?><br>
        workforce_percent_industry: <?= $workforce_percent_industry ?><br>
      </div>

    </div><!-- END .body-inner -->
  </div><!-- END .grid-item-body -->
</article>