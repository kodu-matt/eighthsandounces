/* error show in login and registration flow */

jQuery(document).ready(function($) {
    // Only run on WooCommerce account pages
    if ($('body').hasClass('woocommerce-account')) {

        var $firstWrapper = $('.woocommerce-notices-wrapper').eq(0);
        var $secondWrapper = $('.woocommerce-notices-wrapper').eq(1);

        // Function to move notices and remove the first wrapper
        function moveNotices() {
            if ($firstWrapper.length && $firstWrapper.children().length > 0) {
                // Move all children to the second wrapper
                $firstWrapper.children().appendTo($secondWrapper);
                // Remove the first wrapper entirely
                $firstWrapper.remove();
            }
        }

        // Initial move on page load
        moveNotices();

        // Watch for dynamic changes (AJAX updates)
        var observer = new MutationObserver(function(mutationsList, observer) {
            moveNotices();
        });

        if ($firstWrapper.length) {
            observer.observe($firstWrapper[0], { childList: true, subtree: true });
        }
    }
});
jQuery(function($){
  // possible target selectors (tries multiple common ones)
  var $wrapper = $('.notice-wrapper, .woocommerce-notices-wrapper, .woocommerce-message-wrapper').first();

  // if none found, attempt to create a wrapper near main content as fallback
  if (!$wrapper.length) {
    var $target = $('main, .entry-content, .page, .content').first();
    if ($target.length) {
      $wrapper = $('<div class="woocommerce-notices-wrapper"></div>').prependTo($target);
      // console.info('Created fallback .woocommerce-notices-wrapper'); // Removed logging
    } else {
      // console.warn('No existing wrapper found and no fallback target. Exiting.'); // Removed logging
      return;
    }
  }

  // core routine: keep one message, move it into wrapper, remove others
  function dedupeAndMove(){
    var $msgs = $('.woocommerce-message-custom');
    if (!$msgs.length) {
      // nothing to do
      return;
    }

    // prefer a message already marked as moved, otherwise pick the first
    var $msg = $msgs.filter('[data-moved="1"]').first();
    if (!$msg.length) $msg = $msgs.first();

    // mark it so we don't accidentally remove the one we want later
    $msg.attr('data-moved','1');

    // move the chosen message into wrapper (prepend so it shows at top)
    if (!$wrapper.is($msg.parent())) {
      $wrapper.prepend($msg);
      // console.info('Moved message into wrapper'); // Removed logging
    }

    // remove any other duplicates from page and inside wrapper
    $msgs.not($msg).remove();
    $wrapper.find('.woocommerce-message-custom').not($msg).remove();
  }

  // run once after small delay (some themes render messages slightly after DOM ready)
  setTimeout(dedupeAndMove, 120);

  // also run on common WooCommerce events + generic ajax complete
  $(document).ajaxComplete(function(){ setTimeout(dedupeAndMove, 60); });
  $(document.body).on('wc_fragments_refreshed updated_checkout updated_wc_div', function(){ setTimeout(dedupeAndMove, 60); });

  // MutationObserver to catch messages inserted dynamically
  var observer = new MutationObserver(function(muts){
    var found = false;
    for (var m of muts) {
      for (var n of m.addedNodes) {
        if (!(n instanceof HTMLElement)) continue;
        if (n.matches('.woocommerce-message-custom') || (n.querySelector && n.querySelector('.woocommerce-message-custom'))) {
          found = true;
          break;
        }
      }
      if (found) break;
    }
    if (found) {
      // small debounce
      clearTimeout(window.__moveMsgTO);
      window.__moveMsgTO = setTimeout(dedupeAndMove, 40);
    }
  });
  observer.observe(document.body, { childList: true, subtree: true });

  // optional: disconnect observer after X seconds to save resources
  setTimeout(function(){ observer.disconnect(); }, 2 * 60 * 1000);
});

document.addEventListener('DOMContentLoaded', function() {
   
    var swiper = new Swiper('.sale-products-swiper', {
        slidesPerView: 1,
        spaceBetween: 10,
      
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 2,
                spaceBetween: 40,
            },
        }
    });
    var swiper = new Swiper('.recentview-container-home', {
        slidesPerView: 1,
        spaceBetween: 10,
      
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 2,
                spaceBetween: 40,
            },
        }
    });
    new Swiper('.recentview-container', {
        slidesPerView: 1,
        spaceBetween: 10,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            type: 'fraction',
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 2,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 2,
                spaceBetween: 40,
            },
        },
    });

    new Swiper('.sponsored-products-grid .swiper-container', { 
        slidesPerView: 1,
        spaceBetween: 16,
        pagination: {
            el: '.swiper-pagination',
            type: 'fraction',
        },
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30
            },
            1024: {
                slidesPerView: 5,
                spaceBetween: 16
            },
            1260: {
                slidesPerView: 7,
                spaceBetween: 16
            }
        }
    });
    
    var swiper = new Swiper('.logo-img-slider', {
        slidesPerView: 2,
        spaceBetween: 10,
        loop: true,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        breakpoints: {
            640: {
                slidesPerView: 3,
                spaceBetween: 20, 
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30
            },
            1024: {
                slidesPerView: 5,
                spaceBetween: 40
            }
        }
    });
    new Swiper('.home-slider', {
        slidesPerView: 1,
        spaceBetween: 24,
        loop: true,
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        pagination: {
            el: '.swiper-pagination',
            type: 'fraction',
        },
    });
});
jQuery(document).ready(function($) {
 
    $('.tab-links a').on('click', function(e) {
        e.preventDefault();
        var currentAttrValue = $(this).attr('href');
        $('.products-container' + currentAttrValue).css('display', 'flex').siblings('.tab-content').hide();
        $(this).parent('li').addClass('active').siblings().removeClass('active');
    });
    // Set first tab as active and show its content
    $('.tab-links li:first-child').addClass('active');
    $('#latest').css('display', 'flex').siblings('.tab-content').hide();
    $('.bru-coulmn').matchHeight();
    $('.cat-list ul li').matchHeight();
    $('.custom-products .product-description').matchHeight();
    matchHeightEle( $('.coulmn-grp') );
    $('.single-grid-1').matchHeight();
    $('.single-grid-2').matchHeight();
    $('.trending-video-description').matchHeight();
    $('.bru-coulmn .coulmn-grp').matchHeight();
    $('.recommendations_product_img').matchHeight();
    $('.shop-product-img').matchHeight();
    $('.city-product-img').matchHeight();
    $('.custom-products .product-title').matchHeight();
    $('.custom-products .product-description').matchHeight();
    $('.packset-heading').matchHeight();
    $('.packset-paragraph').matchHeight();
    $('.product-details').matchHeight();
    $('.tab-content .match-height').matchHeight();
    $('.sponsored-products-grid .woocommerce-loop-product__title').matchHeight();
    $('.product-item h2').matchHeight();
    $('.recentview-container-home img').matchHeight();
    $('.faq-description').matchHeight();
    $('.seed-match-height').matchHeight();
    $('.recent-container img').matchHeight();
    matchHeightEle( $('.shop-now-section .wp-block-group .blog-title-height') );
    matchHeightEle( $('.blog-dec-height') );
    matchHeightEle( $('.shop-blog-dec p') );
    matchHeightEle( $('.title-section') );
    matchHeightEle( $('.shop-blog-title') );
    matchHeightEle( $('.green-dec-height') );
	matchHeightEle( $('.product-card h3') );
	matchHeightEle( $('.blog-title') );
	matchHeightEle( $('.recent-container h2') );
	matchHeightEle( $('.cat-post-title') );
    $('.rating-wrap').matchHeight();
    $('.cat-match-height').matchHeight();
});
function matchHeightEle(ele){
    var maxHeight = 0;
    ele.each(function() {
        var headlineHeight = jQuery(this).outerHeight();
        if (headlineHeight > maxHeight) {
            maxHeight = headlineHeight;
        }
    });
    
    ele.each(function() {
        jQuery(this).css('height', maxHeight + 'px');
    });
}
jQuery(document).ready(function($) {
    $('.everygrow-section ').each(function() {
        $(this).find('.column-grp').matchHeight();
    });
});
jQuery(document).on('click', function(event) { 
    if (jQuery(event.target).parent('.specifications-details').length > 0) {
        setTimeout(function(){
            jQuery('.single-grid-1').matchHeight();
            jQuery('.single-grid-2').matchHeight();
        }, 100);
    }
});
document.addEventListener('DOMContentLoaded', function () {
    var swiper = new Swiper('.recent-container', {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 10,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            type: 'fraction',
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 6,
                spaceBetween: 16,
            },
        }
    });
});
document.addEventListener('DOMContentLoaded', function () {
    var swiper = new Swiper('.city-product-wrap', {
        loop: true,
        slidesPerView: 1,
        spaceBetween: 10,
        pagination: {
            el: '.swiper-pagination',
            clickable: true,
            type: 'fraction',
        },
        navigation: {
            nextEl: '.swiper-button-next',
            prevEl: '.swiper-button-prev',
        },
        breakpoints: {
            640: {
                slidesPerView: 2,
                spaceBetween: 20,
            },
            768: {
                slidesPerView: 3,
                spaceBetween: 30,
            },
            1024: {
                slidesPerView: 12,
                spaceBetween: 16,
            },
        }
    });
});
document.addEventListener('DOMContentLoaded', function() {
    function updateChart(chartId, percent, subText) {
        const centerTextPlugin = {
            id: 'centerText',
            beforeDraw: function(chart) {
                const width = chart.width;
                const height = chart.height;
                const ctx = chart.ctx;
                ctx.restore();
                
                ctx.font = "20px sans-serif"; // Customize font via CSS
                ctx.textBaseline = "middle";
                const text = percent + "%";
                const textX = Math.round((width - ctx.measureText(text).width) / 2);
                const textY = height / 2 - 10;
                ctx.fillText(text, textX, textY);

                ctx.font = "18px sans-serif"; // Customize font via CSS
                const subTextX = Math.round((width - ctx.measureText(subText).width) / 2);
                const subTextY = height / 2 + 22 / 2;
                ctx.fillText(subText, subTextX, subTextY + 26 / 2);
                ctx.save();
            }
        };
        const ctx = document.getElementById(chartId).getContext('2d');
        const data = {
            datasets: [{
                data: [percent, 100 - percent],
                backgroundColor: ['#3da81c', '#e6e6e6']
            }]
        };
        const options = {
            cutout: '80%',
            responsive: true,
            plugins: {
                tooltip: {
                    enabled: false
                },
                legend: {
                    display: false
                }
            }
        };
        new Chart(ctx, {
            type: 'doughnut',
            data: data,
            options: options,
            plugins: [centerTextPlugin]
        });
    }

	// THC Chart
	const thcSection = document.querySelector('.progress-bar-section[data-thc-progress-bar]');
	if (thcSection) {
		const thcPercent = parseInt(thcSection.getAttribute('data-thc-progress-bar'), 10) || 0;
		let thcLabel;

		if (thcPercent <= 10) {
			thcLabel = 'Low';
		} else if (thcPercent <= 20) {
			thcLabel = 'Medium';
		} else if (thcPercent <= 30) {
			thcLabel = 'High';
		} else {
			thcLabel = 'Unknown'; // Fallback for invalid percentages
		}

		updateChart('thcChart', thcPercent, thcLabel);
	}
    // CBD Chart
    const cbdSection = document.querySelector('.progress-bar-section[data-cbd-progress-bar]');
    if (cbdSection) {
        const cbdPercent = parseInt(cbdSection.getAttribute('data-cbd-progress-bar'), 10) || 0;
        updateChart('cbdChart', cbdPercent, 'Very Low');
    }
});
  document.addEventListener('DOMContentLoaded', function() {
    var countdownElements = document.querySelectorAll('#countdown');
    
    countdownElements.forEach(function(countdown) {
        var endDate = new Date(countdown.getAttribute('data-end-date')).getTime();
        
        var updateCountdown = setInterval(function() {
            var now = new Date().getTime();
            var distance = endDate - now;

            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            countdown.querySelector('#tiles').innerHTML = 
                '<div class="countdown-segment days"><span>' + days + '</span> days</div>' +
                '<div class="countdown-segment hours"><span>' + hours + '</span> hours</div>' +
                '<div class="countdown-segment minutes"><span>' + minutes + '</span> minutes</div>' +
                '<div class="countdown-segment seconds"><span>' + seconds + '</span> seconds</div>';

            if (distance < 0) {
                clearInterval(updateCountdown);
                countdown.querySelector('#tiles').innerHTML = 'SALE ENDED';
            }
        }, 1000);
    });
});
document.addEventListener('DOMContentLoaded', function() {
    var shippingMethods = document.querySelectorAll('#shipping-method li');

    // Add click event listeners to each shipping method item
    shippingMethods.forEach(function(item) {
        item.addEventListener('click', function() {
            var shippingMethod = this.getAttribute('data-method');

            if (this.classList.contains('selected')) {
                // If the clicked item is already selected, remove the class and clear session storage
                this.classList.remove('selected');
                sessionStorage.removeItem('selected_shipping_method');
            } else {
                // If the clicked item is not selected, add the class and save to session storage
                sessionStorage.setItem('selected_shipping_method', shippingMethod);

                // Highlight the selected method and remove the class from others
                shippingMethods.forEach(function(el) {
                    el.classList.remove('selected');
                });
                this.classList.add('selected');
            }
        });
    });

    // Set the initial state based on session storage
    var selectedMethod = sessionStorage.getItem('selected_shipping_method');
    if (selectedMethod) {
        shippingMethods.forEach(function(item) {
            if (item.getAttribute('data-method') === selectedMethod) {
                item.classList.add('selected');
            }
        });
    }
});
document.addEventListener('DOMContentLoaded', function() {
    var addToCartForm = document.querySelector('form.cart');

    if (addToCartForm) {
        addToCartForm.addEventListener('submit', function(event) {
            var selectedMethod = sessionStorage.getItem('selected_shipping_method');
            if (selectedMethod) {
                var input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'shipping_method';
                input.value = selectedMethod;
                addToCartForm.appendChild(input);
            }
        });
    }
});
document.addEventListener("DOMContentLoaded", function() {
    // Create the toggle button
    var toggleButton = document.createElement("button");
    toggleButton.id = "toggle-search";
    toggleButton.innerText = "Find Location Near Me";
    
    // Insert the button before the search field container
    var searchFieldContainer = document.querySelector("#wpsl-search-wrap"); // Replace with the actual class or ID
    if (searchFieldContainer) {
        searchFieldContainer.parentNode.insertBefore(toggleButton, searchFieldContainer);

        // Hide the search field container by default on mobile
        if (window.innerWidth <= 768) {
            searchFieldContainer.style.display = "none";
        }

        // Add click event listener to the button
        toggleButton.addEventListener("click", function() {
            if (window.innerWidth <= 768) { // Adjust width as needed for your definition of mobile
                if (searchFieldContainer.style.display === "none" || searchFieldContainer.style.display === "") {
                    searchFieldContainer.style.display = "block";
                } else {
                    searchFieldContainer.style.display = "none";
                }
            }
        });
    }
});

jQuery(document).ready(function($) {
    $('.button-view-strains').on('click', function(e) {
        e.preventDefault();

        var postId = $(this).data('post-id');

        $.ajax({
            url: custom_ajax_obj.ajax_url,
            type: 'POST',
            data: {
                action: 'load_single_template_content',
                post_id: postId,
                nonce: custom_ajax_obj.nonce
            },
            success: function(response) {
                if (response.success) {
                    $('.content-container').html(response.data);
                } else {
                    alert('Failed to load content.');
                }
            },
            error: function() {
                alert('An error occurred.');
            }
        });
    });
	
	$("#cat_name").select2({
			closeOnSelect : false,
			placeholder : "Select Category",
			allowClear: true,
			tags: true 
		});
});

document.addEventListener('DOMContentLoaded', function() {
    const tabs = document.querySelectorAll('.tab_list');
    const contents = document.querySelectorAll('.tab_content');

    tabs.forEach(tab => {
        tab.addEventListener('click', function() {
            const id = this.getAttribute('data-id');

            tabs.forEach(t => t.classList.remove('active'));
            contents.forEach(c => c.classList.remove('active'));

            this.classList.add('active');
            document.getElementById('tab-content-' + id).classList.add('active');
        });
    });

    // Activate the first tab and its content by default
    if (tabs.length > 0) {
        tabs[0].classList.add('active');
        document.getElementById('tab-content-0').classList.add('active');
    }
});

document.addEventListener('DOMContentLoaded', function() {
    function setupSeeMore(sectionClass, buttonClass) {
        const sections = document.querySelectorAll(`.${sectionClass} .taxonomy-img-heading-section`);
        const button = document.querySelector(`.${buttonClass}`);

        if (button) {
            button.addEventListener('click', function() {
                sections.forEach((section, index) => {
                    if (index >= 4) {
                        section.classList.toggle('hidden');
                    }
                });

                // Change button text based on visibility
                if (button.textContent === 'See More') {
                    button.textContent = 'See Less';
                } else {
                    button.textContent = 'See More';
                }
            });
        }
    }
    // Initialize toggle for each section
    setupSeeMore('side-effects-section', 'side-effects-more');
    setupSeeMore('may-relive-section', 'may-relive-more');
    setupSeeMore('flavors-section', 'flavors-more');
    setupSeeMore('aromas-section', 'aromas-more');
});
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.sale-countdown').forEach(function (element) {
        var endDate = new Date(element.dataset.endDate).getTime();
        var countdown = setInterval(function () {
            var now = new Date().getTime();
            var distance = endDate - now;
            
            var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);
            
            element.innerHTML = days + "d " + hours + "h " + minutes + "m " + seconds + "s ";
            
            if (distance < 0) {
                clearInterval(countdown);
                element.innerHTML = "Sale ended";
            }
        }, 1000);
    });
});
jQuery(document).ready(function($) {
    $(".variations select").each(function(selectIndex, selectElement) {
        var select = $(selectElement);

        var value = select.find("option:selected").val();
        $(".list-product li[data-value='" + value + "']").addClass("checked");

        select.parent().on("click", ".list-product li", function() {
            var selectedValue,
                currentlyChecked = $(this).hasClass("checked");
			$(".list-product li").removeClass("checked");
            $(this).siblings().removeClass("checked");

            if (!currentlyChecked) {
                $(this).addClass("checked");
                selectedValue = $(this).data("value");
            } else {
                selectedValue = "";
            }

            select.val(selectedValue).trigger("change");
            select.find("option").prop("selected", false).filter("[value='" + selectedValue + "']").prop("selected", true);

            $(document).trigger("woocommerce_variation_select_change");
            $(document).trigger("woocommerce_update_variation_values");
            $("form.variations_form").trigger("check_variations");
        });

        $(".reset_variations").on("mouseup", function() {
            $(".list-product li.checked").removeClass("checked");
            select.val("").trigger("change");

            $("form.variations_form").trigger("reset_data");
        });
    });

    var $cart_button = $('.button-form .express-checkout-button');

    if ($cart_button.length > 0) {
        // Disable the button initially
        $cart_button.addClass('disabled');

        // Handle variable product scenarios
        $('form.variations_form').on('woocommerce_variation_select_change', function() {
            $cart_button.addClass('disabled');
        });

        $('form.variations_form').on('found_variation', function(event, variation) {
            $cart_button.removeClass('disabled');
        });

        $('form.variations_form').on('reset_data', function() {
            $cart_button.addClass('disabled');
        });

        // Simple product logic
        if (!$('form.variations_form').length) {
            // Check if it is a simple product
            $cart_button.removeClass('disabled');

            // Ensure the button works without variations
            $cart_button.on('click', function(e) {
                e.preventDefault();
                // Directly submit the form
                $('form.cart').submit();
            });
        }
    }
});
jQuery(document).ready(function($) {
    $('.list-product .location-variation').on('click', function() {
        var selectedValue = $(this).data('value');
         
        $('.variation-input').val(selectedValue); 
    });
});

jQuery(document).ready(function ($) {
	$('ul.filtered-items').hide()
	$(document).on('click', '.remove_filter', function (e) {
		e.preventDefault();

		var id = $(this).data('id');
		var name = $(this).data('name');

		$('input[value="' + id + '"]').prop('checked', false);
		
		$('#apply-filters').trigger('click');
	});

	$(document).on('click', '.clear_all_filter', function (e) {
		e.preventDefault();

		// Clear all select elements in the filter section
		$(this).closest('.product-filter').find('input[type="checkbox"]').each(function () {
			$(this).prop('checked', true);
		});

		// Uncheck all checkboxes in the filter section
		$(this).closest('.product-filter').find('input[type="checkbox"]').prop('checked', false);

		// Trigger the filter application
		$('#apply-filters').trigger('click');
	});

	$( document ).on('change', '.product-filter input[type="checkbox"]', function(){
		$( this ).closest(' .product-filter ').find( '#apply-filters' ).trigger('click');
	});
	
    $('#apply-filters').on('click', function (e) {
        e.preventDefault();
        
		// Collecting values of checked checkboxes
		var cat_name = $('input[name="cat_name[]"]:checked').map(function () {
			return this.value;
		}).get();

		var cat_type = $('input[name="cat_type[]"]:checked').map(function () {
			return this.value;
		}).get();

		var typical_effects = $('input[name="typical_effects[]"]:checked').map(function () {
			return this.value;
		}).get();

		var common_usage = $('input[name="common_usage[]"]:checked').map(function () {
			return this.value;
		}).get();
		
		var thc = $('input[name="thc[]"]:checked').map(function () {
			return this.value;
		}).get();

		var html = '';

		// Display selected filters dynamically
		if (cat_name.length > 0) {
			cat_name.forEach(function (name) {
				
				var title = $('label[for="' + name + '"]').text() || name; // Assuming labels have the `for` attribute matching checkbox IDs
				html += '<li class="item">';
				html += '<span class="filter-label">Category: </span>';
				html += '<span class="filter-value">' + $('input[value="'+ name +'"]').parent('label').attr('for') + '</span>';
				html += '<button class="action remove_filter" data-id="' + name + '" data-name="cat_name" title="Remove Category ' + title + '"><span>x</span></button>';
				html += '</li>';
			});
		}

		if (cat_type.length > 0) {
			cat_type.forEach(function (type) {
				var title = $('label[for="' + type + '"]').text() || type;
				html += '<li class="item">';
				html += '<span class="filter-label">Category Type: </span>';
				html += '<span class="filter-value">' + $('input[value="'+ type +'"]').parent('label').attr('for') + '</span>';
				html += '<button class="action remove_filter" data-id="' + type + '" data-name="cat_type" title="Remove Category Type ' + title + '"><span>x</span></button>';
				html += '</li>';
			});
		}

		if (typical_effects.length > 0) {
			typical_effects.forEach(function (effect) {
				var title = $('label[for="' + effect + '"]').text() || effect;
				html += '<li class="item">';
				html += '<span class="filter-label">Typical Effects: </span>';
				html += '<span class="filter-value">' + $('input[value="'+ effect +'"]').parent('label').attr('for') + '</span>';
				html += '<button class="action remove_filter" data-id="' + effect + '" data-name="typical_effects" title="Remove Typical Effect ' + title + '"><span>x</span></button>';
				html += '</li>';
			});
		}

		if (common_usage.length > 0) {
			common_usage.forEach(function (usage) {
				var title = $('label[for="' + usage + '"]').text() || usage;
				html += '<li class="item">';
				html += '<span class="filter-label">Common Usage: </span>';
				html += '<span class="filter-value">' + $('input[value="'+ usage +'"]').parent('label').attr('for') + '</span>';
				html += '<button class="action remove_filter" data-id="' + usage + '" data-name="common_usage" title="Remove Common Usage ' + title + '"><span>x</span></button>';
				html += '</li>';
			});
		}
		
		if (thc.length > 0) {
			thc.forEach(function (th) {
				var title = $('label[for="' + th + '"]').text() || th;
				html += '<li class="item">';
				html += '<span class="filter-label">Common Usage: </span>';
				html += '<span class="filter-value">' + $('input[value="'+ th +'"]').parent('label').attr('for') + '</span>';
				html += '<button class="action remove_filter" data-id="' + th + '" data-name="common_usage" title="Remove Common Usage ' + title + '"><span>x</span></button>';
				html += '</li>';
			});
		}

		if (html !== '') {
			html += '<button class="clear_all_filter" name="clear_filter" title="Clear All">Clear All</button>';
		}

		$('ul.filtered-items').html(html).show();

		// Make AJAX request
		$.ajax({
			url: custom_ajax_obj.ajax_url, // Use localized AJAX URL
			type: 'POST',
			data: {
				action: 'filter_products',
				nonce: custom_ajax_obj.nonce, // Use localized nonce
				cat_name: cat_name,
				cat_type: cat_type,
				typical_effects: typical_effects,
				common_usage: common_usage,
				thc: thc,
				current_cat: $('input[name="current_cat"]').val()
			},
			success: function (response) {
				$('ul.products-block-post-template').html(response.html);
			},
			error: function () {
				alert('Error applying filters.');
			}
		});
    });
});
document.addEventListener('DOMContentLoaded', function() {
    const currentUrl = window.location.href;
    const menuLinks = document.querySelectorAll('.wp-block-navigation__container a');

    menuLinks.forEach(link => {
        if (link.href === currentUrl) {
            link.classList.add('active'); // Add active class to current page link
        }
    });
});
jQuery(document).ready(function ($) {
    $('.apply-btn').on('click', function (e) {
        e.preventDefault();

        // Check if location is enabled
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function () {
                document.cookie = "location_enabled=true; path=/";

                // AJAX request
                $.post(
                    custom_ajax_obj.ajax_url, // Use localized ajax_url
                    {
                        action: 'check_cart_and_apply_discount',
                        nonce: custom_ajax_obj.nonce // Pass the nonce for verification
                    },
                    function (response) {
                        if (response.success) {
                            window.location.href = '/cart'; // Redirect to cart page
                        } else {
                            alert(response.data.message);
                        }
                    }
                );
            }, function () {
                alert('Location permission is required to apply the discount.');
            });
        } else {
            alert('Your browser does not support location services.');
        }
    });
});

window.dataLayer = window.dataLayer || [];
window.eventTracker = window.eventTracker || {};
if (window.dataLayer && Array.isArray(window.dataLayer)) {
  
  for (let i = 0; i < window.dataLayer.length; i++) {
      
    if (window.dataLayer[i].event === "gtm.linkClick" || window.dataLayer[i].event === "begin_checkout") {

      window.dataLayer[i].ecommerce.bulk_discount = parseFloat(window.cartData?.bulk_discount || 0);
      window.dataLayer[i].ecommerce.coupons = window.cartData?.coupons || [];
      window.dataLayer[i].ecommerce.subtotal_price = parseFloat(window.cartData?.subtotal_price || 0);
      
      if (
        Array.isArray(window.cartData?.items) && 
        Array.isArray(window.dataLayer?.[i]?.ecommerce?.items)
      ) {
        let lineTotal = 0;
        let lineSubTotal = 0;
    
        const itemCount = Math.min(window.cartData.items.length, window.dataLayer[i].ecommerce.items.length);
    
        for (let it = 0; it < itemCount; it++) {
            const item = window.cartData.items[it];
            const dataLayerItem = window.dataLayer[i].ecommerce.items[it];
    
            // Check if item exists before processing
            if (item) {
                lineTotal += parseFloat(item.line_total || 0);
                lineSubTotal += parseFloat(item.line_subtotal || 0);
            }
        }
        window.dataLayer[i].ecommerce.line_total = lineTotal;
        window.dataLayer[i].ecommerce.line_subtotal = lineSubTotal;
      }
    }

  }

}

window.dataLayer.push({
  ecommerce: window.dataLayer.find((data) => data.event === "gtm.linkClick" || data.event === "begin_checkout")?.ecommerce
});
															   
if( jQuery('.woocommerce-checkout').length > 0 ){
	if( jQuery('.wc-block-checkout__form input[id="email"]').val() != '' ){
		
															   
// 		jQuery(document).on('blur', '.wc-block-checkout__form input[id="email"]', function () {
// 			const email = $(this).val(); // Get the value of the email field

// 			if (email !== '') {
// 				// Send an AJAX request to check if the email qualifies as "new"
// 				fetch('/check-new-customer', {
// 					method: 'POST',
// 					headers: { 'Content-Type': 'application/json' },
// 					body: JSON.stringify({ email: email }),
// 				}).then((response) => {
// 					if (!response.ok) {
// 						throw new Error('Network response was not ok');
// 					}
// 					return response.json();
// 				})
// 					.then((data) => {
// 					if (data.is_new_customer) {
// 						alert('You qualify for a 15% discount as a new customer!');
// 					}
// 				})
// 					.catch((error) => {
// 					console.error('There was a problem with the fetch operation:', error);
// 				});
// 			}
// 		});
															   
// 		const email = jQuery('.wc-block-checkout__form input[id="email"]').val();

// 		// Send an AJAX request to check if the email qualifies as "new"
// 		fetch('/check-new-customer', {
// 			method: 'POST',
// 			headers: { 'Content-Type': 'application/json' },
// 			body: JSON.stringify({ email: email })
// 		})
// 		.then(response => response.json())
// 		.then(data => {
// 			if (data.is_new_customer) {
// 				alert('You qualify for a 15% discount as a new customer!');
// 			}
// 		});
	}
	
// 	document.querySelector('#email').addEventListener('blur', function () {
// 		const email = this.value;

// 		// Send an AJAX request to check if the email qualifies as "new"
// 		fetch('/check-new-customer', {
// 			method: 'POST',
// 			headers: { 'Content-Type': 'application/json' },
// 			body: JSON.stringify({ email: email })
// 		})
// 		.then(response => response.json())
// 		.then(data => {
// 			if (data.is_new_customer) {
// 				alert('You qualify for a 15% discount as a new customer!');
// 			}
// 		});
// 	});
}
window.addEventListener("load", function () {
    setTimeout(function () {
        let button = document.querySelector('.wc-block-components-panel__button');

        if (button) {
            // Set aria-expanded attribute to true
            button.setAttribute('aria-expanded', 'true');

            // Check if clicking is required to expand the section
            if (button.click) {
                button.click();
            }
        }
    }, 1000); // Delay to ensure the element is loaded
});

jQuery(function ($) {
    function checkCartItems() {
        if ($('.wc-block-cart-items__row').length > 0) {
            $('.wc-block-cart-items__row').each(function () {
                let val = $(this).find('.wc-block-components-product-price__value').text().trim();
                if (val === '') {
                    $('.wc-block-components-quantity-selector__button--plus').prop('disabled', true);
                }
            });
        } else {
            // If cart is empty, disable the "+" button
            $('.wc-block-components-quantity-selector__button--plus').prop('disabled', true);
        }
    }

    // Run check when the page loads
    checkCartItems();

    // Run check after cart updates (WooCommerce AJAX update event)
    $(document.body).on('wc-blocks-cart-items-updated', function () {
        checkCartItems();
    });
});
															   
jQuery(document).ready(function($) {
    $('.woocommerce-product-gallery__wrapper a').each(function() {
        var $anchor = $(this);

        if ($anchor.find('video').length > 0) {
            $anchor.addClass('has-video');
			$anchor.removeAttr('href');
        } else if ($anchor.find('iframe').length > 0) {
            $anchor.addClass('has-video');
			$anchor.removeAttr('href');
        } else if ($anchor.find('img').length > 0) {
            $anchor.addClass('has-image');
        }
    });
});

jQuery(document).ready(function($) {
    $(window).scroll(function() {
        if ($(this).scrollTop() > 300) {
            $('.back-to-top').fadeIn();
        } else {
            $('.back-to-top').fadeOut();
        }
    });

    $('.back-to-top').click(function(event) {
        event.preventDefault();
        $('html, body').animate({ scrollTop: 0 }, 500);
    });
});
	jQuery(document).ready(function($) {
		$(".single-product .flex-viewport .woocommerce-product-gallery__wrapper > p").remove();
	});
	
	document.addEventListener("DOMContentLoaded", function () {
  // Get current pathname (like /about-us/)
  let path = window.location.pathname;

  // Clean it up into a class-friendly format
  let slugClass = path.replace(/[^a-z0-9]/gi, '-').toLowerCase();

  // Fallback for homepage
  if (!slugClass || slugClass === '-') {
    slugClass = 'home-page';
  }

  // Prefix to avoid conflicts
  let uniqueClass = 'page-' + slugClass;

  // Add to body
  document.body.classList.add(uniqueClass);
});
