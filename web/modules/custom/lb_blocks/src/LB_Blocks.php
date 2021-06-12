<?php

namespace Drupal\lb_blocks;

use Drupal\user\Entity\User;

class LB_Blocks {

  public static function getStates($limit = 10) {

    $database = \Drupal::database();

    //TODO: add join to node_field_data and check status/active

    $query = $database->select('node__field_address', 'fa');
    $query->fields('fa', ['field_address_administrative_area']);
    $query->addExpression('count(*)', 'fa_count');
    $query->groupBy('fa.field_address_administrative_area');
    $query->orderBy('fa_count', 'DESC');
    $query->range(0, $limit);

    $result = $query->execute();

    $data = array();

    while ($r = $result->fetchAssoc()) {
      $data[$r['field_address_administrative_area']] = $r['field_address_administrative_area'] . ' (' . $r['fa_count'] . ')';
    }

    return $data;
  }

  public static function getStateCounts() {

    $database = \Drupal::database();

    //TODO: add join to node_field_data and check status/active

    $query = $database->select('node__field_address', 'fa');
    $query->fields('fa', ['field_address_administrative_area']);
    $query->addExpression('count(*)', 'fa_count');
    $query->groupBy('fa.field_address_administrative_area');

    $result = $query->execute();

    $data = array();

    while ($r = $result->fetchAssoc()) {
      $data[$r['field_address_administrative_area']] = $r['fa_count'];
    }

    return $data;
  }

  public static function getCities($limit = 10) {

    $database = \Drupal::database();

    //TODO: add join to node_field_data and check status/active

    $query = $database->select('node__field_address', 'fa');
    $query->fields('fa', ['field_address_locality']);
    $query->addExpression('count(*)', 'fa_count');
    $query->groupBy('fa.field_address_locality');
    $query->orderBy('fa_count', 'DESC');
    $query->range(0, $limit);

    $result = $query->execute();

    $data = array();

    while ($r = $result->fetchAssoc()) {
      $data[$r['field_address_locality']] = $r['field_address_locality'] . ' (' . $r['fa_count'] . ')';
    }

    return $data;
  }

  public static function getCityCounts($limit = 100) {

    $database = \Drupal::database();

    //TODO: add join to node_field_data and check status/active

    $query = $database->select('node__field_address', 'fa');
    $query->fields('fa', ['field_address_locality']);
    $query->addExpression('count(*)', 'fa_count');
    $query->groupBy('fa.field_address_locality');
    $query->orderBy('fa_count', 'DESC');
    $query->range(0, $limit);

    $result = $query->execute();

    $data = array();

    while ($r = $result->fetchAssoc()) {
      $data[$r['field_address_locality']] = [
        'name' => $r['field_address_locality'],
        'lat' => 0,
        'lng' => 0,
        'description' => $r['fa_count'] . ' ' . $r['field_address_locality'] . ' jobs!!!',
      ];
    }

    return $data;
  }

  public static function getTypes($limit = 10) {

    $database = \Drupal::database();

    //TODO: add join to node_field_data and check status/active

    $query = $database->select('node__field_job_type', 'fjt');
    $query->fields('fjt', ['field_job_type_target_id']);
    $query->addExpression('count(*)', 'fjt_count');
    $query->groupBy('fjt.field_job_type_target_id');
    $query->orderBy('fjt_count', 'DESC');
    $query->range(0, 10);

    $result = $query->execute();

    $data = array();

    while ($r = $result->fetchAssoc()) {

      $term = \Drupal::entityTypeManager()->getStorage('taxonomy_term')->load($r['field_job_type_target_id']);

      $name = $term->get('name')->value;

      $data[$name] = $name . ' (' . $r['fjt_count'] . ')';
    }

    return $data;
  }

  public static function getCompanies($limit = 10) {

    $database = \Drupal::database();

    //TODO: add join to node_field_data and check status/active

    $query = $database->select('node_field_data', 'fd');
    $query->fields('fd', ['uid']);
    $query->condition('fd.status', 1);
    $query->condition('fd.type', 'job');
    $query->addExpression('count(*)', 'fd_count');
    $query->groupBy('fd.uid');
    $query->orderBy('fd_count', 'DESC');
    $query->range(0, 10);

    $result = $query->execute();

    $data = array();

    while ($r = $result->fetchAssoc()) {

      $user = User::load($r['uid']);
      $p = \Drupal::entityTypeManager()->getStorage('profile')->loadByUser($user, 'employer_profile');

      $company = $p->get('field_company_name')->value;

      $data[$company] = $company . ' (' . $r['fd_count'] . ')';
    }

    return $data;
  }

  public static function getStateList() {
    $states = [
      'AL' => 'Alabama',
      'AK' => 'Alaska',
      'AZ' => 'Arizona',
      'AR' => 'Arkansas',
      'CA' => 'California',
      'CO' => 'Colorado',
      'CT' => 'Connecticut',
      'DE' => 'Delaware',
      'FL' => 'Florida',
      'GA' => 'Georgia',
      'HI' => 'Hawaii',
      'ID' => 'Idaho',
      'IL' => 'Illinois',
      'IN' => 'Indiana',
      'IA' => 'Iowa',
      'KS' => 'Kansas',
      'KY' => 'Kentucky',
      'LA' => 'Louisiana',
      'ME' => 'Maine',
      'MD' => 'Maryland',
      'MA' => 'Massachusetts',
      'MI' => 'Michigan',
      'MN' => 'Minnesota',
      'MS' => 'Mississippi',
      'MO' => 'Missouri',
      'MT' => 'Montana',
      'NE' => 'Nebraska',
      'NV' => 'Nevada',
      'NH' => 'New Hampshire',
      'NJ' => 'New Jersey',
      'NM' => 'New Mexico',
      'NY' => 'New York',
      'NC' => 'North Carolina',
      'ND' => 'North Dakota',
      'OH' => 'Ohio',
      'OK' => 'Oklahoma',
      'OR' => 'Oregon',
      'PA' => 'Pennsylvania',
      'RI' => 'Rhode Island',
      'SC' => 'South Carolina',
      'SD' => 'South Dakota',
      'TN' => 'Tennessee',
      'TX' => 'Texas',
      'UT' => 'Utah',
      'VT' => 'Vermont',
      'VA' => 'Virginia',
      'WA' => 'Washington',
      'WV' => 'West Virginia',
      'WI' => 'Wisconsin',
      'WY' => 'Wyoming',
      'DC' => 'District of Columbia',
      'AS' => 'American Samoa',
      'GU' => 'Guam',
      'MP' => 'Northern Mariana Islands',
      'PR' => 'Puerto Rico',
      'UM' => 'United States Minor Outlying Islands',
      'VI' => 'Virgin Islands, U.S.',
    ];

    return $states;
  }
}
