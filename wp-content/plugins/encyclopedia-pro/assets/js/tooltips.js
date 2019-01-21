(function($){
  'use strict';

  window.Encyclopedia_Tooltips.links = $('a.encyclopedia, .widget_encyclopedia_taxonomies ul.taxonomy-list li.cat-item > a').tooltipster({
    animationDuration: parseInt(Encyclopedia_Tooltips.animation_duration), // duration of the animation, in milliseconds
    delay: parseInt(Encyclopedia_Tooltips.delay), // delay before the tooltip starts its opening and closing animations
    distance: 5, // distance between the origin and the tooltip, in pixels
    maxWidth: 480, // maximum width for the tooltip
    theme: 'encyclopedia-tooltip', // this will be added as class to the tooltip wrapper
  });

}(jQuery));
