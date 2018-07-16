<?php
$section = lcfirst($vars['this_section']);

if (empty($section)){
	$section = 'summary';
}

echo '<!-- View section control - tasks\views\default\tasks\display\que.php => '.$section.'.php<br>-->';
echo '<!--View context: '.elgg_get_context().'--></p>';
/*
	$destination = "tasks/display/que/$section";
	echo "<!--Section: $section-->";

	if (elgg_view_exists($destination)){
		echo elgg_view($destination, $vars);
	}
    if (!elgg_view_exists($destination)){
	   	register_error("$destination<b> not found</b>");
    } 
*/

?>
<script> 
$(document).ready(function(){
    $("#Summary_tab").click(function(){
        $("#Summary_panel").slideDown("slow");
        $("#Summary_tab").addClass("elgg-state-selected");
        
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
        $("#Details_panel").slideDown("slow");
        $("#Details_tab").addClass("elgg-state-selected");
        
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
        $("#Schedule_panel").slideDown("slow");
        $("#Schedule_tab").addClass("elgg-state-selected");
        
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
        $("#Inventory_panel").slideDown("slow");
        $("#Inventory_tab").addClass("elgg-state-selected");
        
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
        $("#Management_panel").slideDown("slow");
        $("#Management_tab").addClass("elgg-state-selected");
        
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
        $("#Accounting_panel").slideDown("slow");
        $("#Accounting_tab").addClass("elgg-state-selected");
        
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
        $("#Gallery_panel").slideDown("slow");
        $("#Gallery_tab").addClass("elgg-state-selected");
        
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
        $("#Reports_panel").slideDown("slow");
        $("#Reports_tab").addClass("elgg-state-selected");
        
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
        $("#Timeline_panel").slideDown("slow");
        $("#Timeline_tab").addClass("elgg-state-selected");
        
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
#Details_panel, #Schedule_panel, #Inventory_panel, #Management_panel, #Accounting_panel, #Gallery_panel, #Reports_panel, #Timeline_panel {
	display: none;
}
</style>
<?php
echo "<div id='Summary_panel' class='elgg-head'><h4>".elgg_echo("jot:title:schedule")."</h4>";
include __DIR__."/que/summary.php";
echo "</div>";

echo "<div id='Details_panel' class='elgg-head'><h4>Details</h4>";
include __DIR__."/que/details.php";
echo "</div>";

echo "<div id='Schedule_panel' class='elgg-head'><h4>Schedule</h4>";
include __DIR__."/que/schedule.php";
echo "</div>";

echo "<div id='Inventory_panel' class='elgg-head'><h4>Inventory</h4>";
include __DIR__."/que/inventory.php";
echo "</div>";

echo "<div id='Management_panel' class='elgg-head'><h4>Management</h4>";
include __DIR__."/que/management.php";
echo "</div>";

echo "<div id='Accounting_panel' class='elgg-head'><h4>Accounting</h4>";
include __DIR__."/que/accounting.php";
echo "</div>";

echo "<div id='Gallery_panel' class='elgg-head'><h4>Gallery</h4>";
include __DIR__."/que/gallery.php";
echo "</div>";

echo "<div id='Reports_panel' class='elgg-head'><h4>Reports</h4>";
include __DIR__."/que/reports.php";
echo "</div>";

echo "<div id='Timeline_panel' class='elgg-head'><h4>Timeline</h4>";
include __DIR__."/que/timeline.php";
echo "</div>";