jQuery(function() {
  var animate_duration = 200; // milliseconds
  var $ = jQuery;
  var toggle_list = function(button, sublists) {
    if (button.hasClass('arrow-down')) { // show sublist
      button.removeClass('arrow-down').addClass('arrow-up');
      sublists.slideDown(animate_duration);
    } else { // hide sublist
      button.removeClass('arrow-up').addClass('arrow-down');
      sublists.slideUp(animate_duration);
    }
  }
  var traverse_children = function(list) {
    list.children('li').each(function() {
      var child = $(this);
      var sublists = child.children('ul,ol');
      if (sublists.length > 0) { // has sublist
        var button = $('<span class="arrow-down"></span>');
        button.click(function() { toggle_list(button, sublists); });
		  child.children('.menu-list-item').append(button);
		  if (child.hasClass('active') || child.hasClass('active_ancestor')) {
			  button.removeClass('arrow-down').addClass('arrow-up');
		  } else {
			  sublists.hide();
		  }
      }
    });
  };
  traverse_children($('.contractable'));
});
