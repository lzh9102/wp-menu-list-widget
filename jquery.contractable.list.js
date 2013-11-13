jQuery(function() {
  var $ = jQuery;
  var toggle_list = function(button, sublists) {
    if (button.hasClass('arrow-down')) { // show sublist
      button.removeClass('arrow-down').addClass('arrow-up');
      sublists.show();
    } else { // hide sublist
      button.removeClass('arrow-up').addClass('arrow-down');
      sublists.hide();
    }
  }
  var traverse_children = function(list) {
    list.children('li').each(function() {
      var child = $(this);
      var sublists = child.children('ul,ol');
      if (sublists.length > 0) { // has sublist
        var button = $('<span class="arrow-down"></span>');
        button.click(function() { toggle_list(button, sublists); });
        button.insertBefore(sublists.first());
        sublists.hide();
		  if (child.hasClass('active') || child.hasClass('active_ancestor'))
          toggle_list(button, sublists); // open current active lists
      }
    });
  };
  traverse_children($('.contractable'));
});
