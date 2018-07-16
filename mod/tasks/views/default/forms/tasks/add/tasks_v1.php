<script>
//Add input fields when TAB pressed.  Source: http://jsbin.com/amoci/123/edit
$(function () {
	$(document).on('keydown', 'input.last_content_item', function(e) { 
	    var keyCode = e.keyCode || e.which; 

	    if (keyCode == 9) { 
	      e.preventDefault(); 
	      $(this).removeClass("last_content_item");
		  $(this).trigger({type: 'keypress', which: 9, keyCode: 9}); // supposed to send the TAB keystroke, but doesn't work ... yet.
		  var html = $('.content_item').html();
		  $(html).insertBefore('.new_content_item');
	    } 
	});
	// remove a node
	$('.remove-node').on('click', function(e){
		e.preventDefault();
		
		// remove the node
		$(this).parents('div').parents('div').eq(0).remove();
	});
    $( "#sortable" ).sortable({
	    revert: true,
	    items: "> div:not(.pin)"
	});	
    $( "#draggable" ).draggable({
        revert: false,
        connectToSortable: "#sortable",
      });
});
</script>

<?php
$panel = $vars['panel'];

/******************************/
//Contents Add
    $contents_add .= elgg_view('input/hidden', array('name'=>'element_type'  , 'value'=>$vars['element_type']));
    $contents_add .= elgg_view('input/hidden', array('name'=>'aspect'        , 'value'=>$vars['aspect']));
    $contents_add .= elgg_view('input/hidden', array('name'=>'task[guid]', 'value'=>$vars['task_guid']));
    $contents_add .= elgg_view('input/hidden', array('name'=>'task[container_guid]', 'value'=>$vars['container_guid']));
    $contents_add .= elgg_view('input/hidden', array('name'=>'task[parent_guid]'   , 'value'=>$vars['parent_guid']));
    $contents_add .="
	     <div class='rTable' style='width:100%'>
    		<div id='sortable' class='rTableBody'>
	            <div class='rTableRow'>";
    $contents_add .="<div class='rTableCell' style='width:25px;cursor:move'></div>
    				<div class='rTableCell' style='width:575px'>".
    			      elgg_view('input/text', array(
    					'name' => 'jot[task_step][][title]',
    					'class' => 'last_content_item',
    			      	 'placeholder'=>'Step Name',
    				))."
		            </div>";
    $contents_add .="
                </div>";
    $contents_add .= '<div class="new_content_item"></div>';
    $contents_add .= "</div>
            </div>";
    
/*    $contents_add .= elgg_view('input/submit', array('value'=>'add tasks',
	                                                 'class' => 'elgg-button-submit-element',
                                                     'style' => 'width:75px',));
*/    // Contents 
    $contents_add .= "
    <div id ='store' style='visibility:hidden'>";
    $contents_add .= "
    	<div class='content_item'>
    	    <div class='rTableRow'>
				<div class='rTableCell' style='width:25px;cursor:move'><a href='#' class='remove-node'>[X]</a></div>
    			<div class='rTableCell' style='width:575px'>".
			      elgg_view('input/text', array(
					'name'        => 'jot[task_step][][title]',
					'class'       => 'last_content_item',
		      	    'placeholder' => 'Step Name',
				))."</div>";
    $contents_add .= "
    		</div>
    	</div>";
    $contents_add .= "
	</div>";

/******************************/
//Components Add


/******************************/
//Accessories Add

if ($panel == 'tasks_add_panel'){echo $contents_add;}
if ($panel == 'components'){echo $components_add;}
if ($panel == 'accessories'){echo $accessories_add;}