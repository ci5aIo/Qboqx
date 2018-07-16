<?php
$aspect         = elgg_extract('aspect'        , $vars);
$element_type   = elgg_extract('element_type'  , $vars);
$guid           = elgg_extract('guid'          , $vars);
$asset          = elgg_extract('asset'         , $vars);
$container_guid = elgg_extract('container_guid', $vars);

$discovery_efforts = elgg_get_entities_from_relationship(array(
	'type'                 => 'object',
	'relationship'         => 'discovery_effort',
	'relationship_guid'    => $container_guid,
	'inverse_relationship' => true,
	// Discovery effort items must have a 'sort_order' value to apper in this group.  This value is applied by the actions pick and edit.
	'order_by_metadata'    => array('name' => 'sort_order', 
			                        'direction' => ASC, 
			                        'as' => 'integer'),
	'limit'                => false,
	));

	$discovery_form  = 
		"<b>Discoveries</b><br>
		<div class='rTable' style='width:100%'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:0'>".
								elgg_view('output/url', array(
								    'text' => '+',
									'href' => '#',
									'class' => 'elgg-button-submit-element clone-discovery-action' // unique class for jquery
									))."
		            </div>
					<div class='rTableHead' style='width:10%; padding: 0px 0px'><span title='When this happened'>Date</span></div>
					<div class='rTableHead' style='width:30%; padding: 0px 0px'><span title='What I did'>Action</span></div>
					<div class='rTableHead' style='width:30%; padding: 0px 0px'><span title='What I observed'>Observation</span></div>
					<div class='rTableHead' style='width:30%; padding: 0px 0px'><span title='What I learned'>Discovery</span></div>
					<div class='rTableHead' style='width:0; padding: 0px 0px'></div>
				</div>
				";
	
	// Populate existing research efforts
	$n=0;
	foreach($discovery_efforts as $effort){
		$n = $n+1;
		$element_type = 'discovery_effort';
		if ($effort->canEdit()) {
			$delete = elgg_view("output/url",array(
		    	'href' => "action/jot/delete?guid=$guid->GUID&container_guid=$container_guid",
		    	'text' => elgg_view_icon('delete'),
//				'text' => elgg_view_icon('arrow-left'),
		    	'confirm' => sprintf('Delete discovery?'),
		    	'encode_text' => false,
		    ));
			$date   = elgg_view('input/date', array(
					'name' => 'discovery[date][]',
					'value' => $effort->date,
			));
			$action = elgg_view('input/text', array(
	        		'name' => 'discovery[action][]',
	        		'value' => $effort->action,
	        		'class'=> 'rTableform',
	        ));        	
	        $observation = elgg_view('input/text', array(
	        		'name' => 'discovery[observation][]',
	        		'value' => $effort->observation,
	        		'class'=> 'rTableform',
	        ));        	
	        $discovery = elgg_view('input/text', array(
	        		'name' => 'discovery[discovery][]',
	        		'value' => $effort->discovery,
	        		'class'=> 'rTableform',
	        ));
	        $effort_guid = elgg_view('input/hidden', array(
					'name' => 'discovery[guid][]',
					'value' => $effort->getGUID(),
			));
		}
	$discovery_form  .= 
		"		<div class='rTableRow'>
					<div class='rTableCell' style='width:0; padding: 0px 0px'>{$delete}{$select}</div>
					<div class='rTableCell' style='width:10; padding: 0px 0px'>$date</div>
					<div class='rTableCell' style='width:30%; padding: 0px 0px'>$action</div>
					<div class='rTableCell' style='width:30%; padding: 0px 0px'>$observation</div>
					<div class='rTableCell' style='width:30%; padding: 0px 0px'>$discovery</div>
					<div class='rTableCell' style='width:0; padding: 0px 0px'>$effort_guid</div>
				</div>";
	}
	// Populate blank lines
	for ($i = $n+1; $i <= $n+3; $i++) {
		$pick = elgg_view('output/url', array(
				'text' => 'pick',
				'class' => 'elgg-button-submit-element elgg-lightbox',
				'data-colorbox-opts' => '{"width":500, "height":525}',
				'href' => "market/pick/item/" . $transfer_guid));
	$discovery_form  .= 
		"		<div class='rTableRow'>
					<div class='rTableCell' style='width:0; padding: 0px 0px'><a href='#' class='remove-node'>[X]</a></div>
					<div class='rTableCell' style='width:10%; padding: 0px 0px'>".elgg_view('input/date', array(
													'name' => 'discovery[date][]',
											))."</div>
					<div class='rTableCell' style='width:30%; padding: 0px 0px'>".elgg_view('input/text', array(
									        		'name' => 'discovery[action][]',
									        		'class'=> 'rTableform',
									        ))."</div>
					<div class='rTableCell' style='width:30%; padding: 0px 0px'>".elgg_view('input/text', array(
									        		'name' => 'discovery[observation][]',
									        		'class'=> 'rTableform',
									        ))."</div>
					<div class='rTableCell' style='width:30%; padding: 0px 0px'>".elgg_view('input/text', array(
									        		'name' => 'discovery[discovery][]',
									        		'class'=> 'rTableform',
									        ))."</div>
					<div class='rTableCell' style='width:0; padding: 0px 0px'></div>
				</div>";
	}
	$discovery_form  .= 
	"<div class='new_discovery'></div>
	</div>
</div>";
	
	$discovery_display  = 
		"<b>Discoveries</b><br>
		<div class='rTable' style='width:100%'>
			<div class='rTableBody'>
				<div class='rTableRow'>
					<div class='rTableCell' style='width:0; padding: 0px 0px'></div>
					<div class='rTableHead' style='width:10%; padding: 0px 0px'>Date</div>
					<div class='rTableHead' style='width:30%; padding: 0px 0px'>What I did</div>
					<div class='rTableHead' style='width:30%; padding: 0px 0px'>What I observed</div>
					<div class='rTableHead' style='width:30%; padding: 0px 0px'>What I learned</div>
					<div class='rTableHead' style='width:0; padding: 0px 0px'></div>
				</div>
				";
	
	// Populate existing research efforts
	$n=0;
	foreach($discovery_efforts as $effort){
		$n = $n+1;
		$element_type = 'discovery_effort';
		if ($effort->canEdit()) {
			$date   = $effort->date;
			$action = $effort->action;        	
	        $observation = $effort->observation;        	
	        $discovery = $effort->discovery;
	        $effort_guid = elgg_view('input/hidden', array(
					'name' => 'discovery[guid][]',
					'value' => $effort->getGUID(),
			));
		}
	$discovery_display  .= 
		"		<div class='rTableRow'>
					<div class='rTableCell' style='width:0; padding: 0px 0px'>$select</div>
					<div class='rTableCell' style='width:10; padding: 0px 0px'>$date</div>
					<div class='rTableCell' style='width:30%; padding: 0px 0px'>$action</div>
					<div class='rTableCell' style='width:30%; padding: 0px 0px'>$observation</div>
					<div class='rTableCell' style='width:30%; padding: 0px 0px'>$discovery</div>
					<div class='rTableCell' style='width:0; padding: 0px 0px'>$effort_guid</div>
				</div>";
	}
	$discovery_display  .= 
	"</div>
</div>";

echo $discovery_form;
echo $discovery_display;