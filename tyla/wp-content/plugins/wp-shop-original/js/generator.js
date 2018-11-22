jQuery.extend(jQuery.validator.messages, {
  required: object_name.required,
  remote: object_name.remote,
  email: object_name.email,
  url: object_name.url,
  date: object_name.date,
  dateISO: object_name.dateISO,
  number: object_name.number,
  digits: object_name.digits,
  creditcard: object_name.creditcard,
  equalTo: object_name.equalTo,
  accept: object_name.accept,
  maxlength: jQuery.validator.format(object_name.maxlength),
  minlength: jQuery.validator.format(object_name.minlength),
  rangelength: jQuery.validator.format(object_name.rangelength),
  range: jQuery.validator.format(object_name.range),
  max: jQuery.validator.format(object_name.max),
  min: jQuery.validator.format(object_name.min),
  pattern: object_name.pattern
});

jQuery(document).ready(function () {
  jQuery('body').on('click', '.popup_wpshop_button',function(e){
    e.preventDefault();
    jQuery(this).magnificPopup(
      { 
        type:'inline',
        midClick: true,
        alignTop: true,
        callbacks: {
          open: function() {
            jQuery('body').addClass('wpshop-mfp-shown');
          },
          close: function() {
            jQuery('body').removeClass('wpshop-mfp-shown');
          }
        }
      }
    ).magnificPopup('open');
  });
  
  jQuery('body').on('click','.wpshop_shortcode', function(e) {
		e.preventDefault();
    var type = jQuery(this).data('type');
    
    jQuery.ajax({
			type: "POST",
			url: object_name.url_site+"/wp-admin/admin-ajax.php",
			data: {action:'choose_shortcode',short_type:type},
			success: function(t){ 
        jQuery('.wpshop_choose_shortcode').hide();
        jQuery('.wpshop_options_shortcode .inner').html(t);
        jQuery('.wpshop_options_shortcode').show();
      }
    });  

  });
  
  jQuery('body').on('change','input[name="variant"]', function(e) {
    var val = jQuery(this).val();
    if(val=='category') {
      jQuery('.shortcode_option_wrapp.select_tag').addClass('hidden');
      jQuery('.shortcode_option_wrapp.select_cat').removeClass('hidden');
    }else {
      jQuery('.shortcode_option_wrapp.select_cat').addClass('hidden');
      jQuery('.shortcode_option_wrapp.select_tag').removeClass('hidden');
    }
  });
  
  jQuery('body').on('change','.shortcode_option_wrapp.select_post_type select', function(e) {
    e.preventDefault();
    var val = jQuery(this).val();
    jQuery.ajax({
      type: "POST",
      url: object_name.url_site+"/wp-admin/admin-ajax.php",
      data: {action:'shortcode_post_type_change',post_type:val},
      beforeSend:function(){
        jQuery('.shortcode_option_wrapp.select_taxonomy select').empty();
        jQuery('.shortcode_option_wrapp.select_term select').empty();
        jQuery('<img class="wpshop_wait_img" src="'+object_name.wpshop+'/images/loader.gif"/>').prependTo('form#vitrina_custom_types');
      }, 
      success: function(t){ 
        var arr_tax = jQuery.parseJSON(t);
        jQuery.each(arr_tax, function (i, item) {
           jQuery('.shortcode_option_wrapp.select_taxonomy select').append(jQuery('<option>', { 
              value: i ,
              text : item.name 
          }));
        }); 
      },
      complete: function(){ 
        //jQuery('form#vitrina_custom_types .wpshop_wait_img').remove();
        jQuery('.shortcode_option_wrapp.select_taxonomy select').trigger('change'); 
      }
    });
  });
  
  jQuery('body').on('change','.shortcode_option_wrapp.select_taxonomy select', function(e) {
    e.preventDefault();
    var val = jQuery(this).val(),
    type = jQuery('.shortcode_option_wrapp.select_post_type select').val();
    
    jQuery.ajax({
      type: "POST",
      url: object_name.url_site+"/wp-admin/admin-ajax.php",
      data: {action:'shortcode_taxonomy_change',post_type:type,tax:val},
      beforeSend:function(){
        jQuery('.shortcode_option_wrapp.select_term select').empty();
        jQuery('<img class="wpshop_wait_img" src="'+object_name.wpshop+'/images/loader.gif"/>').prependTo('form#vitrina_custom_types');
      },
      success: function(t){ 
        var arr_tax = jQuery.parseJSON(t);
        jQuery.each(arr_tax, function (i, item) {
           jQuery('.shortcode_option_wrapp.select_term select').append(jQuery('<option>', { 
              value: i ,
              text : item.name 
          }));
        });
      },
      complete: function(){ 
        jQuery('form#vitrina_custom_types .wpshop_wait_img').remove();
      }
    });
  });
  
  jQuery('body').on('click','.return_select_shortcode', function(e) {
    e.preventDefault();
    jQuery('.wpshop_choose_shortcode').show();
    jQuery('.wpshop_options_shortcode').hide();
  });
  
  // подключаем IRIS
  jQuery('body').on('ajaxComplete',function(){
    jQuery('.color-picker input').wpColorPicker({palettes: true,defaultColor: false});
  });
  
  jQuery('body').on('click','.shortcode_button', function(e) {
		e.preventDefault();
    var type = jQuery(this).data('type');
    var form = jQuery(this).parents('form');
    
    if (type=='vitrina') {
      form.validate({
        rules: {
          cols: {
            required: true,
            digits: true
          },
          rows: {
            required: true,
            digits: true
          },
          height: {
            required: true,
            digits: true
          },
          text: {
            digits: true
          },
          width: {
            digits: true
          },
          button_rad: {
            digits: true
          },
          align: {
            required: false,
            pattern: /^\w+/
          },
          custom_class: {
            required: false,
            pattern: /^\w+/
          },
          field: {
            required: false,
            pattern: /^\w+/
          },
          img_height: {
            digits: true
          },
          shop_text: {
            required: false,
            pattern: /^[a-zа-яё \-<>'=/]+$/i
          },
          text_color: {
            required: false,
            pattern: /^\#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/
          },
          text_color_h: {
            required: false,
            pattern: /^\#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/
          },
          bg_color: {
            required: false,
            pattern: /^\#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/
          },
          bg_color_h: {
            required: false,
            pattern: /^\#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/
          }
        }
      });
    }
    
    if (type=='vitrina_custom_types') {
      form.validate({
        rules: {
          posttype:  {
            required: true
          },
          include_id: {
            required: false,
            pattern: /^[\d,]+$/
          },
          exclude_id: {
            required: false,
            pattern: /^[\d,]+$/
          },
          cols: {
            required: true,
            digits: true
          },
          rows: {
            required: true,
            digits: true
          },
          height: {
            required: true,
            digits: true
          },
          text: {
            digits: true
          },
          width: {
            digits: true
          },
          button_rad: {
            digits: true
          },
          align: {
            required: false,
            pattern: /^\w+/
          },
          custom_class: {
            required: false,
            pattern: /^\w+/
          },
          field: {
            required: false,
            pattern: /^\w+/
          },
          img_height: {
            digits: true
          },
          shop_text: {
            required: false,
            pattern: /^[a-zа-яё \-<>'=/]+$/i
          },
          text_color: {
            required: false,
            pattern: /^\#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/
          },
          text_color_h: {
            required: false,
            pattern: /^\#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/
          },
          bg_color: {
            required: false,
            pattern: /^\#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/
          },
          bg_color_h: {
            required: false,
            pattern: /^\#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/
          }
        }
      });
    }
    
    //console.log(form.valid());
    
    if(form.valid()){
      var form_data = JSON.stringify(jQuery( form ).serializeJSON());
      //console.log(form_data);
      jQuery.magnificPopup.close();
      jQuery.ajax({
        type: "POST",
        url: object_name.url_site+"/wp-admin/admin-ajax.php",
        data: {action:'generate_shortcode',short_type:type,form_data:form_data },
        success: function(t){ 
          jQuery('.wpshop_choose_shortcode').show();
          jQuery('.wpshop_options_shortcode').hide();
          wp.media.editor.insert(t);
        }
      });
    }    
  });
});