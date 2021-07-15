<?php

namespace Drupal\lb_blocks\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\field\Entity\FieldConfig;


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
    $query->condition('vid', 'job_type');
    $query->condition('status', 1);
    $query->sort('weight');
    $tids = $query->execute();
    $terms = \Drupal\taxonomy\Entity\Term::loadMultiple($tids);

    $field_info = FieldConfig::loadByName('taxonomy_term', 'job_type', 'field_icon');
    $image_uuid = $field_info->getSetting('default_image')['uuid'];
    $image = \Drupal::service('entity.repository')->loadEntityByUuid('file', $image_uuid);
    $icon_url = file_create_url($image->getFileUri());

    $data = [];

    foreach ($terms as $term) {

      $icon = $term->get('field_icon')->entity;

      if ($icon !== NULL) {
        $icon_url = file_create_url($icon->getFileUri());
      }

      $t = [
        'term' => $term->name->value,
        'icon' => $icon_url,
        'featured' => $term->get('field_featured')->value,
      ];

      $data[] = $t;
    }

    return [
      '#theme' => 'block__home_types',
      '#data' => $data,
    ];
  }

}
