(function ($) {

  'use strict';

  /**
   * Toggle home page map.
   */
  Drupal.behaviors.mapToggle = {
    'attach': function (context) {

      for (var key in drupalSettings.stateMap) {
        simplemaps_usmap_mapdata.state_specific[key] = drupalSettings.stateMap[key];
      }

      simplemaps_usmap.load();

      $('#map-tabs--state').on('click', function() {
        $('#map-states').show();
        $('#map-cities').hide();
      });

      $('#map-tabs--city').on('click', function() {
        $('#map-states').hide();
        $('#map-cities').show();

        if (simplemaps_usmap_cities.loaded != true) {
          simplemaps_usmap_cities_mapdata.locations = drupalSettings.cityMap.locations;
          simplemaps_usmap_cities.load();
        }
      });
    }
  };

})(jQuery);
