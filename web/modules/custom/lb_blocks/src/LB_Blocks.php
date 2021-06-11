<?php

namespace Drupal\lb_blocks;

class LB_Blocks {

  public static function getStates($limit = 10) {

    $database = \Drupal::database();

    $query = $database->select('node__field_address', 'fa');

    $query->fields('fa', ['field_address_administrative_area']);

    $query->addExpression('count(*)', 'fa_count');

    $query->groupBy('fa.field_address_administrative_area');

    $query->orderBy('fa_count', 'DESC');

    $query->range(0, 10);

    $result = $query->execute();

    $data = array();

    while ($r = $result->fetchAssoc()) {
      $data[$r['field_address_administrative_area']] = $r['field_address_administrative_area'] . ' (' . $r['fa_count'] . ')';
    }

    return $data;
  }

  public static function getCities($limit = 10) {

    $database = \Drupal::database();

    $query = $database->select('node__field_address', 'fa');

    $query->fields('fa', ['field_address_locality']);

    $query->addExpression('count(*)', 'fa_count');

    $query->groupBy('fa.field_address_locality');

    $query->orderBy('fa_count', 'DESC');

    $query->range(0, 10);

    $result = $query->execute();

    $data = array();

    while ($r = $result->fetchAssoc()) {
      $data[$r['field_address_locality']] = $r['field_address_locality'] . ' (' . $r['fa_count'] . ')';
    }

    return $data;
  }

}
