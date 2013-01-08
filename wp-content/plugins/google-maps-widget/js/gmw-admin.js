/*
 * Google Maps Widget
 * (c) Web factory Ltd, 2012
 */

jQuery(function($) {
  $('.gmw-tabs').each(function(i, el) {
    el_id = $(el).attr('id');
    $(el).tabs({ selected: get_active_tab(el_id),
                 show: function(event, ui) { $.cookie($(this).attr('id'), $(this).tabs('option', 'selected'), { expires: 7 }); }
    });
  });
  
  // get active tab index from cookie
  function get_active_tab(el_id) {
    id = parseInt(0 + $.cookie(el_id), 10);
        
    return id;
  } // get_active_tab
  
  // re-tab on GUI rebuild
  $('div[id*="googlemapswidget"]').ajaxSuccess(function(event, request, option) {
    $('.gmw-tabs').each(function(i, el) {
      el_id = $(el).attr('id');
      $(el).tabs({ selected: get_active_tab(el_id),
                   show: function(event, ui) { $.cookie($(this).attr('id'), $(this).tabs('option', 'selected'), { expires: 7 }); }
      });
    });
  });
}); // onload