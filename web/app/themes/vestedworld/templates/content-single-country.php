<?php
// Single Country
$photo = get_the_post_thumbnail($post->ID, 'grid-large');
$body = apply_filters('the_content', $post->post_content);

$timeline = get_post_meta($post->ID, '_cmb2_timeline', true);
$news_links = get_post_meta($post->ID, '_cmb2_news_links', true);

$country_overview_intro = get_post_meta($post->ID, '_cmb2_country_overview_intro', true);
$population = get_post_meta($post->ID, '_cmb2_population', true);
$projected_population = get_post_meta($post->ID, '_cmb2_projected_population', true);
$median_age = get_post_meta($post->ID, '_cmb2_median_age', true);
$poverty = get_post_meta($post->ID, '_cmb2_poverty', true);
$poverty_label = get_post_meta($post->ID, '_cmb2_poverty_label', true);
$workforce_participation = get_post_meta($post->ID, '_cmb2_workforce_participation', true);
$income_level_classification = get_post_meta($post->ID, '_cmb2_income_level_classification', true);
$economic_outlook_intro = get_post_meta($post->ID, '_cmb2_economic_outlook_intro', true);
$gross_gdp = get_post_meta($post->ID, '_cmb2_gross_gdp', true);
$per_capita_gdp = get_post_meta($post->ID, '_cmb2_per_capita_gdp', true);

$gdp_growth = get_post_meta($post->ID, '_cmb2_gdp_growth', true);
$gdp_growth_comparison_1 = get_post_meta($post->ID, '_cmb2_gdp_growth_comparison_1', true);
$gdp_growth_comparison_1_label = get_post_meta($post->ID, '_cmb2_gdp_growth_comparison_1_label', true);
$gdp_growth_comparison_2 = get_post_meta($post->ID, '_cmb2_gdp_growth_comparison_2', true);
$gdp_growth_comparison_2_label = get_post_meta($post->ID, '_cmb2_gdp_growth_comparison_2_label', true);

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

// Clean up numeric fields, removing $ and any other random characters
foreach ([
    'population',
    'projected_population',
    'median_age',
    'poverty',
    'workforce_participation',

    'gross_gdp',
    'per_capita_gdp',
    'gdp_growth',
    'gdp_growth_comparison_1',
    'gdp_growth_comparison_2',
    'inflation',
    'foreign_direct_investments',
    'ease_of_doing_business_ranking',
    'world_corruption_ranking',

    'gdp_percent_agriculture',
    'gdp_percent_service',
    'gdp_percent_industry',
    'workforce_percent_agriculture',
    'workforce_percent_service',
    'workforce_percent_industry',
  ] as $clean_me) {
  // Remove any chars not a digit, comma, period, or slash
  $$clean_me = preg_replace('/[^\d\.,\/]/', '', $$clean_me);
}

// Format numbers e.g. 3300 -> 3,300
$foreign_direct_investments = number_format($foreign_direct_investments);
$per_capita_gdp = number_format($per_capita_gdp);

// Wrap currency name in tag for styling (e.g. 99.73 KES -> 99.73 <span>KES</span>)
$average_exchange_rate = preg_replace('/ (.*)$/', ' <span>$1</span>', $average_exchange_rate);

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

      <div class="grid-item-text intro-text">
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

      <div class="grid-text-group">
        <h3 class="tab">Overview</h3>
        <div class="user-content">
          <h3>Demographic + Economic Overview</h3>
          <?= apply_filters('the_content', $country_overview_intro) ?>
        </div>
        <div class="country-overview-stats">
          <div class="row">
            <div class="population-chart">
              <div class="circle-chart">
                <span class="chart" data-value="<?= $population ?>" data-value2="<?= $projected_population ?>"></span>
              </div>

              <span class="stat-num dark"><?= $population ?>M</span>
              <span class="stat-label" data-source="World Bank">Current Population</span>
              <span class="stat-num"><?= $projected_population ?>M</span>
              <span class="stat-label" data-source="Population Reference Bureau">Projected Population Growth by 2050</span>
            </div>
            <div class="median-age">
              <span class="stat-num"><?= $median_age ?></span>
              <span class="stat-label" data-source="CIA World Fact Book">Median Age</span>
            </div>
          </div><!-- END .row -->
          <div class="row">
            <div class="poverty-chart">
              <span class="chart" data-value="<?= $poverty ?>"></span>
              <span class="stat-num"><?= $poverty ?>%</span>
              <span class="stat-label" data-source="CIA World Fact Book"><?= $poverty_label ?></span>
            </div>
            <div class="workforce-chart">
              <span class="chart" data-value="<?= $workforce_participation ?>"></span>
              <span class="stat-num"><?= $workforce_participation ?>%</span>
              <span class="stat-label" data-source="World Bank">Workforce Participation</span>
            </div>
          </div><!-- END .row -->
          <div class="income-level-classification">
            <ul class="row">
              <li class="stat-label" data-source="World Bank">Income Level Classification:</li>
              <?php
              foreach (\Firebelly\PostTypes\Country\get_income_levels() as $value=>$display) {
                echo '<li' . ($value==$income_level_classification ? ' class="active"' : '') . '>' . $display . '</li>';
              }
              ?>
            </ul>
          </div>
        </div>
      </div>

      <div class="grid-text-group">
        <h3 class="tab">Outlook</h3>
        <div class="user-content">
          <h3>Economic Outlook</h3>
          <?= apply_filters('the_content', $economic_outlook_intro) ?>
        </div>
        <div class="outlook-stats">
          <div class="row">
            <div class="gross-gdp">
              <div class="inner-row">
                <span class="stat-num">$<?= $gross_gdp ?>B</span>
                <span class="stat-label" data-source="CIA World Fact Book">Gross<br> GDP</span>
              </div>
            </div>
            <div class="per-capita-gdp">
              <div class="inner-row">
                <span class="stat-num">$<?= $per_capita_gdp ?></span>
                <span class="stat-label" data-source="World Bank">Per Capita<br> GDP</span>
              </div>
            </div>
          </div><!-- END .row -->

          <div class="row">
            <div class="gdp-growth-chart">
              <div class="inner-row">
                <div class="stat-label" data-source="World Bank">GDP Growth Chart Comparison</div>
                <span class="chart" data-value="<?= $gdp_growth ?>" data-value2="<?= $gdp_growth_comparison_1 ?>" data-value3="<?= $gdp_growth_comparison_2 ?>"></span>
              </div>
            </div>
            <div class="gdp-growth-labels">
              <span class="stat-num"><?= $gdp_growth ?>%</span>
              <span class="stat-label"><?= $post->post_title ?></span>

              <span class="stat-num"><?= $gdp_growth_comparison_1 ?>%</span>
              <span class="stat-label"><?= $gdp_growth_comparison_1_label ?></span>

              <span class="stat-num"><?= $gdp_growth_comparison_2 ?>%</span>
              <span class="stat-label"><?= $gdp_growth_comparison_2_label ?></span>
            </div>
          </div><!-- END .row -->

          <div class="row">
            <div class="inflation">
              <div class="inner-row">
                <span class="stat-num"><?= $inflation ?>%</span>
                <span class="stat-label" data-source="CIA World Fact Book">Inflation</span>
              </div>
            </div>
            <div class="per-capita-gdp">
              <div class="inner-row">
                <span class="stat-num">$<?= $foreign_direct_investments ?>M</span>
                <span class="stat-label" data-source="World Bank">Foreign Direct Investments</span>
              </div>
            </div>
          </div><!-- END .row -->

          ease_of_doing_business_ranking: <?= $ease_of_doing_business_ranking ?><br>
          world_corruption_ranking: <?= $world_corruption_ranking ?><br>
          average_exchange_rate: <?= $average_exchange_rate ?><br>
          currency_description: <?= $currency_description ?><br>
        </div>
      </div>

      <div class="grid-text-group">
        <h3 class="tab">Key Sectors</h3>
        <div class="user-content">
          <h3>Key Sectors</h3>
          <?= apply_filters('the_content', $key_sectors_intro) ?>
        </div>
        <div class="key-sector-stats">
          gdp_percent_agriculture: <?= $gdp_percent_agriculture ?><br>
          gdp_percent_service: <?= $gdp_percent_service ?><br>
          gdp_percent_industry: <?= $gdp_percent_industry ?><br>
          workforce_percent_agriculture: <?= $workforce_percent_agriculture ?><br>
          workforce_percent_service: <?= $workforce_percent_service ?><br>
          workforce_percent_industry: <?= $workforce_percent_industry ?><br>
        </div>
      </div>

    </div><!-- END .body-inner -->
  </div><!-- END .grid-item-body -->
</article>