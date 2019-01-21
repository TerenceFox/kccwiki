(function($){
  'use strict';

  $('form.encyclopedia.search-form input#encyclopedia-search-term').each(function(){
    var
      $input = $(this),
      $form = $input.parents('form:first');

    $input.autocomplete({
      minLength: Encyclopedia_Search.minLength,
      delay: Encyclopedia_Search.delay,
      source: Encyclopedia_Search.ajax_url,
      appendTo: $form,
      select: function(event, ui){
        if (ui.item.url){
          window.location.href = ui.item.url;
        }
        else {
          $input.val(ui.item.value);
          $form.submit();
        }
      }
    })
    .autocomplete('instance')._renderItem = function(ul, item){
      return $('<li>')
        .data('url', item.url)
        .append(item.label)
        .appendTo(ul);
    };
  });

}(jQuery));
