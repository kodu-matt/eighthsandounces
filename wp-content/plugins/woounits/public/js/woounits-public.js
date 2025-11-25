(function ($) {
	'use strict';

	/**
	 * All of the code for your public-facing JavaScript source
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
	jQuery(document).ready(function ($) {
		var UNIT_FORM_ID = "#wut-unit-form ";
		if ($(UNIT_FORM_ID + "#wut_select_unit").length > 0) {
			wut_set_add_to_cart_button_disable();
		}

		// function wut_set_add_to_cart_button_enable() {
		// 	document.getElementsByClassName("single_add_to_cart_button")[0].removeAttribute("disabled");
		// }
		// function wut_set_add_to_cart_button_disable() {
		// 	document.getElementsByClassName("single_add_to_cart_button")[0].setAttribute("disabled", "disabled");
		// }
		function wut_set_add_to_cart_button_enable() {
			var $btn = $(".single_add_to_cart_button");
			if ($btn.length > 0) {
				$btn.removeAttr("disabled");
			}
		}

		function wut_set_add_to_cart_button_disable() {
			var $btn = $(".single_add_to_cart_button");
			if ($btn.length > 0) {
				$btn.attr("disabled", "disabled");
			}
		}

		if (wut_public.def_selected_unit == 'yes') {
			if ($("#wut_select_unit").is("select") && !$("#wut_select_unit").val()) {
				// If it's a dropdown and no value is selected
				$("#wut_select_unit").val("0").trigger("change");
				wut_set_add_to_cart_button_enable();
			} else if ($("#wut_select_unit input[type='radio']:checked").length === 0) {
				// If no radio button is selected
				$("#wut_select_unit input[type='radio'][value='0']")
					.prop("checked", true)
					.trigger("change");
				wut_set_add_to_cart_button_enable();
			}
		}

		// $("form.cart").on("change", "input.qty, #wut_select_unit", function () {
		// 	var $unitSelect = $(UNIT_FORM_ID + "#wut_select_unit"),
		// 		$qtyInput = $('input[name="quantity"]'),
		// 		selectedOption = $unitSelect.find(":selected"),
		// 		uh_id = parseInt(selectedOption.data("uh")),
		// 		level_value = parseFloat(selectedOption.data("level_value")),
		// 		level_price = parseFloat(selectedOption.data("level_price")),
		// 		lbl_value = selectedOption.text().trim(),
		// 		from_unit = selectedOption.data("from_unit"),
		// 		to_unit = selectedOption.data("to_unit");

		// 	if (selectedOption.val()) {
		// 		wut_set_add_to_cart_button_enable();
		// 		$("#wut_uh").val(uh_id);
		// 		$("#wut_lbl").val(lbl_value);
		// 		$("#wut_selected_rate").val(level_value);
		// 		$("#wut_selected_from").val(from_unit);
		// 		$("#wut_selected_to").val(to_unit);
		// 	} else {
		// 		$("#wut_uh, #wut_lbl").val("");
		// 		wut_set_add_to_cart_button_disable();
		// 	}

		// 	if (uh_id > 0 && level_value > 0) {
		// 		get_unit_price({
		// 			product_id: $("#wut_product_id").val(),
		// 			unit_id: uh_id,
		// 			unit_value: level_value,
		// 			unit_price: level_price,
		// 			lbl_value: lbl_value,
		// 			from_unit: from_unit,
		// 			to_unit: to_unit,
		// 			quantity: $qtyInput.val(),
		// 			action: "wut_get_unit_price",
		// 		});
		// 	}
		// });

		if ($(UNIT_FORM_ID + "#wut_select_unit").length > 0) {
			$("form.cart").on("change", "input.qty, #wut_select_unit, #wut_select_unit input[type='radio']", function () {
				var $selectedUnit = $("#wut_select_unit").find("option:selected, input[type='radio']:checked"),
					$qtyInput = $('input[name="quantity"]'),
					uh_id = parseInt($selectedUnit.data("uh")),
					level_value = parseFloat($selectedUnit.data("level_value")),
					level_price = parseFloat($selectedUnit.data("level_price")),
					lbl_value = ($selectedUnit.is("option") ? $selectedUnit.text() : $selectedUnit.parent().text()).trim(),
					from_unit = $selectedUnit.data("from_unit"),
					to_unit = $selectedUnit.data("to_unit");

				// console.log($selectedUnit);

				if ($selectedUnit.val()) {
					wut_set_add_to_cart_button_enable();
					$("#wut_uh").val(uh_id);
					$("#wut_lbl").val(lbl_value);
					$("#wut_selected_rate").val(level_value);
					$("#wut_selected_from").val(from_unit);
					$("#wut_selected_to").val(to_unit);
				} else {
					$("#wut_uh, #wut_lbl").val("");
					wut_set_add_to_cart_button_disable();
				}

				if (uh_id > 0 && level_value > 0) {
					get_unit_price({
						product_id: $("#wut_product_id").val(),
						unit_id: uh_id,
						unit_value: level_value,
						unit_price: level_price,
						lbl_value: lbl_value,
						from_unit: from_unit,
						to_unit: to_unit,
						quantity: $qtyInput.val(),
						action: "wut_get_unit_price",
					});
				}
			});
		}

		function get_unit_price(a_data) {
			var $qtyInput = $('input[name="quantity"]');
			$.ajax({
				url: wut_public.admin_url,
				type: "POST",
				dataType: "json",
				data: a_data,
				beforeSend: function () {
					$("form.cart").block({ message: null, overlayCSS: { background: "#c3c3c3", opacity: 0.6 } });
				},
				success: function (response) {
					if (response.success) {
						if (response.data.html) {
							$("#wut-price-box").show();
							$("#wut_price").html(response.data.html.wut_price);
							$("#wut_unt_price").val(response.data.html.unit_price);
							if (response.data.html.av_message != '') {

								jQuery('#wut_show_stock_status').show();
								jQuery("#wut_show_stock_status").html(response.data.html.av_message);

								var max = parseInt(response.data.html.availability);
								$qtyInput.attr("max", max);
							}
						}
					} else {
						var $notice = $("#wut-unit-form").closest(".wut-wrap").find(".notice.wut-notice");
						$notice.find("p.wut-msg").html(response.data.msg);
						$notice.removeClass("notice-success").addClass("notice-error").show();
					}
				},
				error: function (error) {
					console.log(error);
				},
				complete: function () {
					$("form.cart").unblock();
				},
			});
		}

	});

})(jQuery);