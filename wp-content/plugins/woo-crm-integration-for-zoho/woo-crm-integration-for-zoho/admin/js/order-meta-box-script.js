jQuery(document).ready(function () {

	const ajax_url = ajax_data.ajax_url;
	const action = ajax_data.ajax_action;
	const nonce = ajax_data.ajax_nonce;


	jQuery('#wciz-zoho-manual-sync-button').on('click', function (e) {
		e.preventDefault();

		var feed_id = jQuery('#wciz-zoho-manual-sync-select').val();
		var post_id = jQuery('#wciz-shop-order-meta-box-wrap').attr('data-object_id_delete');
		if (feed_id == '' || post_id == '') {

			alert('Select feed');
			return;
		}
		var event = 'sync_object_manually';
		jQuery.post(ajax_url, { action, nonce, event, feed_id, post_id }).done(function (response) {
			response = JSON.parse(response);
			if (200 == response.status) {
				triggerSuccess(response.message);
			} else {
				triggerError(response.message);
			}
		})
	})

	jQuery('#wciz-zoho-delete-sync_data-button').on('click', function (e) {
		e.preventDefault();

		var feed_id = jQuery('#wciz-zoho-delete-sync_data-select').val();
		var post_id = jQuery('#wciz-shop-order-meta-box-wrap').attr('data-object_id_delete');
		if (feed_id == '' || post_id == '') {

			alert('Select feed');
			return;
		}
		var event = 'delete_object_keys_from_woo';
		jQuery.post(ajax_url, { action, nonce, event, feed_id, post_id }).done(function (response) {
			response = JSON.parse(response);
			if (200 == response.status) {
				triggerDeleteSuccess(response.message);
			} else {
				triggerDeleteError(response.message);
			}
		})
	})

});

const triggerError = (message) => {
	swal("Something Went Wrong..!", message, "error");
}

const triggerSuccess = (message) => {
	swal("Process Completed", message, "success");
}

const triggerDeleteError = (message) => {
	swal("Something Went Wrong", message, "error");

}

const triggerDeleteSuccess = (message) => {
	swal("Process Completed", message, "success");
	window.location.reload();
}