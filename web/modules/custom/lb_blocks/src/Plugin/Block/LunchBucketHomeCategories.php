<?php

namespace Drupal\lb_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\lb_blocks\LB_Blocks;

/**
 * Sets up a 'Categories' block.
 *
 * @Block(
 *   id = "lb_home_page_categories",
 *   admin_label = @Translation("Home Page Categories"),
 * )
 */
class LunchBucketHomeCategories extends BlockBase {

  public function build() {

    $data[] = [
      'category' => 'Jobs by State',
      'items' => LB_Blocks::getStates(),
    ];

    $data[] = [
      'category' => 'Jobs by City',
      'items' => LB_Blocks::getCities(),
    ];

    $category = [
      3 => 'Jobs by Job Type',
      4 => 'Jobs by Company',
    ];

    for ($x=3; $x<=4;$x++) {
      $data[] = [
        'category' => $category[$x],
        'items' => [
          'foo' => 'foo_url',
          'bar' => 'bar_url',
          'baz' => 'baz_url',
          'foo2' => 'foo_url',
          'bar2' => 'bar_url',
          'baz2' => 'baz_url',
          'foo3' => 'foo_url',
          'bar3' => 'bar_url',
          'baz3' => 'baz_url',
        ],
      ];
    }


    return [
      '#theme' => 'block__home_categories',
      '#data' => $data,
    ];
  }

}
