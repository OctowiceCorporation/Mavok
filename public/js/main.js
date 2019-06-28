$('#header_dropdown').hide();


$.ajax({
    method: "GET",
    url: '/header_categories',
})
    .done(function(msg) {
        let array = JSON.parse(msg);
        let parent = $('#header_dropdown');
        Object.keys(array).forEach(function (key) {
            if(array[key]['is_visible'] === true)
                callback_category(parent, array[key]);
        });
        function callback_category(element, category) {
            let is_sub_exist = false;
            if(category['sub'].length === undefined)
                is_sub_exist = true;
            let li = $('<li></li>').append($('<a style="display: inline" href="/category'+category["link"]+'">'+category["name"]+'</a> '));
            if(is_sub_exist){
                li.addClass('header_hassubs');
                li.append('<i style="position: absolute; right: 0; display: inline-block; padding: 1em" class="fas fa-chevron-right open-ul"></i>');
                let ul = $('<ul style="display: none;"></ul>');
                li.append(ul);
                Object.keys(category['sub']).forEach(function (key) {
                    if(category['sub'][key]['is_visible'] === true)
                        callback_category(ul, category['sub'][key]);
                });
            }
            element.append(li);
            $('#header_dropdown').show();

        }
    });

$.ajax({
    method: "GET",
    url: '/get_product_amount',
})
    .done(function(amount) {;
        $('#basket-product-amount').html(amount);
    });


$(document).ready(function () {


    if(window.screen.width < 800){
        $('#header_dropdown').css('width', '95vw');
        $(document).on('click', '.open-ul',function () {
            let uls = $('.open-ul-opened');
            let not_closing = $(this).parents('ul');
            let close = true;
            $(this).addClass('open-ul-opened');
            $(this).removeClass('open-ul');
            let elements = $(this).closest('.header_hassubs').find('ul');
            $(this).css('transition-duration', '0.2s').css('transform','rotate(90deg)');
            $(elements).each(function (key) {
                if(key === 0){
                    $(this).show();
                    $(this).css('visibility', 'visible').css('top', 0).css('opacity', 1).css('margin-top', '36px').css('border','thin solid #dadada').css('background-color', 'white').css('left',0).css('width','auto').css('z-index',2);
                }
            });
            $(not_closing).each(function (key) {
                if($(this).attr('id') === 'header_dropdown')
                    not_closing.splice(key, 1);
                if(not_closing.length && not_closing.length > 0)
                    close = false;
            });
            $(uls).each(function () {
                if(close)
                    $(this).css('transform','rotate(0deg)');
                let ul = $(this).closest('.header_hassubs').find('ul');
                $(ul).each(function () {
                    if(close){
                        $(this).css('opacity', 0).css('left', '50%').css('top', '-50%').delay(200).queue(function (next) {
                            $(this).css('display', 'none');
                            next()});
                    }
                });

            });
        });
        $(document).on('click', '.open-ul-opened',function () {
            $(this).closest('.header_hassubs').find('.open-ul-opened').css('transform','rotate(0deg)').css('transition-duration', '0').removeClass('open-ul-opened').addClass('open-ul');
            $(this).removeClass('open-ul-opened');
            $(this).addClass('open-ul');
            let elements = $(this).closest('.header_hassubs').find('ul');

            $(elements).each(function () {
                $(this).css('opacity', 0).css('left', '50%').css('top', '-50%').delay(200).queue(function (next) {
                    $(this).css('display', 'none');
                    next()});
            })
        });

        $(document).on('click', '.mobile-header-closed', function (e) {
                if (e.target === this || e.target === $('.cat_menu_text')[0] || e.target.tagName === 'SPAN' || e.target === $('.cat_menu_title')[0] || e.target === $('.cat_burger')[0]){
                $('#header_dropdown').css('left', '20%').css('left', 0).css('opacity', 1);
                $(this).removeClass('mobile-header-closed').addClass('mobile-header-opened');
            }
        });

        $(document).on('click', '.mobile-header-opened', function (e) {
            if (e.target === this || e.target === $('.cat_menu_text')[0] || e.target.tagName === 'SPAN' || e.target === $('.cat_menu_title')[0] || e.target === $('.cat_burger')[0]){
                $('#header_dropdown').css('opacity', 0).css('left', '20%');
                $(this).removeClass('mobile-header-opened').addClass('mobile-header-closed');
            }
        });
    }
    else{
        $(document).on('mouseover', '.header_hassubs',function () {
            let elements = $(this).find('ul')[0];
            $(elements).each(function () {
                $(this).show();
                $(this).css('visibility', 'visible').css('top', 0).css('left', '100%').css('opacity', 1);
            })
        });
        $(document).on('mouseout', '.header_hassubs',function () {
            let elements = $(this).find('ul')[0];
            $(elements).each(function () {
                $(this).show();
                $(this).css('visibility', 'hidden').css('top', '20%').css('right','50%').css('opacity', 0);
            })
        });
    }

    $(document).on('click','.add_to_basket', function () {
        let element = $(this);
        $.ajax({
            method: "POST",
            url: '/add_to_basket',
            data: {
                'slug': $(this).val(),
            }
        })
            .done(function() {
                element.html('В корзине <i class="fas fa-check"></i>').css('background-color','green').css('width','90%');
                $('#basket-product-amount').html(Number($('#basket-product-amount').html()) + 1);
            });
    });

    $(document).on('keydown','#mobile-search', function () {
        let value = $(this).val();

        if(value.length === 0)
            return;

        $.ajax({
            method: "GET",
            url: '/ajax_search/'+value,
        })
            .done(function(json) {
                let array = JSON.parse(json);
                $('#mobile-search-result').html('');
                if(array.length === 0){
                    let submit = $('<a></a>').addClass('list-group-item list-group-item-action').html('Ничего не найдено');
                    $('#mobile-search-result').append(submit);
                }
                else {
                    array.forEach(function (product) {
                        let href = $('<a></a>').addClass('list-group-item list-group-item-action').attr('href', '/product/' + product['slug']).html(product['name']);
                        $('#mobile-search-result').append(href);
                    });
                }
            });
    });

    $(document).on('blur', '#mobile-search', function () {
        window.setTimeout(function() { $('#mobile-search-result').html(''); }, 100);
    });

    $(document).on('keydown','#desktop-search', function () {
        let value = $(this).val();

        if(value.length === 0)
            return;

        $.ajax({
            method: "GET",
            url: '/ajax_search/'+value,
        })
            .done(function(json) {
                let array = JSON.parse(json);
                $('#desktop-result').html('');
                if(array.length === 0){
                    let submit = $('<a></a>').addClass('list-group-item list-group-item-action').css('background-color','#0e8ce4').css('background-color','#0e8ce4').css('color', 'white').html('Ничего не найдено');
                    $('#desktop-result').append(submit);
                }
                else {
                    array.forEach(function (product) {
                        let href = $('<a></a>').addClass('list-group-item list-group-item-action').attr('href', '/product/' + product['slug']).html(product['name']);
                        $('#desktop-result').append(href);
                    });
                    let submit = $('<a></a>').addClass('list-group-item list-group-item-action').attr('href', '/product_search?search_text=' + value).css('background-color', '#0e8ce4').css('color', 'white').html('Посмотреть все варианты');
                    $('#desktop-result').append(submit);
                }
            });
    });

    $(document).on('focusout', '#desktop-search', function () {
        window.setTimeout(function() { $('#desktop-result').html(''); }, 200);
    });




});
