<?php

namespace Drupal\lb_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;


/**
 * Sets up a 'Hero' block.
 *
 * @Block(
 *   id = "lb_home_page_hero",
 *   admin_label = @Translation("Home Page Hero"),
 * )
 */
class LunchBucketHomeHeroBlock extends BlockBase {

  public function build() {
    return ['#markup' => 'Boooommmm....'];
  }

}
