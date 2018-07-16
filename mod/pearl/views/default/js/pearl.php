<?php if (FALSE) : ?>
    <script type="text/javascript">
<?php endif; ?>
elgg.provide('pearl');

pearl.loggedin = function(){
    $(document).ready(function() {
        var messages = $(".your_inbox").data('messages');
        var username = $(".your_profile").data('username');
        var url      = $(".your_dashboard").data('url');
        var logout   = $(".logout").data('logout');
        //console.log(username);
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
            timeout: 500,
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
    });
}
pearl.usermenu = function(){
	$(document).ready(function(){
        $(document).on("click", ".user_menu_tag", function(e){
            $(".user_menu_holder").slideToggle('slow');
        });
    });
}
pearl.loggedout = function(){
	$(document).ready(function(){
        $(".login_tab").on('click',function(){
            $(".loginform").fadeIn('slow');
            $(".registerform").hide();
            $(".login_tab").addClass('active');
            $(".register_tab").removeClass('active');
        });
        $(".register_tab").on('click',function(){
            $(".loginform").hide();
            $(".registerform").fadeIn('slow'); 
            $(".login_tab").removeClass('active');
            $(".register_tab").addClass('active');
        });
    });
};

elgg.register_hook_handler('init', 'system', pearl.loggedin);  
elgg.register_hook_handler('init', 'system', pearl.usermenu);
elgg.register_hook_handler('init', 'system', pearl.loggedout);

<?php if (FALSE) : ?></script><?php endif; ?>