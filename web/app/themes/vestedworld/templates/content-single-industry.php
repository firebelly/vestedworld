<?php
// Single Industry
$photo = get_the_post_thumbnail($post->ID, 'grid-large');
$body = apply_filters('the_content', $post->post_content);

$timeline = get_post_meta($post->ID, '_cmb2_timeline', true);
$news_links = get_post_meta($post->ID, '_cmb2_news_links', true);
$video_links_parsed = get_post_meta($post->ID, '_cmb2_video_links_parsed', true);
$image_slideshow = get_post_meta($post->ID, '_cmb2_image_slideshow', true);

$regional_label = get_post_meta($post->ID, '_cmb2_regional_label', true);
$total_current_size_region = get_post_meta($post->ID, '_cmb2_total_current_size_region', true);
$total_current_size_world = get_post_meta($post->ID, '_cmb2_total_current_size_world', true);
$expected_size_region = get_post_meta($post->ID, '_cmb2_expected_size_region', true);
$expected_size_world = get_post_meta($post->ID, '_cmb2_expected_size_world', true);
$percentage_gdp_region = get_post_meta($post->ID, '_cmb2_percentage_gdp_region', true);
$percentage_gdp_world = get_post_meta($post->ID, '_cmb2_percentage_gdp_world', true);
$percentage_workforce_region = get_post_meta($post->ID, '_cmb2_percentage_workforce_region', true);
$percentage_workforce_world = get_post_meta($post->ID, '_cmb2_percentage_workforce_world', true);
$percentage_consumer_spending_region = get_post_meta($post->ID, '_cmb2_percentage_consumer_spending_region', true);
$percentage_consumer_spending_world = get_post_meta($post->ID, '_cmb2_percentage_consumer_spending_world', true);

$subsectors = apply_filters('the_content', get_post_meta($post->ID, '_cmb2_subsectors', true));
$global_leaders = apply_filters('the_content', get_post_meta($post->ID, '_cmb2_global_leaders', true));
$trends = apply_filters('the_content', get_post_meta($post->ID, '_cmb2_trends', true));
$risks_challenges = apply_filters('the_content', get_post_meta($post->ID, '_cmb2_risks_challenges', true));
$future_outlook = apply_filters('the_content', get_post_meta($post->ID, '_cmb2_future_outlook', true));
$sources_list = get_post_meta($post->ID, '_cmb2_sources_list', true);

// Clean up numeric fields, removing $ and any other random characters
foreach ([
    'total_current_size_region',
    'total_current_size_world',
    'expected_size_region',
    'expected_size_world',
    'percentage_gdp_region',
    'percentage_gdp_world',
    'percentage_workforce_region',
    'percentage_workforce_world',
    'percentage_consumer_spending_region',
    'percentage_consumer_spending_world',
  ] as $clean_me) {
  // Remove any chars not a digit, comma, period, or slash
  $$clean_me = preg_replace('/[^\d\.,\/BT]/i', '', $$clean_me);
}
?>

<article id="<?= $post->post_name ?>" class="grid-item-data single industry" data-id="<?= $post->ID ?>" data-page-title="<?= $post->post_title ?>" data-page-url="<?= get_permalink($post) ?>">
  <div class="-left">
    <div class="grid-item-inner">
      <div class="grid-item-image">
        <div class="grid-item-image-inner">
          <h1 class="section-title"><?= $post->post_title ?></h1>
          <?= $photo ?>
        </div>
      </div>

      <div class="grid-item-text intro-text user-content">
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

      <?php if (!empty($image_slideshow) || !empty($video_links_parsed)): ?>
      <div class="grid-item-text">
        <h3 class="tab">Multimedia</h3>
        <?= \Firebelly\Utils\video_slideshow($video_links_parsed) ?>
        <?= \Firebelly\Utils\image_slideshow($image_slideshow) ?>
      </div>
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
        <h3 class="tab">Statistics</h3>
        <div class="user-content">

          <table class="industry-stats" summary="Statistics for industry <?= $post->post_title ?> in <?= $regional_label ?> vs. the world">
            <thead>
              <tr>
                <th scope="col"><?= $regional_label ?>:</th>
                <th scope="col">World:</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>
                  <div class="row stat-row">
                    <span class="stat-num">$<?= $total_current_size_region ?></span>
                    <span class="stat-label">Total Current Size ($)</span>
                  </div>
                </td>
                <td>
                  <div class="row stat-row">
                    <span class="stat-num">$<?= $total_current_size_world ?></span>
                    <span class="stat-label">Total Current Size ($)</span>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="row stat-row">
                    <span class="stat-num"><?= !empty($expected_size_region) ? '$'.$expected_size_region : '–' ?></span>
                    <span class="stat-label">Expected Size by 2030 ($)</span>
                  </div>
                </td>
                <td>
                  <div class="row stat-row">
                    <span class="stat-num"><?= !empty($expected_size_world) ? '$'.$expected_size_world : '–' ?></span>
                    <span class="stat-label">Expected Size by 2030 ($)</span>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="row chart-row">
                    <div class="chart ct-square">
                      <div class="ct-chart donut-inner"></div>
                      <div class="ct-chart donut-chart" data-total="100" data-value="<?= $percentage_gdp_region ?>" data-class="ct-series-a"></div>
                    </div>
                    <div class="chart-labels">
                      <span class="stat-num"><?= $percentage_gdp_region ?>%</span>
                      <span class="stat-label">of GDP</span>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="row chart-row">
                    <div class="chart ct-square">
                      <div class="ct-chart donut-inner"></div>
                      <?php if (!empty($percentage_gdp_world)): ?>
                        <div class="ct-chart donut-chart" data-total="100" data-value="<?= $percentage_gdp_world ?>" data-class="ct-series-a"></div>
                      <?php endif ?>
                    </div>
                    <div class="chart-labels">
                      <span class="stat-num"><?= !empty($percentage_gdp_world) ? $percentage_gdp_world.'%' : '–' ?></span>
                      <span class="stat-label">of GDP</span>
                    </div>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="row chart-row">
                    <div class="chart ct-square">
                      <div class="ct-chart donut-inner"></div>
                      <div class="ct-chart donut-chart" data-total="100" data-value="<?= $percentage_workforce_region ?>" data-class="ct-series-a"></div>
                    </div>
                    <div class="chart-labels">
                      <span class="stat-num"><?= $percentage_workforce_region ?>%</span>
                      <span class="stat-label">of Workforce</span>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="row chart-row">
                    <div class="chart ct-square">
                      <div class="ct-chart donut-inner"></div>
                      <?php if (!empty($percentage_workforce_world)): ?>
                        <div class="ct-chart donut-chart" data-total="100" data-value="<?= $percentage_workforce_world ?>" data-class="ct-series-a"></div>
                      <?php endif ?>
                    </div>
                    <div class="chart-labels">
                      <span class="stat-num"><?= !empty($percentage_workforce_world) ? $percentage_workforce_world.'%' : '–' ?></span>
                      <span class="stat-label">of Workforce</span>
                    </div>
                  </div>
                </td>
              </tr>

              <tr>
                <td>
                  <div class="row chart-row">
                    <div class="chart ct-square">
                      <div class="ct-chart donut-inner"></div>
                      <?php if (!empty($percentage_consumer_spending_region)): ?>
                        <div class="ct-chart donut-chart" data-total="100" data-value="<?= $percentage_consumer_spending_region ?>" data-class="ct-series-a"></div>
                      <?php endif ?>
                    </div>
                    <div class="chart-labels">
                      <span class="stat-num"><?= !empty($percentage_consumer_spending_region) ? $percentage_consumer_spending_region.'%' : '–' ?></span>
                      <span class="stat-label">of Total Consumer Spending</span>
                    </div>
                  </div>
                </td>
                <td>
                  <div class="row chart-row">
                    <div class="chart ct-square">
                      <div class="ct-chart donut-inner"></div>
                      <?php if (!empty($percentage_consumer_spending_world)): ?>
                        <div class="ct-chart donut-chart" data-total="100" data-value="<?= $percentage_consumer_spending_world ?>" data-class="ct-series-a"></div>
                      <?php endif ?>
                    </div>
                    <div class="chart-labels">
                      <span class="stat-num"><?= !empty($percentage_consumer_spending_world) ? $percentage_consumer_spending_world.'%' : '–' ?></span>
                      <span class="stat-label">of Total Consumer Spending</span>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div><!-- END .grid-text-group -->

      <div class="grid-text-group">
        <h3 class="tab">Subsectors</h3>
        <div class="user-content">
          <?= $subsectors ?>
        </div>
      </div>

      <div class="grid-text-group">
        <h3 class="tab">Global Leaders</h3>
        <div class="user-content">
          <?= $global_leaders ?>
        </div>
      </div>

      <div class="grid-text-group">
        <h3 class="tab">Trends</h3>
        <div class="user-content">
          <?= $trends ?>
        </div>
      </div>

      <div class="grid-text-group">
        <h3 class="tab">Risks &amp; Challenges</h3>
        <div class="user-content">
          <?= $risks_challenges ?>
        </div>
      </div>

      <div class="grid-text-group">
        <h3 class="tab">Future Outlook</h3>
        <div class="user-content">
          <?= $future_outlook ?>
        </div>
      </div>

      <?php if ($sources_list): ?>
        <div class="grid-text-group">
          <div id="sources-list">
            <h3>Sources</h3>
            <?= apply_filters('the_content', $sources_list) ?>
          </div>
        </div>
      <?php endif; ?>

    </div><!-- END .body-inner -->
  </div><!-- END .grid-item-body -->
</article>