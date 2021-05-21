<?php

namespace Drupal\lb_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;


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

    $attached['drupalSettings']['stateMap']['PA'] = '1,000,000 jobs!!!';

    return [
      '#theme' => 'block__home_map',
      '#attached' => $attached,
    ];
  }

}
