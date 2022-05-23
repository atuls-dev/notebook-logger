function input_row(type) {
	var html  = '<tr valign="top">'
		+ '<th></th>'
		+ '<td>'
		+   '<input type="text"  class="regular-text" name="option[' + type + '][new][]"  value="" >'
		+ '</td><td>'
		+ '<button class="nlbtnRemove" data-id="" type="button">Remove</button>'
		+ '</td></tr>';
	return html;
}

jQuery("body").on("click",".nlbtnAdd",function(){
	var type = jQuery(this).attr('data-type');
	var row = input_row(type)
	if( type == 'trigger'){
		jQuery(".tblTrigger").append(row);
	}else if( type == 'emotion' ){
		jQuery(".tblEmotion").append(row);
	}else if( type == 'cope' ){
		jQuery(".tblCope").append(row);
	}
});


jQuery("body").on("click",".nlbtnRemove",function(){
	var id = jQuery(this).attr('data-id');
	var ele = jQuery(this);
	if( id != '' ) {
		jQuery.ajax({
		    url: nl_ajax.ajaxurl, // this is the object instantiated in wp_localize_script function
		    type: 'POST',
		    data: { 
		    	action: 'nlRemoveOption',
		    	option_id: id
			},
		    success: function( data ){
		    	var res = JSON.parse(data);
		    	if(res.status == 'success'){
			    	ele.parents("tr").remove();
		    	}
		    }
		});
	}else{
    	ele.parents("tr").remove();
	}

});