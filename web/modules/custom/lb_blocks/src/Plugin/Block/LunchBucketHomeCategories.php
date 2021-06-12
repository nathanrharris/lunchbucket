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

    $data[] = [
      'category' => 'Jobs by Type',
      'items' => LB_Blocks::getTypes(),
    ];

    $data[] = [
      'category' => 'Jobs by Company',
      'items' => LB_Blocks::getCompanies(),
    ];

    return [
      '#theme' => 'block__home_categories',
      '#data' => $data,
    ];
  }

}
