View: tasks\views\default\tasks\display\task.php
<?php
$section = lcfirst($vars['this_section']);

if (empty($section)){
	$section = 'summary';
}

echo '<!--View: \default\tasks\display\task.php => '.$section.'.php-->';
echo '<!--View context: '.elgg_get_context().'--></p>';
//echo elgg_dump($vars);
//echo elgg_view_list_item($vars['entity'], array('full_view' => true));

	$destination = "tasks/display/$section";

	if (elgg_view_exists($destination)){
		echo elgg_view($destination, $vars);
	}
    if (!elgg_view_exists($destination)){
	   	register_error("$destination<b> not found</b>");
    }
 