//#region VARS

let productId = '';

let userId = '';

let currentCart = [];

let currentProductImage = 0;

//#endregion



//#region WINDOWEVENTS

//#region ONLOAD
function parseCookiesToArray() {
    let cookiesStr = document.cookie+';';
    let cookies = {};

    do {
        let key = cookiesStr.substr(0, cookiesStr.indexOf('='));
        let value = cookiesStr.substr(cookiesStr.indexOf('=')+1, cookiesStr.indexOf(';')-cookiesStr.indexOf('=')-1);
        cookies[key] = value;
        cookiesStr = cookiesStr.replace(key+'='+value, '');
        cookiesStr = cookiesStr.replace(';', '');
        cookiesStr = cookiesStr.replace(' ', '');
	} while (cookiesStr.length>0);
	
	return cookies;
}

function updateCurrencyFromCookie(cookies) {
	let currency = '$';
	
    if (cookies['currency']) {
        currency = cookies['currency'];
        $('#currency').val(currency);
    }
	$('#currency').trigger('change');
}

window.onload = function() {
    console.log(document.readyState);
	console.timeEnd();
	
	let cookies = parseCookiesToArray();

    if (cookies['rmbct'])
    {
        currentCart = JSON.parse(cookies['rmbct']);
	}

	updateCurrencyFromCookie(cookies);

	refreshCartCost();

    if ($("#product-item").length>0) {
        currentProductImage = 0;

        productId = $("#product_code").html().replace('#', '');
    }
}
//#endregion


//#region RESIZE
let lastWindowSize = window.innerWidth;

$(window).resize(function () {
    if ($('.nav-item.active').length>0) {
        $('.close-button').css('top', -$('header').height());
    } 
    if (window.innerWidth<menuBreakpoint+20 && lastWindowSize>menuBreakpoint+20)
    {
        hideActiveNavItem();
    }
    if (window.innerWidth>menuBreakpoint-20 && lastWindowSize<menuBreakpoint-20)
    {
        hideActiveNavItem();
        $('.close-button').css('top', '100%');
    }
    lastWindowSize = window.innerWidth;
});
//#endregion

//#endregion



//#region ONSUBMIT

//#region SIGIN SIGNUP ERROR/SUCCESS-MESSAGE CHECK-FIELDS
function setErrorMessage(id, message, pos) {
	let errMsg = $(id+'-error-message');
    errMsg.html(message);
    errMsg.css({
        'opacity': '1'
    })
}

function setSuccessMessage(id, message, pos) {
	let errMsg = $(id+'-error-message');
    errMsg.html(message);
    errMsg.fadeIn(0);
    $(id+'-form input').css('border-color', '#e7e7e7');
    errMsg.css({
        'opacity': '1',
        'color': 'green'
    })
}

let errorMessage = '';
let errorMessagePos;
let password = '';

function resetVars() {
	errorMessage = '';
	errorMessagePos;
	password = '';
}

function checkLogin(item, id) {
	if (item['value']=='')
	{
		$(id+'-login').css('border-color', 'red');
		errorMessage = 'Fill all fields';
		if (errorMessagePos==undefined)
			errorMessagePos = $(id+'-login').position();
	}
}

function checkEmail(item, id) {
	let email = $(id+'-email');
	if (item['value']=='')
	{
		email.css('border-color', 'red');
		errorMessage = 'Fill all fields';
		if (errorMessagePos==undefined)
			errorMessagePos = email.position();
	} else {
		var pattern = /^(([a-zA-Z0-9]|[!#$%\*\/\?\|^\{\}`~&'\+=-_])+\.)*([a-zA-Z0-9]|[!#$%\*\/\?\|^\{\}`~&'\+=-_])+@([a-zA-Z0-9-]+\.)+[a-zA-Z0-9-]+$/;
		if (!pattern.test(item['value'])) 
		{
			errorMessage = 'Wrong email format';
			email.css('border-color', 'red');
		}
	}
}

function checkPass(item, id) {
	if (item['value']=='')
	{
		$(id+'-pass').css('border-color', 'red');
		errorMessage = 'Fill all fields';
		if (errorMessagePos==undefined)
			errorMessagePos = $(id+'-pass').position();
	} else
		password =  item['value'];
}

function checkConfirm(item, id) {
	let confirmPass = $(id+'-confirm-pass');
	if (formData[i]['value']=='')
	{
		confirmPass.css('border-color', 'red');
		errorMessage = 'Fill all fields';
		if (errorMessagePos==undefined)
			errorMessagePos = confirmPass.position();
	} 
	else if (formData[i]['value']!=password && errorMessage=='') 
	{
		errorMessage="Passwords don't match";
		if (errorMessagePos==undefined)
			errorMessagePos = $(id+'-pass').position();
		confirmPass.css('border-color', 'red');
		$(id+'-pass').css('border-color', 'red');
	}
}

function checkFormFields(formData, id) {
	resetVars();
	let errorMessageAndPos = [];
    let captchaResponse = grecaptcha.getResponse();
    if (id=='#reg')
        captchaResponse = grecaptcha.getResponse();
    for (let i = 0; i<formData.length;i++)
    {
		if (formData[i]['name']=='login') {
			checkLogin(formData[i], id);
			continue;
		}
        if (formData[i]['name']=='email') 
        {
			checkEmail(formData[i], id);
            continue;
        } 
        if (formData[i]['name']=='password')
        {
			checkPass(formData[i], id);
            continue;
        }
        if (formData[i]['name']=='conf-password')
        {
			checkConfirm(formData[i], id);
            continue;
        }

        if (id=='#reg' && captchaResponse.length == 0) {
            if (errorMessage=='')
            {
                errorMessage="Complete the captcha";
                errorMessagePos = $(id+'-recaptcha').position();
            }
        }
    }
    if (errorMessage!='')
    {
        setErrorMessage(id, errorMessage, errorMessagePos);
        return 0;
    }
    else
    {
        return 1;
    }
}


$("#reg-form").submit(function(e) {
	e.preventDefault();
	formData = $(this).serializeArray();
	if (!checkFormFields(formData, '#reg'))
		return;
	let form_data = $(this).serialize();
	$.ajax({
	type: "POST",
	url: "/dosignup",
	data: form_data,
	success: function(response) {
		if (response == 1) {
			setSuccessMessage('#reg', 
				'You have signed up! Going back to home page.',
				$('#reg-email').position());
			setTimeout(changeLocation, 5000, '/home');
		}
		if (response == 0) {
			setErrorMessage('#reg', 
				"Couldn't connect to database. Sorry for the temporary inconvenience <br> <a href='/home'>Go back to home page</a>",
				$('#reg-email').position());
		}
		if (response == 2){
			setErrorMessage('#reg', 
				"User with such email already exists",
				$('#reg-email').position());
			$('#reg-email').css('border-color', 'red');
		}
		if ($response == 3) {
			setErrorMessage('#reg', 
				"Invalid token. Request rejected",
				$('#reg-email').position());
		}
	} 
	});
});


$("#log-form").submit(function(e) {
	e.preventDefault();
	formData = $(this).serializeArray();
	if (!checkFormFields(formData, '#log'))
		return;
	let form_data = $(this).serialize();
	$.ajax({
	type: "POST",
	url: "/dosignin",
	data: form_data,
	success: function(response) {
		if (response == 1) {
			setSuccessMessage('#log', 
				'You have signed in! Going back to home page.',
				$('#log-email').position());
				setTimeout(changeLocation, 5000, '/home');
		}
		if (response == 0) {
			setErrorMessage('#log', 
				"Couldn't connect to database. Sorry for the temporary inconvenience <br> <a href='/home'>Go back to home page</a>",
				$('#log-email').position());
		}
		if (response == 2){
			setErrorMessage('#log', 
				"Wrong email or password",
				$('#log-email').position());
			$('#log-email').css('border-color', 'red');
			$('#log-pass').css('border-color', 'red');
		}
		if (response == 3) {
			setErrorMessage('#log', 
				"Invalid token. Request rejected",
				$('#log-login').position());
		}
	} 
	});
});

function checkReviewForm() {
	let error = $('.reviews-add-error');
	if ($('.reviews-add-rating-star.active').length==0) {
		error.html('Rate the product to continue');
		error.addClass('active-error');
		setTimeout(function() {
			error.removeClass('active-error')
		}, 3000);
		return false;
	}
	if ($('.reviews-add-text').val().length<4) {
		error.html('Review must contain at least 5 characters');
		error.addClass('active-error');
		setTimeout(function() {
			error.removeClass('active-error')
		}, 3000);
		return false;
	}
	return true;
}

$("#form-add-review").submit(function(e) {
	e.preventDefault();
	let error = $('.reviews-add-error');
	if (!checkReviewForm())
		return;
	$('#add-review-rating').val($('.reviews-add-rating-star.active').length);
	let form_data = $(this).serialize();
	form_data += '&item='+productId;
	$.ajax({
	type: "POST",
	url: "/add_review",
	data: form_data,
	success: function(response) {
		error.html('Review submitted');
		error.removeClass('active-error');
		error.addClass('active-success');
		setTimeout(function() {
			error.removeClass('active-success')
		}, 3000);
	} 
	});
})
//#endregion

//#endregion



//#region ONCLICK
let changeLocation = function (location) {
	history.back();
}


//#region NAV-ITEM, MENU-NAV-BUTTON, CLOSE-BUTTON
function hideActiveNavItem() {
    let currentActiveName = $('.nav-item.active').attr('id');

    $("."+currentActiveName).slideUp(200);

    $('#'+currentActiveName).removeClass('active');
}

$(".nav-item").click(function (e) {
    let name = $(this).attr('id');

    let newActive = $("."+name);

	hideActiveNavItem();
    if (newActive.css('display')=='none')
    {
        newActive.slideDown(200);
        $('#'+name).addClass('active');
    }
});

$('.content').click(function() {
    hideActiveNavItem();
})
//#endregion


$('.control').click(function (e) { 
    e.preventDefault();

    lastSlide = $('.control.active');
    if ($(this).attr('class').indexOf('active')>-1)
        return;
    lastSlide.removeClass('active');
    $(this).addClass('active');

    getShopHomeItems(lastSlide);

    customSelectChangeFields($('#order-'+$(this).attr('id').replace('control-', '')));
});
		

//#region SELECT-SELECTED, -OTHER
function customSelectChangeFields(clicked) {
	let selected = $('.select-selected span');
	
	let selectedCaption = selected.html();
	let selectedName = selected.attr('name');
	let selectedId = selected.attr('id');

	selected.html(clicked.html());
	selected.attr('name', clicked.attr('name'));
	selected.attr('id', clicked.attr('id'));

	clicked.attr('name', selectedName);
	clicked.attr('id', selectedId);
	clicked.html(selectedCaption);
	
	for (let i=0;i<$('.select-other span').length-1;i++) {
		for (let j=0;j<$('.select-other span').length-1-i;j++) {
			if (Number($('.select-other span:eq('+j+')').attr('id').replace('order-','')) >
				Number($('.select-other span:eq('+(j+1)+')').attr('id').replace('order-','')))
				{
					let selected = $('.select-other span:eq('+j+')');
					let selected2 = $('.select-other span:eq('+(j+1)+')');

					let selectedCaption = selected.html();
					let selectedName = selected.attr('name');
					let selectedId = selected.attr('id');
				
					selected.html(selected2.html());
					selected.attr('name', selected2.attr('name'));
					selected.attr('id', selected2.attr('id'));
				
					selected2.attr('name', selectedName);
					selected2.attr('id', selectedId);
					selected2.html(selectedCaption);
				}
		}
	}
}

$('.select-selected').click(function() {
	let other = $('.select-other');
	$(this).toggleClass('active');
	other.slideToggle(200);
})

$('.select-other span').click(function() {

    customSelectChangeFields($(this));

    $('.select-other').slideUp(200);

    $('.select-selected').removeClass('active');

    $("#"+$('.select-selected span').attr("name")).trigger('click');
});
//#endregion


//#region PRODUCT SLIDER CLICK

function productSliderAnimate() {
    let content = $("#product-content");
    content.animate({
        'margin-left': (-currentProductImage*100)+'%'
    })
}

$("#product-left").click(function () { 
	let slidesCount = $('.product-inner-main-slider-content-item').length;
    currentProductImage += currentProductImage == 0 ? slidesCount-1 : -1;
    productSliderAnimate();
});

$("#product-right").click(function () { 
	let slidesCount = $('.product-inner-main-slider-content-item').length;
    currentProductImage += currentProductImage == slidesCount-1 ? -slidesCount+1 : 1;
    productSliderAnimate();
});
//#endregion


//#region OPTION-TEXT, OPTION-BOX
$(".option-text").click(function (e) { 
    e.preventDefault();
    let option_box = $(this).parent();
	
	option_box.toggleClass('active');
});

$(".option").click(function (e) { 
    e.preventDefault();
    $(this).parent().parent().children('.option-text').html($(this).html());
    $(this).parent().parent().removeClass('active');
});
//#endregion


//#region COUNTER-CONTROLS
function counterControlsClick(control, change) {

    let counter = $(control).parent().parent().children('.counter');
	
	if (Number(counter.html())+change>0) {
		counter.html(Number(counter.html())+change);

		if ($(counter).attr('id')) {
			currentCart[Number($(counter).attr('id'))].qty += change;
			refreshCartCost();
		}
	}
}

$(".counter-controls-up").click(function (e) { 
    counterControlsClick(this, 1);
});

$(".counter-controls-down").click(function (e) { 
    counterControlsClick(this, -1);
});
//#endregion
 

//#region CART TO-CART COUNTER_EVENTS
function counterKeyDown (e, counter){
    let code = e.keyCode || e.which;
    return '123456789'.indexOf(String.fromCharCode(code))>-1 && $(counter).html().length<3 || code===8;
}

function counterKeyUp(counter) {
    if ($(counter).attr('id')){
        currentCart[$(counter).attr('id')].qty = $(counter).html();
        refreshCartCost();
    }
}

function counterFocusOut(counter) {
    if ($(counter).html()=="")
		$(counter).html('1');
	if ($(counter).html().match(/\D/))
		$(counter).html('1');
	if ($(counter).html().length>3)
		$(counter).html($(counter).html().substr(0, 3));
    if ($(counter).attr('id')){
        currentCart[$(counter).attr('id')].qty = $(counter).html();
        refreshCartCost();
    }
}

function refreshCartCost() {
    let sumPrice = 0;
    for (let i=0; i<currentCart.length;i++) {
        sumPrice += $('#currency').val()=='$' ? currentCart[i].cost_usd*currentCart[i].qty : currentCart[i].cost_gbp*currentCart[i].qty;
    }
    sumPrice = sumPrice.toFixed(2);
    $("#cart-count").html("<sup>"+$("#currency").val()+"</sup>"+sumPrice);
    $(".cart-inner-total").html('Total: '+$('#cart-count').html());
    let forCookie = JSON.stringify(currentCart);
    document.cookie = 'rmbct='+forCookie;
}

function toCartError(message) {
	let errMsg = $('.error-message');
    errMsg.html(message);
    errMsg.slideDown(100);
    setTimeout(function() {
        errMsg.slideUp(200);
    }, 4000);
}

function refreshCart() {
    refreshCartCost();
    $('.cart-inner').html('');
    $('.cart-inner').append('<div class="cart-inner-total">'+'Total: '+$('#cart-count').html()+'</div>');
    for (let i=0;i<currentCart.length;i++) {
        $('.cart-inner').append('<div class="cart-inner-item">');

        $('.cart-inner-item:last').append(
        '<a href="/product_view?id='+currentCart[i].id+'" class="cart-inner-item-image">'+
        '<img src="'+currentCart[i].image+'">');

        $('.cart-inner-item:last').append('<div class="cart-inner-item-info">');
        $('.cart-inner-item-info:last').append(
            '<a href="/product_view?id='+currentCart[i].id+'" class="cart-inner-item-info-name">'+currentCart[i].name+'</div>'
        );
        $('.cart-inner-item-info:last').append(
            '<div class="cart-inner-item-info-price">'+String($('#currency').val()=='$' ? '<sup>$</sup>'+currentCart[i].cost_usd : '<sup>£</sup>'+currentCart[i].cost_gbp)+'</div>'
        );
        $('.cart-inner-item-info:last').append(
            '<div class="cart-inner-item-info-options">'+currentCart[i].color+', '+currentCart[i].size+'</div>'
        );
        $('.cart-inner-item-info:last').append(
            '<div class="cart-inner-item-info-buttons">'
        );
        $('.cart-inner-item-info-buttons:last').html(
            '<div class="cart-inner-item-info-buttons-counter">'+
                '<div class="product-counter counter-cart">'+
                    '<div class="product-counter-inner">'+
                        '<div contenteditable="true" id="'+i+'" class="counter">'+currentCart[i].qty+'</div>'+
                        '<div class="counter-controls">'+
                             '<div class="counter-controls-up"></div>'+
                             '<div class="counter-controls-down"></div>'+
                        '</div>'+
                    '</div>'+
                '</div>'+
            '</div>'
        );
        $('.cart-inner-item-info-buttons:last').append(
            '<div id="cart-remove-'+i+'" class="cart-inner-item-info-buttons-remove cart-remove">Remove</div>'
        );
    }
    $(".counter-controls-up").bind('click', function() {
        counterControlsClick(this, 1);
    });
    $(".counter-controls-down").bind('click', function() {
        counterControlsClick(this, -1);
    });
    $(".counter").bind("keydown", function (e) {
        return counterKeyDown(e, this);
    });
    $(".counter").bind("keyup", function (e) {
        counterKeyUp(this);
    });
    $(".counter").bind("focusout", function (e) {
        counterFocusOut(this);
    });
    $('.cart-remove').bind('click', function() {
        currentCart.splice(Number($(this).attr('id').replace('cart-remove-')), 1);
        refreshCart();
    })
}

function openCart() {
	if ($('.cart.active').length==0 && $('.cart-inner-item').length==0) {
		refreshCart();
	}
	$('.cart').toggleClass('active');
	$('.cart-close').toggleClass('active');
}

$(".tocart").click(function() {
    let color = $('#to-cart-color').html();
    let size = $('#to-cart-size').html();
    if (color=="Select Color" || size=="Select Size") {
        toCartError('Error! Choose product options');
        return;
    }
    let data = {
        'color': color,
        'size': size,
        'count' :$('.to-cart-qty').html(),
        'id': productId
    };

    data = JSON.stringify(data);
    $.ajax({
    type: "POST",
    url: "/to_cart_check",
    data: data,
    success: function(response) {
			let parsed = JSON.parse(response);
			console.log(parsed)
            if (parsed==0) {
                toCartError('Error! No product with this size or color');
                return;
            }
            else {
                let inCartIndex = -1;
                currentCart.forEach(function(item, index, array) {
                    if (item.id==productId && item.color==color && item.size==size) {
                        inCartIndex = index;
                        return;
                    }
                });
                if (inCartIndex==-1) {
                    currentCart.push({   
                        'id': productId,
                        'color': color,
                        'size': size,
                        'qty': Number($('.to-cart-qty').html()),
                        'cost_usd': parsed.sale_usd>0 ? parsed.sale_usd : parsed.cost_usd,
                        'cost_gbp': parsed.sale_gbp>0 ? parsed.sale_gbp : parsed.cost_gbp,
                        'image': parsed.image_url,
                        'name': parsed.name
                    });
                } else {
                    currentCart[inCartIndex].qty = Number(currentCart[inCartIndex].qty) + Number($('.to-cart-qty').html());
                }
                refreshCart();
            }
        } 
    });
})

$(".header-basket").click(function (e) { 
    e.preventDefault();
    openCart();
});

$('.cart-close').click(function() {
    $('.header-basket').trigger('click');
});
//#endregion


$(".extra-button").click(function (e) { 
	e.preventDefault();
	
	let id = $(this).attr('id').replace('button', '')+'text';
	let lastActiveId = $('.extra-button.active').attr('id').replace('button', '')+'text';

	$('#'+lastActiveId).removeClass('active');
	$('.extra-button.active').removeClass('active');

	$('#'+id).addClass('active');
	$(this).addClass('active');
});


//#region REVIEWS
function changeReviewsPage(newPage) {
	let data = {
		'current_page': newPage,
		'id': productId
	};
	data = JSON.stringify(data);
    $.ajax({
    type: "POST",
    url: "/rev_page",
    data: data,
    success: function(response) {
			let parsed = JSON.parse(response);
			$('.review').remove();
			for (let i=0;i<parsed.length;i++) {
				$('#extra-reviews-text').append('<div class="review">');
				$('.review:last').append('<div class="review-user">');
				$('.review-user:last').append('<div class="review-user-name">'+parsed[i].login+'</div>');
				$('.review-user:last').append('<div class="review-user-date">'+parsed[i].date+'</div>');
				let rating = '';
				for (let j=0;j<parsed[i].rating;j++)
					rating += '<div class="star"><i class="fa fa-star" aria-hidden="true"></i></div>';
				for (let j=0;j<5-parsed[i].rating;j++)
					rating += '<div class="star"><i class="fa fa-star-o" aria-hidden="true"></i></div>';
				$('.review-user:last').append('<div class="review-user-rating">'+rating+'</div>');
				$('.review:last').append('<div class="review-text">'+parsed[i].text+'</div>');

			$('.reviews-page.active').removeClass('active');
			$('.reviews-page:eq('+(newPage-1)+')').addClass('active');
		}
	}
})
}

$(".reviews-pages-next").click(function (e) { 
	e.preventDefault();

	let newPage = Number($('.reviews-page.active span').html())+1;

	if (newPage<=$('.reviews-page').length)
		changeReviewsPage(newPage);
});

$(".reviews-pages-previous").click(function (e) { 
	e.preventDefault();

	let newPage = Number($('.reviews-page.active span').html())-1;

	if (newPage>=1)
		changeReviewsPage(newPage);
});

$(".reviews-page").click(function (e) { 
	e.preventDefault();
	if ($(this).attr('class').indexOf('active')!=-1)
		return;
	let newPage = Number($(this).children('span').html());
	changeReviewsPage(newPage);
});

$(".reviews-add-rating-star").click(function (e) { 
	e.preventDefault();
	
	let id = Number($(this).attr('id').replace('add-review-star-', ''));
	$('#add-review-rating').val(id);

	for (let i=1;i<=id;i++) {
		$("#add-review-star-"+i).addClass('active');
	}
	for (let i=id+1;i<=5;i++) {
		$("#add-review-star-"+i).removeClass('active');
	}
});
//#endregion

$(".home-to-cart").click(function() {
	
})
//#endregion



//#region ONCHANGE
function updateProductViewPrices() {
    if ($('#product-price-usd').length>0) {
        if ($("#currency").val()=='$')
        {
            $("#product-price-gbp").css('display', 'none');
            $('#product-price-usd').css('display', 'inline');
            $("#product-sale-gbp").css('display', 'none');
            $('#product-sale-usd').css('display', 'inline');
        }
        else
        {
            $("#product-price-gbp").css('display', 'inline');
            $('#product-price-usd').css('display', 'none');
            $("#product-sale-gbp").css('display', 'inline');
            $('#product-sale-usd').css('display', 'none');
        }
    }
}

function updateHomeItemsPrices() {
    for (let i=1;i<=$('.item-cost').length;i++)
    {
        let item = $('#item-cost-'+i);
        if ($('#currency').val()=='$')
            item.html(currentCosts.get(i-1).get('USD').toFixed(2)+" $");
        else
            item.html(currentCosts.get(i-1).get('GBP').toFixed(2)+" £");
	}
}

function updateCartPrices() {
    if (document.cookie.indexOf('rmbct')>-1) {
        for (let i=0;i<currentCart.length;i++) {
            $('.cart-inner-item-info-price:eq('+i+')').html('<sup>'+$('#currency').val()+'</sup>'+($('#currency').val()=="$" ? currentCart[i].cost_usd : currentCart[i].cost_gbp));
        }
        refreshCartCost();
	}
}

$("#currency").change(function (e) { 
    e.preventDefault();

    document.cookie = 'currency='+$("#currency").val();

	updateCartPrices();
	
	updateHomeItemsPrices();
	
	updateProductViewPrices();
});
//#endregion



//#region KEYUP KEYDOWN KEYPRESS

$('.form-inner input').keypress(function (e) { 
    $(this).css('border-color', '#e7e7e7');
    $(this).parent().children('.login-error-message').css('opacity', '0');
});


function appendSearchResult(item) {
	$('.search-results').append(
		"<a href='/product_view?id="+item['id']+"' class='search-results-item flex--row'>"+
			"<div class='search-results-image-container'>"+
				"<img src='"+item['image_url']+"' class='search-results-image'>"+
			"</div>"+
			"<span class='search-results-caption'>"+item['name']+"</span>"+
		"</a>"
	)
}

$('#search-form-text').keyup(function(e) {
    if ($(this).val()=='')
    {
        $('.search-results-item').remove();
        return;
    }

    let form_data = $(this).parent().serialize();

    $.ajax({
        type: "POST",
        url: "/search",
        data: form_data,
        success: function(response) {
            $('.search-results-item').remove();
            parsed = JSON.parse(response);

            for (let i=0;i<parsed.length;i++) {
				appendSearchResult(parsed[i]);
            }
            
            if ($('#search-form-text').val()=='')
            {
                $('.search-results-item').remove();
                return;
            }
        } 
      });
})


//#region COUNTER
$('.counter').keydown(function(e){
    return counterKeyDown(e, this);
});

$('.counter').keyup(function() {
    counterKeyUp(this);
});

$('.counter').focusout(function() {
    counterFocusOut(this);
})
//#endregion

//#endregion