<?php

namespace Drupal\lb_jobs\Entity;

use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Entity\ContentEntityInterface;

/**
 * Defines the Lunch Bucket City Geocode entity.
 *
 * @ingroup lb
 *
 * @ContentEntityType(
 *   id = "lb_city_geocode",
 *   label = @Translation("Lunch Bucket City Geocode"),
 *   base_table = "lb_city_geocode",
 *   entity_keys = {
 *     "id" = "id",
 *   },
 * )
 */
class CityGeocode extends ContentEntityBase implements ContentEntityInterface {
  /**
   * Field deffinitions for this entity.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the translation entity.'))
      ->setReadOnly(TRUE);

    $fields['entity_id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Entity ID'))
      ->setDescription(t('The ID of the referenced entity.'))
      ->setReadOnly(TRUE);

    $fields['locality'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Locality'))
      ->setDescription(t('A string that identifies the city.'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setReadOnly(TRUE);

    $fields['administrative_area'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Administrative Area'))
      ->setDescription(t('A string that identifies the state.'))
      ->setSettings([
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ])
      ->setReadOnly(TRUE);

    $fields['lat'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Latitude'))
      ->setDescription(t('A float that contains latitude.'))
      ->setSettings([
        'default_value' => '',
      ])
      ->setReadOnly(TRUE);

    $fields['lng'] = BaseFieldDefinition::create('float')
      ->setLabel(t('Longitude'))
      ->setDescription(t('A float that contains longitude.'))
      ->setSettings([
        'default_value' => '',
      ])
      ->setReadOnly(TRUE);

    $fields['created'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Created'))
      ->setDescription(t('The create date for the entity.'))
      ->setReadOnly(TRUE);

    return $fields;
  }
}
