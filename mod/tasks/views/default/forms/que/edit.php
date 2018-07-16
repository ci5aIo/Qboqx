<?php
/**
 * Page edit form body
 *
 * @package ElggPages
 */

$guid           = get_input('guid');
if (empty($guid)){$guid   = $vars['item_guid'];}
$que            = get_entity($guid);
//$container_guid = get_input('container_guid');
//$element_type   = get_input('element_type');
$referrer       = elgg_extract('referrer', $vars);
$owner          = $que->getOwnerEntity();
$subtype        = $que->getSubtype();
$aspect         = $que->aspect;
$asset          = $que->asset;

//echo elgg_view('input/hidden', array('name' => 'container'   , 'value' => $container_guid));
//echo elgg_view('input/hidden', array('name' => 'element_type', 'value' => $element_type));
echo elgg_view('input/hidden', array('name' => 'referrer'    , 'value' => $referrer));
echo elgg_view('input/hidden', array('name' => 'que[guid]'   , 'value' => $guid));
echo elgg_view('input/hidden', array('name' => 'que[subtype]', 'value' => $subtype));
echo elgg_view('input/hidden', array('name' => 'que[aspect]' , 'value' => $aspect));
echo elgg_view('input/hidden', array('name' => 'que[asset]'  , 'value' => $asset));

echo 'Form: mod\tasks\views\default\forms\que\edit.php<br>
	  Action: que/edit<br>';
?>
<script> 
$(document).ready(function(){
    $("#Summary_tab").click(function(){
        $("#Summary_panel").slideToggle("slow");
        $("#Summary_tab").toggleClass("elgg-state-selected");
        
        $("#Details_panel").slideUp("slow");
        $("#Details_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").slideUp("slow");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Inventory_panel").slideUp("slow");
        $("#Inventory_tab").removeClass("elgg-state-selected");
        $("#Management_panel").slideUp("slow");
        $("#Management_tab").removeClass("elgg-state-selected");
        $("#Accounting_panel").slideUp("slow");
        $("#Accounting_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideUp("slow");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Reports_panel").slideUp("slow");
        $("#Reports_tab").removeClass("elgg-state-selected");
        $("#Timeline_panel").slideUp("slow");
        $("#Timeline_tab").removeClass("elgg-state-selected");
    });
    $("#Details_tab").click(function(){
        $("#Details_panel").slideToggle("slow");
        $("#Details_tab").toggleClass("elgg-state-selected");
        
        $("#Summary_panel").slideUp("slow");
        $("#Summary_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").slideUp("slow");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Inventory_panel").slideUp("slow");
        $("#Inventory_tab").removeClass("elgg-state-selected");
        $("#Management_panel").slideUp("slow");
        $("#Management_tab").removeClass("elgg-state-selected");
        $("#Accounting_panel").slideUp("slow");
        $("#Accounting_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideUp("slow");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Reports_panel").slideUp("slow");
        $("#Reports_tab").removeClass("elgg-state-selected");
        $("#Timeline_panel").slideUp("slow");
        $("#Timeline_tab").removeClass("elgg-state-selected");
    });
    $("#Schedule_tab").click(function(){
        $("#Schedule_panel").slideToggle("slow");
        $("#Schedule_tab").toggleClass("elgg-state-selected");
        
        $("#Summary_panel").slideUp("slow");
        $("#Summary_tab").removeClass("elgg-state-selected");
        $("#Details_panel").slideUp("slow");
        $("#Details_tab").removeClass("elgg-state-selected");
        $("#Inventory_panel").slideUp("slow");
        $("#Inventory_tab").removeClass("elgg-state-selected");
        $("#Management_panel").slideUp("slow");
        $("#Management_tab").removeClass("elgg-state-selected");
        $("#Accounting_panel").slideUp("slow");
        $("#Accounting_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideUp("slow");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Reports_panel").slideUp("slow");
        $("#Reports_tab").removeClass("elgg-state-selected");
        $("#Timeline_panel").slideUp("slow");
        $("#Timeline_tab").removeClass("elgg-state-selected");
    });
    $("#Inventory_tab").click(function(){
        $("#Inventory_panel").slideToggle("slow");
        $("#Inventory_tab").toggleClass("elgg-state-selected");
        
        $("#Summary_panel").slideUp("slow");
        $("#Summary_tab").removeClass("elgg-state-selected");
        $("#Details_panel").slideUp("slow");
        $("#Details_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").slideUp("slow");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Management_panel").slideUp("slow");
        $("#Management_tab").removeClass("elgg-state-selected");
        $("#Accounting_panel").slideUp("slow");
        $("#Accounting_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideUp("slow");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Reports_panel").slideUp("slow");
        $("#Reports_tab").removeClass("elgg-state-selected");
        $("#Timeline_panel").slideUp("slow");
        $("#Timeline_tab").removeClass("elgg-state-selected");
    });
    $("#Management_tab").click(function(){
        $("#Management_panel").slideToggle("slow");
        $("#Management_tab").toggleClass("elgg-state-selected");
        
        $("#Summary_panel").slideUp("slow");
        $("#Summary_tab").removeClass("elgg-state-selected");
        $("#Details_panel").slideUp("slow");
        $("#Details_tab").removeClass("elgg-state-selected");
        $("#Inventory_panel").slideUp("slow");
        $("#Inventory_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").slideUp("slow");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Accounting_panel").slideUp("slow");
        $("#Accounting_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideUp("slow");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Reports_panel").slideUp("slow");
        $("#Reports_tab").removeClass("elgg-state-selected");
        $("#Timeline_panel").slideUp("slow");
        $("#Timeline_tab").removeClass("elgg-state-selected");
    });
    $("#Accounting_tab").click(function(){
        $("#Accounting_panel").slideToggle("slow");
        $("#Accounting_tab").toggleClass("elgg-state-selected");
        
        $("#Summary_panel").slideUp("slow");
        $("#Summary_tab").removeClass("elgg-state-selected");
        $("#Details_panel").slideUp("slow");
        $("#Details_tab").removeClass("elgg-state-selected");
        $("#Inventory_panel").slideUp("slow");
        $("#Inventory_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").slideUp("slow");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Management_panel").slideUp("slow");
        $("#Management_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideUp("slow");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Reports_panel").slideUp("slow");
        $("#Reports_tab").removeClass("elgg-state-selected");
        $("#Timeline_panel").slideUp("slow");
        $("#Timeline_tab").removeClass("elgg-state-selected");
    });
    $("#Gallery_tab").click(function(){
        $("#Gallery_panel").slideToggle("slow");
        $("#Gallery_tab").toggleClass("elgg-state-selected");
        
        $("#Summary_panel").slideUp("slow");
        $("#Summary_tab").removeClass("elgg-state-selected");
        $("#Details_panel").slideUp("slow");
        $("#Details_tab").removeClass("elgg-state-selected");
        $("#Inventory_panel").slideUp("slow");
        $("#Inventory_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").slideUp("slow");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Management_panel").slideUp("slow");
        $("#Management_tab").removeClass("elgg-state-selected");
        $("#Accounting_panel").slideUp("slow");
        $("#Accounting_tab").removeClass("elgg-state-selected");
        $("#Reports_panel").slideUp("slow");
        $("#Reports_tab").removeClass("elgg-state-selected");
        $("#Timeline_panel").slideUp("slow");
        $("#Timeline_tab").removeClass("elgg-state-selected");
    });
    $("#Reports_tab").click(function(){
        $("#Reports_panel").slideToggle("slow");
        $("#Reports_tab").toggleClass("elgg-state-selected");
        
        $("#Summary_panel").slideUp("slow");
        $("#Summary_tab").removeClass("elgg-state-selected");
        $("#Details_panel").slideUp("slow");
        $("#Details_tab").removeClass("elgg-state-selected");
        $("#Inventory_panel").slideUp("slow");
        $("#Inventory_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").slideUp("slow");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Management_panel").slideUp("slow");
        $("#Management_tab").removeClass("elgg-state-selected");
        $("#Accounting_panel").slideUp("slow");
        $("#Accounting_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideUp("slow");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Timeline_panel").slideUp("slow");
        $("#Timeline_tab").removeClass("elgg-state-selected");
    });
    $("#Timeline_tab").click(function(){
        $("#Timeline_panel").slideToggle("slow");
        $("#Timeline_tab").toggleClass("elgg-state-selected");
        
        $("#Summary_panel").slideUp("slow");
        $("#Summary_tab").removeClass("elgg-state-selected");
        $("#Details_panel").slideUp("slow");
        $("#Details_tab").removeClass("elgg-state-selected");
        $("#Inventory_panel").slideUp("slow");
        $("#Inventory_tab").removeClass("elgg-state-selected");
        $("#Schedule_panel").slideUp("slow");
        $("#Schedule_tab").removeClass("elgg-state-selected");
        $("#Management_panel").slideUp("slow");
        $("#Management_tab").removeClass("elgg-state-selected");
        $("#Accounting_panel").slideUp("slow");
        $("#Accounting_tab").removeClass("elgg-state-selected");
        $("#Gallery_panel").slideUp("slow");
        $("#Gallery_tab").removeClass("elgg-state-selected");
        $("#Reports_panel").slideUp("slow");
        $("#Reports_tab").removeClass("elgg-state-selected");
    });
});
</script>

<style> 
img.normal {
    height: auto;
}
img.big {
    height: 120px;
}
input.w100 {
    width: 100px;
}
input.w50 {
    width: 50px;
}
#Inventory_panel, #Management_panel, #Accounting_panel, #Gallery_panel, #Reports_panel, #Timeline_panel {
	display: none;
}
</style>
<?php
echo "<div id='Schedule_panel' class='elgg-head'><h4>Schedule</h4>";
include __DIR__."/schedule.php";
echo "</div>";

echo "<div id='Inventory_panel' class='elgg-head'><h4>Inventory</h4>";
include __DIR__."/inventory.php";
echo "</div>";

echo "<div id='Management_panel' class='elgg-head'><h4>Management</h4>";
include __DIR__."/management.php";
echo "</div>";

echo "<div id='Accounting_panel' class='elgg-head'><h4>Accounting</h4>";
include __DIR__."/accounting.php";
echo "</div>";

echo "<div id='Gallery_panel' class='elgg-head'><h4>Gallery</h4>";
$mod = elgg_get_plugins_path();
include "$mod/market/views/default/forms/market/edit/gallery.php";
echo "</div>";

echo "<div id='Reports_panel' class='elgg-head'><h4>Reports</h4>";
include __DIR__."/reports.php";
echo "</div>";

echo "<div id='Timeline_panel' class='elgg-head'><h4>Timeline</h4>";
include __DIR__."/timeline.php";
echo "</div>";

echo '<div class="elgg-foot">';
if ($vars['guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'task_guid',
		'value' => $vars['guid'],
	));
}
echo elgg_view('input/hidden', array(
	'name' => 'container_guid',
	'value' => $vars['container_guid'],
));
if ($vars['parent_guid']) {
	echo elgg_view('input/hidden', array(
		'name' => 'parent_guid',
		'value' => $vars['parent_guid'],
	));
}
echo elgg_view('input/submit', array('value' => elgg_echo('save')));
echo "</div>";