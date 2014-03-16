jQuery(window).load(function() {
  var map_location = Drupal.settings.map_location;
  var map_settings = Drupal.settings.map_settings;
  var map_id = Drupal.settings.map_id;
  var bounds = new google.maps.LatLngBounds();
  var map_types = {
    'ROADMAP': google.maps.MapTypeId.ROADMAP,
    'SATELLITE': google.maps.MapTypeId.SATELLITE,
    'HYBRID': google.maps.MapTypeId.HYBRID,
    'TERRAIN': google.maps.MapTypeId.TERRAIN,
  }
  var map_style = (map_settings.style.style != '' ? map_settings.style.style : '[]');
  // var map_pin = (map_settings.style.pin != '' ? map_settings.style.pin : '');
  var init_map = {
    zoom: parseInt(map_settings.zoom.default),
    mapTypeId: map_types[map_settings.style.maptype],
    disableDefaultUI: !map_settings.ui,
    maxZoom: parseInt(map_settings.zoom.max),
    minZoom: parseInt(map_settings.zoom.min),
    styles: JSON.parse(map_style),
    mapTypeControl: map_settings.maptypecontrol,
    panControl: map_settings.pancontrol,
    zoomControl: map_settings.zoomcontrol,
    streetViewControl: map_settings.streetviewcontrol
  }
  var map = new google.maps.Map(document.getElementById(map_id), init_map);
  var infowindow = new google.maps.InfoWindow({content: "holding..."});
  var marker = new google.maps.Marker({
    position: new google.maps.LatLng(map_location.lat , map_location.lon),
    map: map,
    html: map_settings.popup.text,
    icon: map_settings.style.pin,
  });
  google.maps.event.addListener(marker, 'click', function () {
    if (map_settings.popup.text) {
      infowindow.setContent(this.html);
      infowindow.open(map, this);
    }
  });

  bounds.extend(marker.getPosition());
  map.fitBounds(bounds);
});
