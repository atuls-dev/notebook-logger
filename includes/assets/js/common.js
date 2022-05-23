var poper 			= jQuery('#nl_poper'),
	formTargets 	= ["trigger","emotion","cope"],
	edit_icn		= '<img src="'+nl_ajax.assets+'img/notebook-done-check-icon.png" class="nl_png_icons" width="24" height="24">';

jQuery(function(jQuery){
	console.log();
	/*setInterval(function(){
		jQuery('.nl_current_time').val(moment().format("MMMM D, h:mm:ss a"));
		jQuery('.nl_current_time_view').html(moment().format("MMMM D, h:mm a"));
	}, 1000);*/
	jQuery(document).on('change click','.toggle_chart',function(){
		if(jQuery(this).is(':checked')){
			jQuery('.smoking_chart').hide();
			jQuery('.smoke_chart_stat').hide();
			jQuery('.craving_chart').show();
			jQuery('.crav_chart_stat').show();
			jQuery('.cha_smk').removeClass('active');
			jQuery('.cha_cra').addClass('active');
		}else{
			jQuery('.craving_chart').hide();
			jQuery('.crav_chart_stat').hide();
			jQuery('.smoking_chart').show(); 
			jQuery('.smoke_chart_stat').show();
			jQuery('.cha_smk').addClass('active');
			jQuery('.cha_cra').removeClass('active');
		}
	});
	jQuery('#btn-nl-main, .btn-nl-logger').bind('click', function(e) {
				 var tz = Intl.DateTimeFormat().resolvedOptions().timeZone;

				// var cc = Intl.DateTimeFormat().resolvedOptions().timeZone;
			 	//alert(tz);
                // Prevents the default action to be triggered.
                e.preventDefault();
                if(jQuery('body').hasClass('need_login')){
        			location.href = nl_ajax.login_url;
                }else{
                	var winwidth = jQuery(window).width()/2,
                		pos      = winwidth-300;
                	closePopup();
                	jQuery('#nl_poper').bPopup({
			            position: [pos,100],
			            positionStyle: 'fixed',
			            follow: true,
			            onOpen: function() {
			            	get_nl_option();
			            	jQuery('body').addClass('openPopup');
			            	jQuery('.nl_current_time').val(moment().format("MMMM D, h:mm:ss a"));
							jQuery('.nl_current_time_view').html(moment().format("MMMM D, h:mm a"));

							//var timezone_offset_minutes = new Date().getTimezoneOffset();
							//timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
							// Timezone difference in minutes such as 330 or -360 or 0
							//jQuery('#nl_time_zone').val(timezone_offset_minutes);

							var tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
							jQuery('#nl_time_zone').val(tz);
							//console.log(timezone_offset_minutes);
			            },
            			onClose: function() { closePopup(); }
			        });

                }


            });
	jQuery('.search-nl-option').on('input',function(){
		var	type   = jQuery(this).data('option'),
			value  = jQuery(this).val(),
			elem   = this,
			data   = {'value':value,'type':type,'elem':elem};
			get_searched_option(data);


	});

    jQuery('.prev_page').on('click',function(){
		var crnt = jQuery(this).attr('currentpage'),
			prev = jQuery(this).attr('prevpage'),
			name	= jQuery(this).data('name'),
			target	= jQuery(this).data('target');

		if(name !="reason"){
			var trigga = jQuery('#nl_new_'+name).val();
			if(trigga){
				data = {'name':name,'target':target,'value':trigga};
				if(jQuery('#nl_new_'+name).hasClass('data_available')){
					popup_existing_options(data);
					jQuery('#nl_new_'+name).trigger('input');
				}else{
					popup_options(data);
					jQuery('#nl_new_'+name).trigger('input');
				}
			}
		}
		jQuery('.'+crnt).removeClass('Slide_in');
    	jQuery('.'+prev).removeClass('Slide_out');

	});


	jQuery('.nl_my_options').on('input keypress',function(e){
		var trigga = jQuery(this).val(),
			name 	= jQuery(this).attr('name'),
			target	= jQuery(this).data('target'),
			elem   = this,
			sdata 	= {'value':trigga,'type':name,'elem':elem};
		get_searched_option(sdata);
		if (e.which == 13) {
			if(trigga==""){
				jQuery('.prev_page').trigger("click");
				return false;
			}
		}

		if (e.which == 13) {
			if(trigga){
				var name 	= jQuery(this).attr('name'),
					target	= jQuery(this).data('target'),
				    data = {'name':name,'target':target,'value':trigga};
				    if(jQuery(this).hasClass('data_available')){
						popup_existing_options(data);
						jQuery(this).trigger('input');
					}else{
						popup_options(data);
						jQuery(this).trigger('input');
					}

				/*popup_options(data);*/
				return false;
			}

		}
	});

	function get_searched_option(data){
		var value = data.value.toLowerCase();
        var value_length = value.length;
       	jQuery(".filter-"+data.type).filter(function () {
       		jQuery(this).toggle(jQuery(this).text().toLowerCase().indexOf(value) > -1);
       	});
       	jQuery('.nl_my_'+data.type+'_wrapper').show();
   		jQuery('.nl_other_'+data.type+'_wrapper').show();
   		jQuery('.nl_no_match').remove();
   		jQuery(data.elem).addClass('data_available');
       	if(jQuery(".filter-"+data.type+":visible").length == 0){
       			jQuery('.nl_my_'+data.type+'_wrapper').hide();
       			jQuery('.nl_other_'+data.type+'_wrapper').hide();
       			jQuery('.nl_no_match').remove();
       			jQuery(data.elem).removeClass('data_available');
       			jQuery('.nl_other_'+data.type+'_wrapper').after('<span class="nl_no_match">No match found.</span>');
       		}
	}
	function popup_options(option){
		jQuery('#nl_new_'+option.name).val('');
		jQuery('#nl_new_'+option.name).focusout();
		nl_new_option(option.target,option.value,option.name);

		
	}
	function popup_existing_options(option){
		jQuery('#nl_new_'+option.name).val('');
		jQuery('#nl_new_'+option.name).focusout();
		var search_str = option.value.replace(/\s+/g, '-').toLowerCase()+'_'+option.name;
		jQuery('#'+search_str).attr('checked', 'checked').trigger('change');
		jQuery('.nl_edit_'+option.name).removeClass('btn-add').addClass('btn-edit').html(edit_icn);
		jQuery('.nl_edit_'+option.name).closest('li').removeClass('nl-validate nl-error');
		jQuery('#nl_'+option.name+'_front').text(option.value);
		poper.find('.nl_my_'+option.name+'_wrapper').show();
      	poper.find('.nl_other_'+option.name+'_wrapper h4').show();
	}
	function capitalize(str) {
	  strVal = '';
	  str = str.split(' ');
	  for (var chr = 0; chr < str.length; chr++) {
	    strVal += str[chr].substring(0, 1).toUpperCase() + str[chr].substring(1, str[chr].length) + ' '
	  }
	  return strVal
	}
	jQuery('.nl_edit_option').on('click',function(){
		var name = jQuery(this).data('name');
		jQuery('.add_smoking_wrapper').addClass('Slide_out');
    	jQuery('.choose_'+name+'_wrapper').addClass('Slide_in');
    	if(name == 'reason'){
    		setTimeout(function(){jQuery('.nl_reason_area').focus();},1000);

    	}


	});


	jQuery('.step').on('click',function(){
		var title  =jQuery(this).attr('data-val');
		jQuery('.nl_smoking_intensity').val(title);
	});


	jQuery('.steps').on('click', '.step--active', function() {
	  jQuery(this).removeClass('step--incomplete').addClass('step--complete');
	  jQuery(this).removeClass('step--active').addClass('step--inactive');
	  jQuery(this).next().removeClass('step--inactive').addClass('step--active');
	});

	jQuery('.steps').on('click', '.step--inactive,.step--incomplete', function() {
	  jQuery(this).removeClass('step--incomplete').addClass('step--complete');
	  jQuery(this).removeClass('step--active').addClass('step--inactive');
	  jQuery(this).next().removeClass('step--inactive').addClass('step--active');
	  jQuery(this).prevAll().removeClass('step--incomplete').addClass('step--complete');
	  jQuery(this).prevAll().removeClass('step--inactive').addClass('step--active');
	});

	jQuery('.steps').on('click', '.step--complete', function() {
	  jQuery(this).removeClass('step--complete').addClass('step--incomplete');
	  jQuery(this).removeClass('step--inactive').addClass('step--active');
	  jQuery(this).nextAll().removeClass('step--complete').addClass('step--incomplete');
	  jQuery(this).nextAll().removeClass('step--active').addClass('step--inactive');
	});

	//jQuery(document).on('submit','.form_smoking_notebook',function(e){
	jQuery('.form_smoking_notebook').on('submit',function(e){
		e.preventDefault();

		if( !jQuery('#nl_smoke_crav').is(":checked") ) {
			jQuery('.nl-craving-field').removeClass('nl-validate');
		}
		var valid = jQuery('.nl-validate').length;
		if(valid > 0){
			jQuery('.nl-validate').addClass('nl-error');
		}else{

			jQuery.ajax({
			    url: nl_ajax.ajaxurl,
			    type: 'POST',
			    data:jQuery(this).serialize(),
			    beforeSend: function(){
			    	jQuery('.nl_wait').show();
			    	jQuery('.nl_submit').hide();
			    },
			    success: function( data ){
			      var options 	= jQuery.parseJSON(data);
			      jQuery('#nl_poper').bPopup().close();
			      closePopup();
			      if(options.status == 200){
			      	Swal.fire(
					  'Good job!',
					  options.msg,
					  'success'
					);
			      }else{
			      	Swal.fire({
					  icon: 'error',
					  title: 'Oops...',
					  text: options.msg,
					});
			      }

			    },
			    complete : function(data){
			    	jQuery('.nl_wait').hide();
			    	jQuery('.nl_submit').show();
			    }
			 });
		}
	});



	mobiscroll.settings = {
        lang: 'en',                       // Specify language like: lang: 'pl' or omit setting to use default
        theme: 'ios',                     // Specify theme like: theme: 'ios' or omit setting to use default
        themeVariant: 'light',            // More info about themeVariant: https://docs.mobiscroll.com/4-10-6/javascript/datetime#opt-themeVariant
        display: 'bubble',                 // Specify display mode like: display: 'bottom' or omit setting to use default
        wheelOrder: 'ddhhii'
    };
    var instance = mobiscroll.datetime('#nl_time ', {
        showOnTap: false,                 // More info about showOnTap: https://docs.mobiscroll.com/4-10-6/javascript/datetime#opt-showOnTap
        showOnFocus: false,
        dateWheels: '|D M d|',
        dateFormat: 'M dd,',
        max: new Date(),                  // More info about showOnFocus: https://docs.mobiscroll.com/4-10-6/javascript/datetime#opt-showOnFocus
        onInit: function (event, inst) {  // More info about onInit: https://docs.mobiscroll.com/4-10-6/javascript/datetime#event-onInit
            inst.setVal(new Date(), true);
        },onSet: function(event, inst){
        	console.log(event.valueText);
        	jQuery('#nl_hidden_time').val(event.valueText);
            jQuery('#nl_time_front').text(event.valueText);
            //jQuery('#nl_hidden_time').removeClass('nl_current_time');
           // jQuery('#nl_time_front').removeClass('nl_current_time_view');
        },onShow: function(event, inst){
        	var dval = jQuery('#nl_time_front').text();
        	jQuery('.form_smoking_notebook').addClass('nl_wait');
        	inst.setVal(dval, true);
        },onClose: function(event, inst){
        	 setTimeout(function(){
        	 	jQuery('.form_smoking_notebook').removeClass('nl_wait');
        	 }, 1000);
        }
    });

	var el = document.getElementById('nl_time');
	if(el){
	    document
	        .getElementById('nl_time')
	        .addEventListener('click', function () {
	            instance.show();
	        }, false);


    }

    jQuery(window).keypress(function(e) {

	  if (e.which == 13) {
	  	e.preventDefault();
	  	if(jQuery('.add_smoking_wrapper').hasClass('Slide_out')){
	  		formTargets.push('reason');
	  			jQuery.each(formTargets, function( k, v ) {
					jQuery('.choose_'+v+'_wrapper').removeClass('Slide_in');

				});
				jQuery('.add_smoking_wrapper').removeClass('Slide_out');


	  	} else {
	  		if(!jQuery('.form_smoking_notebook').hasClass('nl_wait')){
	  			jQuery('.form_smoking_notebook').submit();
	  		}

		}
	  }
	});

});

jQuery(document).on('change click','#nl_smoke_crav',function(){
	if(jQuery(this).is(':checked')){
		jQuery('.nl-smoking-field').hide();
		jQuery('.nl-craving-field').show();
		if(jQuery('input[name=cope]:checked').length < 1){
			jQuery('.nl-craving-field').addClass('nl-validate');
		}
		jQuery('.nl-smoking-field').removeClass('nl-validate');
		jQuery('.nl_act_smoke').removeClass('active_nl_carv');
		jQuery('.nl_act_crav').addClass('active_nl_carv');
	}else{
		jQuery('.nl-smoking-field').show();
		jQuery('.nl-craving-field').hide();
		if(jQuery('input[name=emotion]:checked').length < 1){
			jQuery('.nl-smoking-field').addClass('nl-validate');
		}
		jQuery('.nl-craving-field').removeClass('nl-validate');
		jQuery('.nl_act_smoke').addClass('active_nl_carv');
		jQuery('.nl_act_crav').removeClass('active_nl_carv');
	}

});
jQuery(document).on('click','.edit_log_nl_box',function(e){
	var exclude = ["edit_log_nl", "delete_log_nl", "view_log_nl"];
	if(jQuery.inArray(e.target.className, exclude) !== -1){
		return;
	}
	if (e.target.tagName == "svg") {
       return;
    }
	var fData = jQuery(this).data('src');
	get_nl_option('',function(){
		jQuery('input:radio[name="trigger"][value="'+fData.triggers+'"]').attr('checked', 'checked').trigger('change');
		jQuery('input:radio[name="emotion"][value="'+fData.emotion+'"]').attr('checked', 'checked').trigger('change');
		jQuery('input:radio[name="cope"][value="'+fData.cope+'"]').attr('checked', 'checked').trigger('change');
		jQuery('.nl_reason_area').val(fData.reason).trigger('input');
		jQuery('.nl_smoking_intensity').val(fData.intensity);
		if(jQuery('#nl-intensity_'+fData.intensity).hasClass('step--incomplete')){
			jQuery('#nl-intensity_'+fData.intensity).trigger('click');
		}
		jQuery('#notebook_types').val('edit');
		jQuery('#notebook_type_id').val(fData.id);
		jQuery("#nl_time_front" ).text(fData.time);
		jQuery('#nl_trigger_front').text(fData.triggers);
		jQuery('#nl_emotion_front').text(fData.emotion);
		jQuery('#nl_cope_front').text(fData.cope);
		jQuery('#nl_reason_front').text(fData.reason.substring(0,20));
		if(fData.etype == 'craving'){
			jQuery('input:checkbox[name="etype"]').prop('checked', true).trigger('change');
		}else{
			jQuery('input:checkbox[name="etype"]').prop("checked", false).trigger('change');
		}

		var winwidth = jQuery(window).width()/2,
                		pos      = winwidth-300;
                	jQuery('#nl_poper').bPopup({
			            position: [pos,100],
			            positionStyle: 'fixed',
			            onOpen: function() {
			            	jQuery('body').addClass('openPopup');
			            	jQuery('.nl_current_time').val(moment().format("MMMM D, h:mm:ss a"));
							jQuery('.nl_current_time_view').html(moment().format("MMMM D, h:mm a"));

							//var timezone_offset_minutes = new Date().getTimezoneOffset();
							//timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
							// Timezone difference in minutes such as 330 or -360 or 0
							//jQuery('#nl_time_zone').val(timezone_offset_minutes);
							//console.log(timezone_offset_minutes);
							var tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
							jQuery('#nl_time_zone').val(tz);
			            },
			            onClose: function() { closePopup(); }
			        });
	});
});
jQuery(document).on('click','.edit_log_nl',function(){
	var fData = jQuery(this).data('src');

	get_nl_option('',function(){
		jQuery('input:radio[name="trigger"][value="'+fData.triggers+'"]').attr('checked', 'checked').trigger('change');
		jQuery('input:radio[name="emotion"][value="'+fData.emotion+'"]').attr('checked', 'checked').trigger('change');
		jQuery('input:radio[name="cope"][value="'+fData.cope+'"]').attr('checked', 'checked').trigger('change');
		jQuery('.nl_reason_area').val(fData.reason).trigger('input');
		jQuery('.nl_smoking_intensity').val(fData.intensity);
		if(jQuery('#nl-intensity_'+fData.intensity).hasClass('step--incomplete')){
			jQuery('#nl-intensity_'+fData.intensity).trigger('click');
		}
		jQuery('#notebook_types').val('edit');
		jQuery('#notebook_type_id').val(fData.id);
		jQuery("#nl_time_front" ).text(fData.time);
		jQuery('#nl_trigger_front').text(fData.triggerVal);
		jQuery('#nl_emotion_front').text(fData.emotionVal);
		jQuery('#nl_cope_front').text(fData.copeVal);
		jQuery('#nl_reason_front').text(fData.reason.substring(0,20));
		jQuery('.nl_add_notebook .nl_submit').text('Update Entry');
		if(fData.etype == 'craving'){
			jQuery('input:checkbox[name="etype"]').prop('checked', true).trigger('change');
		}else{
			jQuery('input:checkbox[name="etype"]').prop("checked", false).trigger('change');
		}

		var winwidth = jQuery(window).width()/2,
                		pos      = winwidth-300;
                	jQuery('#nl_poper').bPopup({
			            position: [pos,100],
			            positionStyle: 'fixed',
			            onOpen: function() {
			            	jQuery('body').addClass('openPopup');
			            	jQuery('.nl_current_time').val(moment().format("MMMM D, h:mm:ss a"));
							jQuery('.nl_current_time_view').html(moment().format("MMMM D, h:mm a"));

							//var timezone_offset_minutes = new Date().getTimezoneOffset();
							//timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;
							// Timezone difference in minutes such as 330 or -360 or 0
							//jQuery('#nl_time_zone').val(timezone_offset_minutes);
							//console.log(timezone_offset_minutes);
							var tz = Intl.DateTimeFormat().resolvedOptions().timeZone;
							jQuery('#nl_time_zone').val(tz);

			            },
			            onClose: function() { closePopup(); }
			        });
	});




});
jQuery(document).on('keydown','.nl_admin_stats_filter',function(e) {
	if(jQuery(this).val()){
	    if (e.keyCode == 13) {
	    	e.preventDefault();
	        jQuery(this).closest('form').submit();
	    }
	}
});

function filter_admin_stat(elem){
	jQuery.ajax({
	    url  : nl_ajax.ajaxurl,
	    type : 'POST',
	    data : jQuery(elem).serialize(),
	    success: function( data ){
	      var options 	= jQuery.parseJSON(data);
	      if(options.status == 200){
	      	var stat = '';
	      	var res = '<div class="nl-searchresult"> <p> <span>Searching for: '+options.data.email+'</span><span class="nl_resetadminresult"> <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"> <g> <g> <path d="M257,0C116.39,0,0,114.39,0,255s116.39,257,257,257s255-116.39,255-257S397.61,0,257,0z M383.22,338.79 c11.7,11.7,11.7,30.73,0,42.44c-11.61,11.6-30.64,11.79-42.44,0L257,297.42l-85.79,83.82c-11.7,11.7-30.73,11.7-42.44,0 c-11.7-11.7-11.7-30.73,0-42.44l83.8-83.8l-83.8-83.8c-11.7-11.71-11.7-30.74,0-42.44c11.71-11.7,30.74-11.7,42.44,0L257,212.58 l83.78-83.82c11.68-11.68,30.71-11.72,42.44,0c11.7,11.7,11.7,30.73,0,42.44l-83.8,83.8L383.22,338.79z"></path> </g> </g> </svg> </span> </p><p>Total: '+options.data.count+' entries</p></div>';
	      		jQuery.each(options.data.stats, function( k, v ) {
	      			stat +="<div class='nl-summary-wrap-left'>";
	      			stat +="<h3 class='cap_text'>"+v.title+"</h3>";
	      			stat +="<p>"+v.timing+"</p></div>";
			        stat +="<div class='nl-summary-wrap-right'>";
			        stat +="<span class='nl_intensity_widget' style='width:"+v.percent+"'></span>";
			        stat +="<span class='nl_text_widget'>"+v.percent+"</span></div>";
			    });

			    jQuery('.reset_nl_filter').remove();
	      		jQuery(elem).parent('.nl-widget-wrap').find('.nl-widget-wrap--inner').html(stat);
	      		if(!options.data.filter){
	      			jQuery(elem).parent('.nl-widget-wrap').find('.nl-widget-wrap--inner').before(res);
	      		}else{
	      			jQuery('.nl-searchresult').remove();

	      		}
	      		jQuery(elem).find('.nl_admin_stats_filter').val('');
	      }else{
	      	Swal.fire({
			  icon: 'error',
			  title: 'Oops...',
			  text: options.message,
			});
	      }


	    }
	  });
}
jQuery(document).on('click','.nl_resetadminresult',function(){
	jQuery(this).parent().parent().parent().find('form').append('<input class="reset_nl_filter" type="hidden" name="filter" value="reset">');
    jQuery(this).parent().parent().parent().find('form').submit();
});
jQuery(document).on('click','.view_log_nl',function(){
	get_nl_option();

	var fData = jQuery(this).data('src');
	var dtype = (fData.etype == 'craving') ? 'Craving ':'Smoking ';
	jQuery(".nl-single-trigger" ).text(fData.triggers);
	jQuery(".nl-single-emotion" ).text(fData.emotion);
	jQuery(".nl-single-reason" ).text(fData.reason);
	jQuery(".nl-single-intensity" ).text(fData.intensity);
	jQuery(".nl-single-cope" ).text(fData.cope);
	jQuery(".nl-single-date" ).text(dtype+fData.time);
	if(fData.etype == 'craving'){
		jQuery('.smoke_view').hide();
		jQuery('.crav_view').show();
	}else{
		jQuery('.smoke_view').show();
		jQuery('.crav_view').hide();
	}
	jQuery('.nl_log_list').fadeOut('300');
	jQuery('.nl-single-log').fadeIn('300');


});


jQuery('.back-nl-list').on('click',function(){
	jQuery('.nl-single-log').fadeOut('300');
	jQuery('.nl_log_list').fadeIn('300');
});


jQuery(document).on('click','.delete_log_nl',function(){
	var log_id = jQuery(this).data('id');
	Swal.fire({
	  title: 'Are you sure?',
	  text: "You won't be able to revert this!",
	  icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Yes, delete it!'
	}).then((result) => {
	  if (result.value) {
	    jQuery.ajax({
			    url: nl_ajax.ajaxurl,
			    type: 'POST',
			    data:{'action':'delete_log','log_id':log_id},
			    success: function( data ){
			      var options 	= jQuery.parseJSON(data);
			      if(options.status == 200){
			      	jQuery('.nl-block-'+log_id).remove();
			      }


			    }
			  });
	  }
	});



});
jQuery(document).on('change','.nl_options_data',function(){
	var name = jQuery(this).attr('name');
	jQuery('.nl_edit_'+name).removeClass('btn-add').addClass('btn-edit').html(edit_icn);
	jQuery('.nl_edit_'+name).closest('li').removeClass('nl-validate nl-error');
	jQuery('#nl_'+name+'_front').text(jQuery(this).data('name'));
	jQuery('#nl_'+name+'_front').css('color','#232a42');
});
jQuery(document).on('input','.nl_reason_area',function(){
	jQuery('.nl_edit_reason').removeClass('btn-add').addClass('btn-edit').html(edit_icn);
	jQuery('.nl_edit_reason').closest('li').removeClass('nl-validate nl-error');
	jQuery('#nl_reason_front').text(jQuery(this).val().substring(0,20));
	jQuery('#nl_reason_front').css('color','#232a42');
});
function closePopup(){
	jQuery('.nl_reason_area').val('');
	jQuery('.nl_smoking_intensity').val('1');
	if(jQuery('#nl-intensity_1').hasClass('step--active')){
		jQuery('#nl-intensity_1').trigger('click');
	}
	//jQuery('#nl_hidden_time').addClass('nl_current_time');
    //jQuery('#nl_time_front').addClass('nl_current_time_view');
	jQuery('#notebook_types').val('add');
	jQuery('#notebook_type_id').val('');
	formTargets.push('reason');
	jQuery('.add_smoking_wrapper').removeClass('Slide_out');
	jQuery.each(formTargets, function( k, v ) {
		jQuery('.choose_'+v+'_wrapper').removeClass('Slide_in');
		jQuery('.nl_edit_'+v).removeClass('btn-edit').addClass('btn-add').text('ADD');
		jQuery('.nl_edit_'+v).closest('li').removeClass('nl-error').addClass('nl-validate');
		jQuery('#nl_new_'+v).val('');
		jQuery('.nl-'+v+'-wrapper-main').show();
		jQuery('.add_'+v+'_wrapper').removeClass('Slide_in');
	});
	jQuery('input:checkbox[name="etype"]').prop("checked", false).trigger('change');
	jQuery('#nl_trigger_front').html('What triggered my carving?');
	jQuery('#nl_emotion_front').html('How did I feel before smoking?');
	jQuery('#nl_reason_front').html('Why did I smoke?');
	jQuery('#nl_cope_front').html('What helped you overcoming the carving?');
	jQuery('.nl_add_notebook .nl_submit').text('Done');
	jQuery('body').removeClass('openPopup');
}
function get_nl_option(option , callback){
	/*if (typeof callback === 'function') {
		        callback();
		    }*/
	var data = {action: 'nloptions'};
	jQuery('.nl_reason_area').val('');
	jQuery('.nl_smoking_intensity').val('1');
	if(jQuery('#nl-intensity_1').hasClass('step--incomplete')){
		jQuery('#nl-intensity_1').trigger('click');
	}
	jQuery('#notebook_types').val('add');
	jQuery('#notebook_type_id').val('');

		if (option){
			data.type = option.type;
			data.value = option.value;
		}
	jQuery.ajax({
	    url: nl_ajax.ajaxurl, // this is the object instantiated in wp_localize_script function
	    type: 'POST',
	    data:data,
	    success: function( data ){
	      var options 	= jQuery.parseJSON(data);
	      console.log(options);
	      jQuery.each(formTargets, function( k, v ) {
	      	if(options.myData){
	      		nl_input(options.myData[v],poper.find('.nl_my_'+v),v);
	      		if(options.myData[v]){
			      	poper.find('.nl_my_'+v+'_wrapper').show();
			      	poper.find('.nl_other_'+v+'_wrapper h4').show();
			      }else{
			      	poper.find('.nl_my_'+v+'_wrapper').hide();
			      	poper.find('.nl_other_'+v+'_wrapper h4').hide();
			      }
			}else{
		      	poper.find('.nl_my_'+v+'_wrapper').hide();
			    poper.find('.nl_other_'+v+'_wrapper h4').hide();
		      	poper.find('.nl_my_'+v).empty();

		      }
		    if(options.otherData){
		    	console.log('--d');
		    	console.log(options.otherData[v]);
		    	console.log('--d');
		    	nl_input(options.otherData[v],poper.find('.nl_other_'+v),v);

	  	  	}else{
	  	  		poper.find('.nl_other_'+v).empty();

	  	  	}

	  	  	if(!options.myData && !options.otherData){
	  	  		jQuery('.nl_other_'+v).html('No Matching '+v);
	  	  	}

		  });
	    },complete:function(){
	    	if (typeof callback === 'function') {
		        callback();
		    }
	    }

	  });
}

function nl_new_option(type,value,name){
	var data = {action: 'nlnewoptions'};
	data.type = type;
	data.value = value,
 	jQuery.ajax({
	    url: nl_ajax.ajaxurl, // this is the object instantiated in wp_localize_script function
	    type: 'POST',
	    data:data,
	    success: function( data ){
	    	console.log(data);

		    var options = jQuery.parseJSON(data);

	    	if( options.status == 'success' ) {
			    var search_str = value.replace(/\s+/g, '-').toLowerCase();
				var trig = '<label class="custom-check filter-'+name+'"><span class="cap_text">'+value+'</span><input type="radio" class="nl_options_data " checked id="'+search_str+'_'+type+'" data-name="'+value+'" value="'+options.id+'" name="'+name+'"><span class="checkmark"></span></label>';
				jQuery('.nl_edit_'+name).removeClass('btn-add').addClass('btn-edit').html(edit_icn);
				jQuery('.nl_edit_'+name).closest('li').removeClass('nl-validate nl-error');
				jQuery('#nl_'+name+'_front').text(value);
				poper.find('.nl_my_'+name).prepend(trig);
				poper.find('.nl_my_'+name+'_wrapper').show();
		      	poper.find('.nl_other_'+name+'_wrapper h4').show();

	    	}

		    if(type == 'triggers'){
		      	jQuery('.add_trigger_wrapper').hide();
			}
			if(type == 'emotion'){
				jQuery('.add_emotion_wrapper').hide();
			}
	    }
	});
}


function nl_input(option,rclass,name){

	rclass.empty();
	if(option){
		jQuery.each( option, function( key, value ) {

			var search_str = value.value.replace(/\s+/g, '-').toLowerCase();
		 	var prelis = '<label class="custom-check filter-'+name+'"><span class=" cap_text">'+value.value+'</span><input type="radio" class="nl_options_data " id="'+search_str+'_'+name+'" data-name="'+value.value+'" value="'+value.id+'" name="'+name+'"><span class="checkmark"></span></label>';
		 	rclass.append(prelis);
		});

	}
}



