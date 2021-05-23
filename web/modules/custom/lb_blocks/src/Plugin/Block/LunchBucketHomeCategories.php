<?php

namespace Drupal\lb_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;


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

    for ($x=1; $x<=4;$x++) {
      $data[] = [
        'category' => 'Category #' . $x,
        'items' => [
          'foo' => 'foo_url',
          'bar' => 'bar_url',
          'baz' => 'baz_url',
        ],
      ];
    }


    return [
      '#theme' => 'block__home_categories',
      '#data' => $data,
    ];
  }

}
