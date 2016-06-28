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
$currency_code = get_post_meta($post->ID, '_cmb2_currency_code', true);
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
    'average_exchange_rate',

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

// Split number/number stats
$ease_of_doing_business_ranking_arr = explode('/', $ease_of_doing_business_ranking);
$world_corruption_ranking_arr = explode('/', $world_corruption_ranking);
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
            <div class="population-chart chart-row">
              <div class="inner-row">
                <div class="chart ct-square">
                  <div class="ct-chart donut-inner" data-label="/ 100M"></div>
                  <div class="ct-chart donut-chart" data-total="100" data-value="<?= $projected_population ?>" data-class="ct-series-a"></div>
                  <div class="ct-chart donut-chart" data-total="100" data-value="<?= $population ?>" data-class="ct-series-b" data-delay="2"></div>
                </div>
                <div class="chart-labels">
                  <div class="row -persist">
                    <span class="one-half stat-num ct-series-b"><?= $population ?>M</span>
                    <span class="one-half stat-label" data-source="World Bank">Current<br> Population</span>
                  </div>
                  <div class="row -persist">
                    <span class="one-half stat-num ct-series-a"><?= $projected_population ?>M</span>
                    <span class="one-half stat-label" data-source="Population Reference Bureau">Projected Population Growth by 2050</span>
                  </div>
                </div>
              </div>
            </div>
            <div class="median-age">
              <span class="stat-num"><?= $median_age ?></span>
              <span class="stat-label" data-source="CIA World Fact Book">Median<br> Age</span>
            </div>
          </div><!-- END .row -->
          <div class="row half-width-row">
            <div class="poverty-chart stat-row chart-row">
              <div class="inner-row">
                <div class="chart ct-square">
                  <div class="ct-chart donut-inner"></div>
                  <div class="ct-chart donut-chart" data-total="100" data-value="<?= $poverty ?>" data-class="ct-series-a"></div>
                </div>
                <div class="chart-labels">
                  <span class="stat-num"><?= $poverty ?>%</span>
                  <span class="stat-label" data-source="CIA World Fact Book"><?= $poverty_label ?></span>
                </div>
              </div>
            </div>
            <div class="workforce-chart stat-row chart-row">
              <div class="inner-row">
                <div class="chart ct-square">
                  <div class="ct-chart donut-inner"></div>
                  <div class="ct-chart donut-chart" data-total="100" data-value="<?= $workforce_participation ?>" data-class="ct-series-a"></div>
                </div>
                <div class="chart-labels">
                  <span class="stat-num"><?= $workforce_participation ?>%</span>
                  <span class="stat-label" data-source="World Bank">Workforce<br> Participation</span>
                </div>
              </div>
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
        </div><!-- END .country-overview-stats -->
      </div><!-- END .grid-text-group -->

      <div class="grid-text-group">
        <h3 class="tab">Outlook</h3>
        <div class="user-content">
          <h3>Economic Outlook</h3>
          <?= apply_filters('the_content', $economic_outlook_intro) ?>
        </div>
        <div class="outlook-stats">
          <div class="row half-width-row -persist">
            <div class="gross-gdp stat-row">
              <div class="inner-row">
                <span class="stat-num">$<?= $gross_gdp ?>B</span>
                <span class="stat-label" data-source="CIA World Fact Book">Gross<br> GDP</span>
              </div>
            </div>
            <div class="per-capita-gdp stat-row">
              <div class="inner-row">
                <span class="stat-num">$<?= $per_capita_gdp ?></span>
                <span class="stat-label" data-source="World Bank">Per Capita<br> GDP</span>
              </div>
            </div>
          </div><!-- END .row -->

          <div class="row chart-row">
            <div class="gdp-growth-chart">
              <div class="inner-row">
                <div class="chart-labels">
                  <div class="stat-label" data-source="World Bank">GDP Growth Chart Comparison</div>
                </div>
                <div class="chart ct-square">
                  <div class="ct-chart donut-inner" data-label="/ 8%"></div>
                  <div class="ct-chart donut-chart" data-total="8" data-value="<?= $gdp_growth ?>" data-class="ct-series-a"></div>
                  <div class="ct-chart donut-chart" data-total="8" data-value="<?= $gdp_growth_comparison_1 ?>" data-class="ct-series-b" data-delay="2"></div>
                  <div class="ct-chart donut-chart" data-total="8" data-value="<?= $gdp_growth_comparison_2 ?>" data-class="ct-series-c" data-delay="3"></div>
                </div>
              </div>
            </div>
            <div class="gdp-growth-labels">
              <div class="inner-row stat-row">
                <span class="stat-num ct-series-a"><?= $gdp_growth ?>%</span>
                <span class="stat-label"><?= $post->post_title ?></span>
              </div>

              <div class="inner-row">
                <span class="stat-num ct-series-b"><?= $gdp_growth_comparison_1 ?>%</span>
                <span class="stat-label"><?= $gdp_growth_comparison_1_label ?></span>
              </div>

              <div class="inner-row">
                <span class="stat-num ct-series-c"><?= $gdp_growth_comparison_2 ?>%</span>
                <span class="stat-label"><?= $gdp_growth_comparison_2_label ?></span>
              </div>
            </div>
          </div><!-- END .row -->

          <div class="row half-width-row -persist">
            <div class="inflation stat-row -persist">
              <div class="inner-row">
                <span class="stat-num"><?= $inflation ?>%</span>
                <span class="stat-label" data-source="CIA World Fact Book">Inflation</span>
              </div>
            </div>
            <div class="foreign-direct-investments stat-row">
              <div class="inner-row wrap">
                <span class="stat-num">$<?= $foreign_direct_investments ?>M</span>
                <span class="stat-label" data-source="World Bank">Foreign Direct<br> Investments</span>
              </div>
            </div>
          </div><!-- END .row -->

          <div class="row half-width-row -persist">
            <div class="inflation stat-row">
              <div class="inner-row wrap">
                <span class="stat-num ratio">
                  <span class="num1"><?= $ease_of_doing_business_ranking_arr[0] ?></span>
                  <i>/</i>
                  <span class="num2"><?= $ease_of_doing_business_ranking_arr[1] ?></span>
                </span>
                <span class="stat-label" data-source="World Bank">Ease of Doing Business Ranking</span>
              </div>
            </div>
            <div class="foreign-direct-investments stat-row">
              <div class="inner-row wrap">
                <span class="stat-num ratio">
                  <span class="num1"><?= $world_corruption_ranking_arr[0] ?></span>
                  <i>/</i>
                  <span class="num2"><?= $world_corruption_ranking_arr[1] ?></span>
                </span>
                <span class="stat-label" data-source="Transparency International">World Corruption Ranking</span>
              </div>
            </div>
          </div><!-- END .row -->

          <div class="row half-width-row -persist">
            <div class="average-exchange-rate stat-row">
              <div class="inner-row">
                <span class="exchange-rate-comparison">
                  <?= $average_exchange_rate ?> <span class="currency"><?= $currency_code ?></span>
                  <i>/</i> $1&nbsp;<span class="currency">USD</span>
                </span>
              </div>
            </div>
            <div class="exchange-rate-details stat-row">
              <span class="stat-label" data-source="Currencylayer API">Current Average Exchange Rate</span>
              <span class="stat-label" data-source="Transparency International">Currency: <?= $currency_description ?></span>
            </div>
          </div><!-- END .row -->
        </div><!-- END .outlook-stats -->
      </div><!-- END .grid-text-group -->
      <div class="grid-text-group">
        <h3 class="tab">Key Sectors</h3>
        <div class="user-content">
          <h3>Key Sectors</h3>
          <?= apply_filters('the_content', $key_sectors_intro) ?>
        </div>

        <div class="row half-width-row key-sector-stats">
          <div class="gdp-by-sector stat-row chart-row">
            <div class="chart">
              <div class="pie-label">GDP<br> by Sector</div>
              <div class="pie-chart ct-square"><div class="ct-chart"></div></div>
            </div>

            <div class="inner-row">
              <span class="stat-num ct-series-a"><?= $gdp_percent_agriculture ?>%</span>
              <span class="stat-label">Agriculture</span>
            </div>
            <div class="inner-row">
              <span class="stat-num ct-series-b"><?= $gdp_percent_service ?>%</span>
              <span class="stat-label">Service</span>
            </div>
            <div class="inner-row">
              <span class="stat-num ct-series-c"><?= $gdp_percent_industry ?>%</span>
              <span class="stat-label">Industry</span>
            </div>
          </div>

          <div class="workforce-by-sector stat-row chart-row">
            <div class="chart">
              <div class="pie-label">Workforce by Sector</div>
              <div class="pie-chart ct-square"><div class="ct-chart"></div></div>
            </div>

            <div class="inner-row">
              <span class="stat-num ct-series-a"><?= $workforce_percent_agriculture ?>%</span>
              <span class="stat-label">Agriculture</span>
            </div>
            <div class="inner-row">
              <span class="stat-num ct-series-b"><?= $workforce_percent_service ?>%</span>
              <span class="stat-label">Service</span>
            </div>
            <div class="inner-row">
              <span class="stat-num ct-series-c"><?= $workforce_percent_industry ?>%</span>
              <span class="stat-label">Industry</span>
            </div>
          </div>
        </div><!-- END .row -->

        <div id="sources-list">
          <h3>Sources</h3>
          <ul>
            <li><span>*</span> <a target="_blank" href="http://data.worldbank.org/">World Bank</a></li>
            <li><span>**</span> <a target="_blank" href="https://www.cia.gov/library/publications/the-world-factbook">CIA World Fact Book</a></li>
            <li><span>^</span> <a target="_blank" href="http://www.prb.org/">Population Reference Bureau</a></li>
            <li><span>^^</span> <a target="_blank" href="https://www.transparency.org/country/">Transparency International</a></li>
            <li><span>^^^</span> <a target="_blank" href="https://currencylayer.com/">Currencylayer API</a></li>
          </ul>
        </div>

      </div><!-- END .grid-text-group -->

    </div><!-- END .body-inner -->
  </div><!-- END .grid-item-body -->
</article>