<?php
$aspect         = elgg_extract('aspect'        , $vars);
$element_type   = elgg_extract('element_type'  , $vars);
$guid           = elgg_extract('guid'          , $vars);
$asset          = elgg_extract('asset'         , $vars);
$container_guid = elgg_extract('container_guid', $vars);

Switch ($element_type){
    case 'transfer':
        Switch ($aspect){
            case 'receipt':
                $form .= '<td>'.
                          elgg_view("input/text", array(
            				"name" => "jot[title]",
            				)).
            			'</td>
            			<td>'.
            			elgg_view('input/submit', array(
            	            "value" => "add!",
            	            "class" => 'elgg-button-submit-element',
            	           )).
            		    '</td>';
                $hidden = elgg_view('input/hidden', array('name' => 'element_type'       , 'value' => $element_type)).
                          elgg_view('input/hidden', array('name' => 'jot[aspect]'        , 'value' => $aspect)).
                          elgg_view('input/hidden', array('name' => 'jot[asset]'         , 'value' => $asset));
                break;
        }
        break;
    default:
        $form = '<tr>
                <td nowrap width=100%>'. 
              elgg_view("input/text", array(
        				"name" => "jot[title]",
        				)).
        	  '</td>
        	   <td align="right">'.
        	  elgg_view('input/submit', array(
        	            "value" => "add!",
        	            "class" => 'elgg-button-submit-element',
        	           )).
        	   '</td>
        	 </tr>';
        $hidden = elgg_view('input/hidden', array('name' => 'element_type'       , 'value' => $element_type)).
                  elgg_view('input/hidden', array('name' => 'action'             , 'value' => elgg_extract('action', $vars))).
                  elgg_view('input/hidden', array('name' => 'jot[aspect]'        , 'value' => $aspect)).
                  elgg_view('input/hidden', array('name' => 'jot[guid]'          , 'value' => $guid)).
                  elgg_view('input/hidden', array('name' => 'jot[location]'      , 'value' => elgg_extract('location', $vars))).
                  elgg_view('input/hidden', array('name' => 'jot[owner_guid]'    , 'value' => elgg_extract('owner_guid', $vars))).
                  elgg_view('input/hidden', array('name' => 'jot[asset]'         , 'value' => $asset)).
                  elgg_view('input/hidden', array('name' => 'jot[container_guid]', 'value' => $container_guid));
        break;
}
echo $form;
echo $hidden;