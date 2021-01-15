<?php

namespace Drupal\lb_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;


/**
 * Sets up a 'Types' block.
 *
 * @Block(
 *   id = "lb_home_page_types",
 *   admin_label = @Translation("Home Page Types"),
 * )
 */
class LunchBucketHomeTypes extends BlockBase {

  public function build() {
    return ['#markup' => 'Boooommmm Types....'];
  }

}
