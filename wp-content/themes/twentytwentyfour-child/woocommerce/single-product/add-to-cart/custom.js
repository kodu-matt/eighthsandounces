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

        // Show/Hide Tabs
        $('.products-container' + currentAttrValue).css('display', 'flex').siblings('.tab-content').hide();
       
        // Change/remove current tab to active
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
        updateChart('thcChart', thcPercent, 'Very High');
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

        // Set initially checked class based on the default selected value
        var value = select.find("option:selected").val();
        $(".list-product li[data-value='" + value + "']").addClass("checked");

        // Handle click on list items
        select.parent().on("click", ".list-product li", function() {
            var selectedValue,
                currentlyChecked = $(this).hasClass("checked");

            $(this).siblings().removeClass("checked");

            if (!currentlyChecked) {
                $(this).addClass("checked");
                selectedValue = $(this).data("value");
            } else {
                selectedValue = "";
            }

            // Update the hidden select dropdown and trigger change event
            select.val(selectedValue).trigger("change");

            // Update selected option in dropdown
            select.find("option").prop("selected", false).filter("[value='" + selectedValue + "']").prop("selected", true);

            // Trigger WooCommerce's internal variation update events
            $(document).trigger("woocommerce_variation_select_change");
            $(document).trigger("woocommerce_update_variation_values");
            $("form.variations_form").trigger("check_variations");
        });

        // Clear checked state when reset link is clicked
        $(".reset_variations").on("mouseup", function() {
            $(".list-product li.checked").removeClass("checked");
            select.val("").trigger("change");

            // Reset WooCommerce variation form
            $("form.variations_form").trigger("reset_data");
        });
    });

    if( $('.button-form .express-checkout-button').length > 0 ){
        var $cart_button = $('.button-form .express-checkout-button');
        
        console.log('Express checkout button found:', $cart_button);

        $cart_button.addClass('disabled');

        $('form.variations_form').on('woocommerce_variation_select_change', function() {
            console.log('Variation selection changed. Disabling button.');
            $cart_button.addClass('disabled'); 
        });

        $('form.variations_form').on('found_variation', function(event, variation) {
            console.log('Valid variation selected. Enabling button.');
            $cart_button.removeClass('disabled'); 
        });

        $('form.variations_form').on('reset_data', function() {
            console.log('Variation reset. Disabling button.');
            $cart_button.addClass('disabled');
        });
    }
});

