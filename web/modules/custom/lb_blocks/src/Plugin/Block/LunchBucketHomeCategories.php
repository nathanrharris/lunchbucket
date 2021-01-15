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
    return ['#markup' => 'Boooommmm Categories....'];
  }

}
