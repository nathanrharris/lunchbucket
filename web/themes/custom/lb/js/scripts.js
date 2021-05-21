(function ($) {

  'use strict';

  /**
   * Toggle home page map.
   */
  Drupal.behaviors.mapToggle = {
    'attach': function (context) {
      $('#map-tabs--state').on('click', function() {
        $('#map-states').show();
        $('#map-cities').hide();
      });

      $('#map-tabs--city').on('click', function() {
        $('#map-states').hide();
        $('#map-cities').show();

        simplemaps_usmap_cities_mapdata.state_specific.PA.description = drupalSettings.stateMap.PA;

        if (simplemaps_usmap_cities.loaded != true) {
          simplemaps_usmap_cities.load();
        }
      });
    }
  };

})(jQuery);
