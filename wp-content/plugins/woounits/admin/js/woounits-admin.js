(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	jQuery(document).ready(function () {

		if ($(".wut_product_cats").length > 0) {
			$(".wut_product_cats").select2({
				width: '100%',
				placeholder: "Select product or categories..",
				allowClear: false
			});
		}

		// Function to add a new raw in unit level table.
		$(document).on('click', '.wut_btn_add_unt_level', function (e) {
			e.preventDefault();

			let button = $(this);
			let cid = parseInt(button.data('ind'));
			var table = $("#wut_settings_form").find(".wut_tbl_measurement").find('#wut_tb_meas_' + cid)[0];
			console.log(table);

			$.ajax({
				url: wut_admin.admin_url,
				type: "POST",
				dataType: "json",
				data: {
					cid: cid,
					action: 'wut_add_unit_level_raw'
				},
				beforeSend() {
					$(".wut-units-container").block({ message: null, overlayCSS: { background: "#c3c3c3", opacity: 0.6 } });
				},
				success: function (response) {
					if (response.success) {
						if (response.data.html) {
							var newRow = table.insertRow();
							newRow.innerHTML = response.data.html;
						}
					} else {
						$(".wut-units-container").closest('.wut-wrap').find('.notice.wut-notice p.wut-msg').html(response.data.msg);
						$(".wut-units-container").closest('.wut-wrap').find('.notice.wut-notice').removeClass('notice-success').addClass('notice-error').show();
					}
				},
				error: function (error) {
					console.log(error);
				},
				complete: function () {
					$(".wut-units-container").unblock();
				},
			});

		});

		// Function to add a new raw in main table.
		$(document).on('click', '#wut_btn_add_sub', function (e) {
			e.preventDefault();

			let button = $(this);
			var currentIndex = parseInt($("#wut_settings_form").find('#wut_cid').val());
			let newIndex = currentIndex + 1;

			$.ajax({
				url: wut_admin.admin_url,
				type: "POST",
				dataType: "json",
				data: {
					ind: currentIndex,
					action: 'wut_add_sub_block'
				},
				beforeSend() {
					$("#wut_settings_form").block({ message: null, overlayCSS: { background: "#c3c3c3", opacity: 0.6 } });
				},
				success(response) {
					if (response.success && response.data.html) {
						let tableBody = $("#wut_settings_form").find('#wut_tbl_main').find('tbody#wut_tb_main');
						if (tableBody.length) {
							tableBody.append(response.data.html);
						}

						// Initialize Select2 if required
						if ($(".wut_product_cats").length) {
							$(".wut_product_cats").select2({
								width: "100%",
								placeholder: "Select product or categories..",
								allowClear: false
							});
						}

						// Update button data attribute
						button.attr('data-ind', newIndex);
						$("#wut_settings_form").find('#wut_cid').val(newIndex);
					} else {
						let notice = $("#wut_settings_form").closest('.wut-wrap').find('.notice.wut-notice');
						notice.find('p.wut-msg').html(response.data.msg);
						notice.removeClass('notice-success').addClass('notice-error').show();
					}
				},
				error: function (error) {
					console.log(error);
				},
				complete() {
					$("#wut_settings_form").unblock();
				},
			});
		});


		// Function to remove a row
		$(document).on('click', '.wut_btn_remove_unt_level', function (e) {
			e.preventDefault();
			$(this).closest('tr').remove();
		});


		$(document).on('click', '#wut_save_settings', function (e) {
			e.preventDefault();
			var save = true;
			var form = $(this);
			var formdata = new FormData($('#wut_settings_form')[0]);
			formdata.append('action', 'wut_save_global_settings');

			if (save) {
				$.ajax({
					url: wut_admin.admin_url,
					type: "POST",
					dataType: "json",
					data: formdata,
					processData: false,
					contentType: false,
					beforeSend() {
						$("#wut_settings_form").block({ message: null, overlayCSS: { background: "#c3c3c3", opacity: 0.6 } });
					},
					success: function (response) {
						if (response.success) {
							$(form).closest('.wut-wrap').find('.notice.wut-notice p.wut-msg').html(response.data.msg);
							$(form).closest('.wut-wrap').find('.notice.wut-notice').removeClass('notice-error').addClass('notice-success').show();

							setTimeout(() => {
								location.reload(true);
							}, 1000);
						} else {
							$(form).closest('.wut-wrap').find('.notice.wut-notice p.wut-msg').html(response.data.msg);
							$(form).closest('.wut-wrap').find('.notice.wut-notice').removeClass('notice-success').addClass('notice-error').show();
						}
					},
					error: function (error) {
						console.log(error);
					},
					complete: function () {
						$("#wut_settings_form").unblock();
					},
				});
			}
		});



		/* only for screenshot */
		// if ($(".wut_meas_unit").length > 0) {
		// 	$(".wut_meas_unit").select2({
		// 		width: '100%',
		// 		placeholder: "Select...",
		// 		allowClear: false
		// 	});
		// }

		// if ($("#wut_units_display_mode").length > 0) {
		// 	$("#wut_units_display_mode").select2({
		// 		width: 'auto',
		// 		placeholder: "Select...",
		// 		allowClear: false
		// 	});
		// }

		// if ($("#wut_def_selected_unit").length > 0) {
		// 	$("#wut_def_selected_unit").select2({
		// 		width: 'auto',
		// 		placeholder: "Select...",
		// 		allowClear: false
		// 	});
		// }

	});

})(jQuery);
