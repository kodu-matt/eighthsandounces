
jQuery(document).ready(function($) {

	const ajax_url = ajax_data_public.ajax_url ;
	const disconnect = ajax_data_public.disconnect ;
	const skip       = ajax_data_public.skip ;
	const action     = ajax_data_public.ajax_action ; 
	const nonce      = ajax_data_public.ajax_nonce ;
	const connect    = ajax_data_public.connect ;
	const feed_settings = ajax_data_public.feed_form_settings;
	const feed_back_link = ajax_data_public.feed_back_link; 
	const feed_back_text = ajax_data_public.feed_back_text; 
	const delete_icon   = ajax_data_public.delete_icon;
	const notice        = ajax_data_public.notice;
	const boolean       = ajax_data_public.boolean;
	const order_status  = ajax_data_public.order_status;
	const mapped_zoho_status = ajax_data_public.mapped_zoho_status;
	
		jQuery(document).on('change', '#billing_email', function() {
	
			var billing_email = jQuery('#billing_email').val();
	
			var billing_first_name = jQuery('#billing_first_name').val();
	
			var billing_last_name = jQuery('#billing_last_name').val();
	
			var billing_company = jQuery('#billing_company').val();
	
			var billing_phone = jQuery('#billing_phone').val();
	
			var user_data = {
				'first_name' : billing_first_name , 
				'last_name' : billing_last_name , 
				'company' : billing_company , 
				'phone' : billing_phone, 
			}
	
			var mailFormat =  /\S+@\S+\.\S+/;
	
			if(billing_email != "" && billing_email != "undefined"  && billing_email.match(mailFormat) ){
	
				data = {
					'action' : 'zoho_capture_billing_email' , 
					'billing_email' : billing_email, 
					'user_data' : user_data,
					'nonce' : nonce,
				}
	
				jQuery.post( ajax_url, data, function(response) {
				});
			}
		});
	
		jQuery(document).on('change', '#email', function() {
	
			var billing_email = jQuery('#email').val();
			
			var billing_first_name = jQuery('#shipping-first_name').val();
	
			var billing_last_name = jQuery('#shipping-last_name').val();
	
			var billing_company = jQuery('#billing_company').val();
	
			var billing_phone = jQuery('#shipping-phone').val();
	
			var user_data = {
				'first_name' : billing_first_name , 
				'last_name' : billing_last_name , 
				'company' : billing_company , 
				'phone' : billing_phone, 
			}
	
			var mailFormat =  /\S+@\S+\.\S+/;
	
			if(billing_email != "" && billing_email != "undefined"  && billing_email.match(mailFormat) ){
	
				data = {
					'action' : 'zoho_capture_billing_email' , 
					'billing_email' : billing_email, 
					'user_data' : user_data,
					'nonce' : nonce,
				}
	
				jQuery.post( ajax_url, data, function(response) {
				});
			}
		});
	});