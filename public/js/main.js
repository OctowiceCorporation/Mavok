$(document).ready(function () {

    $.ajax({
        method: "POST",
        url: '/header_categories',
    })
        .done(function(msg) {
            let array = JSON.parse(msg);
            console.log(array);
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

            }
        });

    if(window.screen.width < 700){
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
                    $(this).css('visibility', 'visible').css('top', 0).css('opacity', 1).css('margin-top', '36px').css('border','thin solid #dadada').css('background-color', 'white').css('left',0).css('width','auto');
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
        })
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
                element.html('Уже в корзине <i class="fas fa-check"></i>').css('background-color','green');
            });
    })


});
