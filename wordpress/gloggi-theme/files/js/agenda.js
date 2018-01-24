function search_events(target) {
  $ = jQuery;
  var buttons = $('.agenda__sections .button');
  buttons.removeClass('button').addClass('button--inactive');
  if(target) {
    $(target).removeClass('button--inactive').addClass('button');
  }
  
  $('.agenda__entries-first > .agenda__entry').detach().prependTo('.agenda__entries');
  $('.agenda__entries .agenda__entry').sort(function(a, b) { return $(a).data('starttime') > $(b).data('starttime'); }).appendTo($('.agenda__entries'));
  $('#noentries').addClass('hide');
  $('#selectgroup').addClass('hide');
  if(target) {
    $('.agenda__entries .agenda__entry').addClass('hide');
    $('.' + $(target).data('showclass')).removeClass('hide');
    $first = $('.agenda__entries .agenda__entry').not('.hide');
    if($first.length) {
      $($first[0]).detach().appendTo('.agenda__entries-first');
    } else {
      $('#noentries').removeClass('hide');
    }
  } else {
    $('.agenda__entries .agenda__entry').addClass('hide');
    $('#selectgroup').removeClass('hide');
  }
}

function on_click_button(clickEvent) {
  $ = jQuery;
  if($(clickEvent.target).hasClass('button')) {
    search_events(null);
  } else {
    search_events(clickEvent.target);
  }
}

function get_query_params(qs) {
  qs = qs.split("+").join(" ");
  var params = {}, tokens, re = /[?&]?([^=]+)=([^&]*)/g;
  while (tokens = re.exec(qs)) params[decodeURIComponent(tokens[1])] = decodeURIComponent(tokens[2]);
  return params;
}

var geocoder;

function gloggi_mark_address(map, bounds, geocoder, coords, title="", single=false) {
  coords = coords.split("|");
  latLng = new google.maps.LatLng(parseFloat(coords[0]), parseFloat(coords[1]));
  bounds.extend(latLng);
  if(single) {
    map.setCenter(latLng);
    map.setZoom(15);
  } else {
    map.fitBounds(bounds);
  }
  return new google.maps.Marker({ map: map, position: latLng, label: title });
}

function gloggi_initialize_map(map) {
  var mapOptions = {
    mapTypeId: 'roadmap'
  };
  var gmap = new google.maps.Map(map, mapOptions);
  var bounds = new google.maps.LatLngBounds();
  var startCoords = map.getAttribute("data-address1");
  var endCoords = map.getAttribute("data-address2");
  if(startCoords == endCoords) {
    gloggi_mark_address(gmap, bounds, geocoder, startCoords, "A", true );
  } else {
    gloggi_mark_address(gmap, bounds, geocoder, startCoords, "A");
    gloggi_mark_address(gmap, bounds, geocoder, endCoords, "B");
  }
}

function gloggi_gmaps_ready() {
  $ = jQuery;
  geocoder = new google.maps.Geocoder();
  var maps = $('.agenda__map');
  $('.lightbox:target .agenda__map').each(function(idx, map) { gloggi_initialize_map(map); });
  window.onhashchange = function() { $('.lightbox:target .agenda__map').each(function(idx, map) { gloggi_initialize_map(map); }); };
}

jQuery(document).ready(function($){
  $('.agenda__sections a.button, .agenda__sections a.button--inactive').click(function(e) {
    e.preventDefault();
    var hash = $(e.target).attr("href");
    if(document.location.search == hash) {
      history.replaceState(null, document.title, document.location.pathname);
    } else {
      history.replaceState(get_query_params(document.location.search), document.title, hash);
    }
  });
  
  $('img.svg').each(function(){
    var $img = jQuery(this);
    var imgID = $img.attr('id');
    var imgClass = $img.attr('class');
    var imgURL = $img.attr('src');
    jQuery.get(imgURL, function(data) {
      var $svg = jQuery(data).find('svg');
      if(typeof imgID !== 'undefined') {
        $svg = $svg.attr('id', imgID);
      }
      if(typeof imgClass !== 'undefined') {
        $svg = $svg.attr('class', imgClass+' replaced-svg');
      }
      $svg = $svg.removeAttr('xmlns:a');
      $img.replaceWith($svg);
    }, 'xml');
  });
  
  var qp = get_query_params(document.location.search);
  var target = undefined;
  if("abteilung" in qp)
    target = qp["abteilung"];
  if("stufe" in qp) 
    target = qp["stufe"];
  if("gruppe" in qp) 
    target = qp["gruppe"];
  if(target != undefined) {
    target = document.getElementById(target);
  }
  search_events(target);
  
  var buttons = $('.agenda__sections .button, .agenda__sections .button--inactive');
  buttons.click(on_click_button);
  
  var script = document.createElement('script');
  script.src = "//maps.googleapis.com/maps/api/js?callback=gloggi_gmaps_ready&key=" + GmapsAPIKey;
  document.body.appendChild(script);
});
