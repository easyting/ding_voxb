(function ($) {
  // Extract ting id from classname
  Drupal.extractTingId = function(e) {
    classname = $(e).attr('class');
    id = classname.match(/isbn-(\S+)/);

    return (id != null && typeof id[1] != 'undefined') ? id[1] : 0;
  };

  // Insert voxb into the page
  Drupal.insertVoxbDetails = function(e) {
    if (e.status == true && e.items) {
      Drupal.voxb_item.details = e.items;
      $.each(e.items, function(k, v) {
        var ele = $('.voxb-details.isbn-' + k);
        ele.find('.voxb-rating .rating:lt(' + Math.round(v.rating / 20) + ')').removeClass('star-off').addClass('star-on');
        if (v.rating_count > 0) {
          ele.find('.rating-count span').html('(' + v.rating_count + ')');
        }

        var e = ele.find('.voxb-reviews');
        e.find('.count').html('(' + v.reviews + ')');
        e.hide();
        if (parseInt(v.reviews) > 0) {
          e.show();
        }
      });
    }

    // Hide empty VoxB fields.
    var voxb_fields = $('.field-name-voxb-rating').each(function(){
      var $this = $(this);
      if ($this.find('a.rating').length == 0) {
        $this.hide();
      }
    });
  };

  Drupal.behaviors.voxb_details = {
    attach : function(context) {
      var item_ids = [];

      $('.ding-voxb-rating-display-only-placeholder a.rating').click(function() {
        return false;
      });

      $('.ting-cover', context).each(function(i, e) {
        var id = Drupal.extractTingId(e);

        if (id != undefined && id != 0) {
          item_ids.push(id);
        }
      });

      if (item_ids.length > 0) {
        $.ajax({
          url : '/voxb/ajax/details',
          type : 'POST',
          data : {
            items : item_ids
          },
          success : Drupal.insertVoxbDetails
        });
      }
    }
  }
})(jQuery);
