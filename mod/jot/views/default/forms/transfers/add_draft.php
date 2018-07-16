<!--Form: jot\views\default\forms\purchases\add.php-->
<?php

//$variables = elgg_get_config('observation');
//$parent_guid = (int) get_input('parent_guid');
$item_guid = $vars['item_guid'];
$description = $vars['description'];
//$description = $jot['description'];
$referrer = $vars['referrer'];
//$entity = $vars['entity'];
$item = get_entity($item_guid);
$ts = time();
$title = elgg_echo("New Transfer");
$tags = "";
//echo elgg_dump($item);
echo "<!--referrer: $referrer-->";

// echo elgg_dump($vars);
//echo 'Referrer: '.$referrer;
//echo 'Description: '.$description;
//echo 'item_guid: '.$item_guid;
//echo '<br>Title: '.$title;

echo elgg_view('input/hidden', array('name' => 'item_guid', 'value' => $item_guid));
echo elgg_view('input/hidden', array('name' => 'referrer' , 'value' => $referrer));
echo elgg_view('input/hidden', array('name' => 'type'     , 'value' => 'object'));
echo elgg_view('input/hidden', array('name' => 'subtype'  , 'value' => 'transfer'));
echo elgg_view('input/hidden', array('name' => 'state'    , 'value' => '1'));
 
// Get plugin settings
$allowhtml = elgg_get_plugin_setting('jot_allowhtml', 'jot');
$numchars = elgg_get_plugin_setting('jot_numchars', 'jot');
if($numchars == ''){
	$numchars = '250';
}


if (defined('ACCESS_DEFAULT')) {
	$access_id = ACCESS_DEFAULT;
} else {
	$access_id = 0;
}

?>

<script type="text/javascript">
function textCounter(field,cntfield,maxlimit) {
	// if too long...trim it!
	if (field.value.length > maxlimit) {
		field.value = field.value.substring(0, maxlimit);
	} else {
		// otherwise, update 'characters left' counter
		$("#"+cntfield).html(maxlimit - field.value.length);
	}
}
$(document).ready(function () {

    $('#purchasechoice').change(function() {
        var divToShow = $(this).find('input:checked').attr('id');
        $('#purchase_types > div').each(function() {
            if($(this).hasClass(divToShow)) { $(this).show(); }
            else { $(this).hide();}
        });

    });

    $('#purchasechoice').trigger('change');
});

</script>
<!-- 09/24/2015 - For some strange reason, the empty form tags below are needed to make the choices work
	 Do not remove -->
<form action="" method="POST"></form>

<!-- Code from http://stackoverflow.com/questions/8993304/hiding-divs-based-on-a-selected-radio-button
-->
<style type="text/css">
    #purchase_types > div { display: none; }
</style>

<div id="purchasewrapper">
    <div id="purchaseinner">
        <div id="signup" style="border:thin; border-color:#666">
            <div id="accountswrapper">
                <form id="purchasechoice" name="purchasechoice" method="post" action="">
                    <label for="offer">Offer to Buy</label>   
                    <input type="radio" name="radio" id="offer" value="radio1" checked="checked" />

                    <label for="receipt">Purchase New</label>   
                    <input type="radio" name="radio" id="receipt" value="radio2" />
                </form>  
<?php
					echo elgg_view('forms/purchases/elements/header', $vars);
?>
                <div id="purchase_types">
			            <div class="offer">
<?php
						echo elgg_view('forms/purchases/elements/offer');
?>                    	
			           </div>
		              <div class="receipt">
<?php
					  echo elgg_view('forms/purchases/elements/receipt');
?>                    	
		           	</div>
                </div>
            </div>
        </div>
    </div>
</div>


<?php
echo "<p>";
echo elgg_view('input/submit', array('name' => 'submit', 'value' => elgg_echo('jot:save:purchase')));


