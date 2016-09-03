SUMMARY
-------
The Styled Google Map module can embed a Google Map to any node entity.
The module nicely integrates as a Geofield formatter. You can overwrite the
theming function if necessary.

REQUIREMENTS
------------
This module requires the following modules:
 * Geofield (https://drupal.org/project/geofield)
 * System Stream Wrapper (https://drupal.org/project/system_stream_wrapper)

INSTALLATION
------------
 * Install as usual, see http://drupal.org/node/70151 for further information.

CONFIGURATION
-------------
 * Configure configuration in Structure » Content Types » Your content type:
  - Include a Geofield for your node content type.
  - In the Display View, choose Styled Google Map as format.
  - Give in your desired Google Map settings.

  - For API key, see: https://developers.google.com/maps/documentation/javascript/get-api-key
    for further information.
  - For client id, see: https://developers.google.com/maps/documentation/javascript/get-api-key#client-id
 * Configure GMaps API key from 'admin/config/content/styled_google_map'
  - See: https://developers.google.com/maps/documentation/javascript/get-api-key
 	for further information.

Suggestions for additional settings are most welcome.

VIEWS INTEGRATION
-------------
 * Styled Google Map provides views style plugin that gives opportunity to create the map with multiple locations.
  To use this feature you need to create a view as usual and in style options select "Styled Google Map". The main settings
  are data source - GeoField, Pin source - image field. Optionally you can add popup window on top location that 
  is triggered with mouse click.
 * All other optional settings duplicate the settings of the field formatter. 
 * There is ability to add several blocks with the maps on one page, just add the blocks to the page using standard block
  layout configuration page. 
  
CUSTOMIZATION
-------------
 * You may override the default theming function THEMENAME_styled_google_map().

TROUBLESHOOTING
---------------
 * When the Google Map is grey or not showing at all, check:
  - If the pin location correct.
  - If the JSON style has a correct syntax.
 * Also try further troubleshooting in the javascript console of your browser.

CONTACT
-------
 * Current maintainers:
  - Nicky Vandevoorde (iampuma) - http://drupal.org/user/2529238
