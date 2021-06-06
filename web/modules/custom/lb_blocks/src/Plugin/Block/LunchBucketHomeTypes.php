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

    $query = \Drupal::entityQuery('taxonomy_term');
    $query->condition('vid', "job_type");
    $tids = $query->execute();
    $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);

    $data = [];

    foreach ($terms as $term) {

      $icon = $term->get('field_icon')->entity;

      if ($icon !== NULL) {
        $icon_url = file_create_url($icon->getFileUri());
      }

      $t = [
        'term' => $term->name->value,
        'icon' => $icon_url,
      ];

      $data[] = $t;
    }

    return [
      '#theme' => 'block__home_types',
      '#data' => $data,
    ];
  }

}
