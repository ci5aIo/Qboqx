<?php
/*
 *
 * Elgg Pearl
 *
 * @package pearl
 * @author Clifton Barron - SocialApparatus
 * @license Commercial
 * @copyright Copyright (c) 2012, Clifton Barron
 *
 * @link http://socia.us
 *
 */

elgg_load_js('hoverintent');
?>
<div id="user_menu">
    <div class="user_menu_holder">
        <?php
        if (elgg_is_logged_in()) {
            echo elgg_view('pearl/loggedin');
			$user = elgg_get_logged_in_user_entity();
        } else {
            echo elgg_view('pearl/loggedout');
        }
		$user_name = $user->name;
        ?>
    </div>
    <?php
    if (elgg_is_logged_in()) {
        echo "<div class='user_menu_tag'>$user_name</div>";
    } else {
        echo "<div class='user_menu_tag'>Login/Register</div>";
    }
    ?>
</div>
<!--@EDIT - 2017-10-12 - SAJ - Moved to pearl.js-->
<script type="text/javascript">
require(['elgg', 'jquery'], function (elgg, $) {
    $(document).ready(function(){
        $(".user_menu_tag").toggle(function(){
            $(".user_menu_holder").slideDown('slow');
        },function(){
            $(".user_menu_holder").slideUp('slow');
        });
    })
});
</script>
 