/*
 * Google Maps Widget
 * (c) Web factory Ltd, 2012
 */

jQuery(function($) {
    $('a.gmw-thumbnail-map').click(function() {
      dialog = $($(this).attr('href'));
      map_width = dialog.attr('data-map-width');
      map_height = dialog.attr('data-map-height');
      map_url = dialog.attr('data-map-iframe-url');
      map_title = dialog.attr('title');
      map_skin = dialog.attr('data-map-skin');
      
      var content = $(dialog.html());
      content.filter('.gmw-map').html('<iframe width="' + map_width + 'px" height="' + map_height + 'px" src="' + map_url + '"></iframe>');

      $.fancybox( { 'wrapCSS': map_skin, 'type': 'html', 'content': content, 'title': map_title, 'autoSize': true, 'minWidth': map_width, 'minHeight': map_height } );
      
      return false;
    });
}); // onload