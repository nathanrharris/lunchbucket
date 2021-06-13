<?php

namespace Drupal\lb_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\lb_blocks\LB_Blocks;


/**
 * Sets up a 'Map' block.
 *
 * @Block(
 *   id = "lb_home_page_map",
 *   admin_label = @Translation("Home Page Map"),
 * )
 */
class LunchBucketHomeMap extends BlockBase {

  public function build() {

    $states = LB_Blocks::getStateList();

    $stateCounts = LB_Blocks::getStateCounts();

    foreach ($states as $state => $name) {

      $count = $stateCounts[$state];

      if (isset($count) && $count > 0) {
        $attached['drupalSettings']['stateMap'][$state] = [
          'name' => $name,
          'color' => "#999",
          'hover_color' => "#666",
          'label_color' => '#FFF',
          'url' => "http://lb.local",
          'description' => $count . ' ' . $name  . ' jobs!!!',
        ];
      }
      else {
        $attached['drupalSettings']['stateMap'][$state] = [
          'name' => $name,
          'color' => "#444",
          'hover_color' => "#444",
          'inactive' => 'yes',
        ];
      }
    }

    $cityCounts = LB_Blocks::getCityCounts();

    foreach ($cityCounts as $city => $d) {

      $attached['drupalSettings']['cityMap']['locations'][] = [
        'name' => $city,
        'lat' => $d['lat'],
        'lng' => $d['lng'],
        'description' => $d['description'],
        'type' => 'circle',
        'color' => '#FFB611',
        'url' => 'http://www.foo.com',
        'size' => 20,
        'border_color' => '#666',
      ];

    }

    return [
      '#theme' => 'block__home_map',
      '#attached' => $attached,
    ];
  }

}
