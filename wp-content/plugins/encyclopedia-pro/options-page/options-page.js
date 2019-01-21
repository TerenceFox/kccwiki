(function($){
  'use strict';

  $('input#enable_advanced_capabilities')
    .on('change', function(event){
      var checked = this.checked;
      $('div.capability-editor').toggle(checked);
      $('div.capability-selection input').prop('disabled', !checked);
    })
    .trigger('change');

}(jQuery));
