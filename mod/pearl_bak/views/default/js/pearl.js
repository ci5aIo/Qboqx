/**
 * 
 */

$(document).ready(function() {
    var messages = $('.user_menu_icons').data('messages');
    var username = $('.user_menu_icons').data('username');
    var url = $('.user_menu_icons').data('url');
    var logout = $('.user_menu_icons').data('logout');
//@TESTING console.log('username:', username);
    if (messages > 0) {
        $('.your_inbox').css('background-color','#80D5A7');
        $('.your_inbox').css('background-position','-120px 0px');
    }
    $(".your_dashboard").on('click', function() {
        window.location = url + 'dashboard';
    });
    $(".your_profile").on('click', function() {
        window.location = url + 'profile/' + username;
    });
    $(".your_inbox").on('click', function() {
        window.location = url + 'messages/inbox/' + username;
    });
    $(".your_friends").on('click', function() {
        window.location = url + 'friends/' + username;
    });
    $(".your_settings").on('click', function() {
        window.location = url + 'settings/user/' + username;
    });
    $(".view_cart").on('click', function() {
        window.location = url + 'shelf/';
    });
    $(".edit_profile").on('click', function() {
        window.location = url + 'profile/' + username + '/edit';
    });
    $(".edit_avatar").on('click', function() {
        window.location = url + 'avatar/edit/' + username;
    });
    $(".logout").on('click', function() {
        window.location = logout;
    });
    $(".pearl_button").hoverIntent({
        over: tooltip_show,
        timeout: 50000,
        out: tooltip_hide,
        interval: 500
    });
    function tooltip_show() {
        $(this).find('.user_menu_tooltip').fadeIn('slow');
        var width = $(this).find('.user_menu_tooltip').width();
        $(this).find('.user_menu_tooltip').css({left: -((width / 2) - 12)});
    }
    function tooltip_hide() {
        $(this).find('.user_menu_tooltip').hide();
    }
})
