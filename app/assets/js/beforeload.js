
let currentCosts = new Map();

let menuBreakpoint = 1250;


function getShopHomeItems(lastSlide = undefined) {
    if ($('.slide').length!=0) {
        let activeSlideNumber = $('.control.active').attr('id').replace('control-','');
        let activeSlide = $("#slide-"+activeSlideNumber);

        let form_data = {'slideNumber': activeSlideNumber};

        currentCosts = new Map();

        let parsed;
        $.ajax({
            type: "POST",
            url: "/load_shop_home_items",
            data: form_data,
            success: function(response) {
			   parsed = JSON.parse(response);
               for (var i=1;i<=parsed.length;i++)
               {
                    let cost = '';
                    if ($('#currency').val()=='$')
                        cost = parsed[i-1]['sale_usd']>0 ? parsed[i-1]['sale_usd'].toFixed(2)+" $" : parsed[i-1]['cost_usd'].toFixed(2)+" $";
                    else
                        cost = parsed[i-1]['sale_gbp']>0 ? parsed[i-1]['sale_gbp'].toFixed(2)+" £" : parsed[i-1]['cost_gbp'].toFixed(2)+" £";

                    activeSlide.append(
                        "<div class='shop-home-page-item'>"+

                            "<img src='"+parsed[i-1]['image_url']+"' id='item-img-"+i+"' class='item-img'>"+
                            "<span class='item-cost' id='item-cost-"+i+"'>"+cost+"</span>"+

                            "<div class='item-caption-container flex--col'>"+
                                "<div class='item-caption-container-inner flex--col'>"+
                                    "<span class='item-caption'>"+parsed[i-1]['name']+"</span>"+
                                    "<span class='item-description'>"+parsed[i-1]['description']+"</span>"+

                                    "<div class='flex--row'>"+
                                        "<a href='/product_view?id="+parsed[i-1]['id']+"' id='product-view' class='links-circle'><i class='fa fa-info links-circle-text' aria-hidden='true'></i></a>"+
                                        "<div id='home-to-cart-"+parsed[i-1]['id']+"' class='links-circle home-to-cart'><i class='fa fa-shopping-cart links-circle-text' aria-hidden='true'></i></div>"+
                                        "<div id='add-to-wishlist' class='links-circle'><i class='fa fa-heart-o links-circle-text' aria-hidden='true'></i></div>"+
                                        "<div id='add-to-compare' class='links-circle'><i class='fa fa-compress links-circle-text' aria-hidden='true'></i></div>"+
                                    "</div>"+
                                
                                "</div>"+
                            "</div>"+
                        "</div"
                    );

                    let itemCost = new Map();
                    itemCost.set('USD', parsed[i-1]['sale_usd']>0 ? parsed[i-1]['sale_usd'] : parsed[i-1]['cost_usd']);
                    itemCost.set('GBP', parsed[i-1]['sale_gbp']>0 ? parsed[i-1]['sale_gbp'] : parsed[i-1]['cost_gbp']);

                    currentCosts.set(i-1, itemCost);
               }
               if (parsed.length==6) {
                    let items = activeSlide.find($('.shop-home-page-item'));
                    $(items.slice(0, 3)).wrapAll('<div class="slides-items-wrapper left-wrapper flex--row">');
                    $(items.slice(3, 6)).wrapAll('<div class="slides-items-wrapper right-wrapper flex--row">');
                    activeSlide.find($('.slides-items-wrapper')).wrapAll("<div class='slides-items-wrapper-wrapper flex--row'>");
               }

               if (lastSlide!=undefined) {
                   setTimeout(function() {
                        currentMargin = -(activeSlideNumber-1)*100;
                        currentMargin = currentMargin+'%';
                        $('.slides-container').animate({
                            'margin-left' : currentMargin
                        }, 400, 'swing', function() {
                            $('#slide-'+lastSlide.attr('id').replace('control-','')).children($('.slides-items-wrapper-wrapper')).remove();
                        })
                   }, 100)
               }
            }
          });
    }
}

function getMenu() {
    let form_data = [];
    $.ajax({
    type: "POST",
    url: "/load_menu",
    data: form_data,
    success: function(response) {
        let parsed = JSON.parse(response);

        for (let menu=0;menu<$(".nav-item").length;menu++) {
            let item = $(".nav-item:eq("+menu+") a");
            item.html(parsed['menus'][menu]['menu_name']);
            item.parent().attr('id', parsed['menus'][menu]['menu_name'].replace(' ','-'));

            item.append(
            "<div class='"+item.parent().attr('id')+" slide-menu'>"+
            "<div class='slide-menu-inner flex--row'>");

            let filterSubnames = parsed['submenus'].filter(function(val) {
                return val['menu_id']==menu+1;
            })
            for (let submenu=0;submenu<filterSubnames.length;submenu++)
            {   
                $(".slide-menu-inner:last").append(
                    "<div class='slide-menu-col flex--col'>"+
                    "<span class='slide-menu-col-name'>"+filterSubnames[submenu]['submenu_name']+"</span>"
                );
                let filterPages = parsed['pages'].filter(function(val) {
                    return val['menu_id']==+filterSubnames[submenu]['id'];
                })
                for (let page=0;page<filterPages.length;page++)
                    $(".slide-menu-col:last").append(
                        "<a href='"+filterPages[page]['page_link']+"'><span class='slide-menu-item-text'>"+filterPages[page]['page_name']+"</span></a>"
                    )
            }
            $(".slide-menu-inner:last").append(
                "<div class='sale'>"+
                "<div class='sale-inner flex--col'>"+
                "<b>AUTUMN SALE!</b>"+
                "<span>UP TO 50% OFF</span>"
            )
        }
    }
  });
}


document.addEventListener("DOMContentLoaded", function(e)
{
    console.time();
    if ($('.intro-home-page').length>0)
        getShopHomeItems();
    getMenu();
    console.log(document.readyState);
});