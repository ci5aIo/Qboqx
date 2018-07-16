<?php
$entity      = $vars['entity'];
$referrer    = $entity->geturl();
$entity_guid = $entity->getGUID();


echo elgg_view('input/hidden',array('name'=>'entity_guid','value'=>$entity_guid));

//echo "<div class='rTable' style='width:100%;padding: 0px 0px;border: 1px solid #999999'>
echo "<div class='rTable' style='width:100%;padding: 0px 0px;border:none'>
					    <div class='rTableRow'>
							<div class='rTableCell' style='width:20%;padding: 0px 0px'>".elgg_view('input/date', array(
														'name' => 'item[date][]',
									                    'class'=> 'rTableform',
									                    'value'=> 'Date',
									                    'onfocus' => "if (this.value=='Date') this.value=''",
									                    'onblur'  => "if (this.value=='') this.value='Date'",
									                    'onsubmit'=> "if (this.value=='Date') this.value=''",
													))."</div>
							<div class='rTableCell' style='width:55%;padding: 0px 0px'>".elgg_view('input/text', array(
														'name'    => 'item[title][]',
									                    'class'   => 'rTableform',
									                    'value'   => 'Title',
									                    'onfocus' => "if (this.value=='Title') this.value=''",
									                    'onblur'  => "if (this.value=='') this.value='Title'",
									                    'onsubmit'=> "if (this.value=='Title') this.value=''",
													))."</div>
							<div class='rTableCell' style='width:15%;padding: 0px 0px'>".elgg_view('input/dropdown', array(
														'name' => 'item[jot_type][]',
									                    'class'=> 'rTableform',
									                    'style'=> 'color: #666',
									                    'options'=>array('jot type...','receipt', 'observation', 'insight'),
									                    'onfocus' => "if (this.value=='jot type...') this.value=''",
									                    'onblur'  => "if (this.value=='') this.value='jot type...'",
									                    'onsubmit'=> "if (this.value=='jot type...') this.value=''",
													))."</div>
							<div class='rTableCell' style='width:10%;padding: 0px 0px'>".elgg_view('input/text', array(
														'name' => 'item[value][]',
									                    'class'=> 'rTableform',
									                    'value'=> '$',
									                    'onfocus' => "if (this.value=='$') this.value=''",
									                    'onblur'  => "if (this.value=='') this.value='$'",
									                    'onsubmit'=> "if (this.value=='$') this.value=''",
													))."</div>
						</div>
						<div class='rTableRow'>
							<div class='rTableCell' style='width:20%;padding: 0px 0px;border-width: 0px 1px 1px 0px'>".elgg_view('input/text', array(
														'name' => 'item[attachment][]',
									                    'class'=> 'rTableform',
									                    'value'=> 'Attachment',
									                    'onfocus' => "if (this.value=='Attachment') this.value=''",
									                    'onblur'  => "if (this.value=='') this.value='Attachment'",
									                    'onsubmit'=> "if (this.value=='Attachment') this.value=''",
													))."</div>
							<div class='rTableCell' style='width:55%;padding: 0px 0px'>
								<div class='rTable' style='width:100%'>
									<div class='rTableRow'>
										<div class='rTableCell' style='width:60%;padding: 0px 0px'>".elgg_view('input/text', array(
														'name' => 'item[operative][]',
									                    'class'=> 'rTableform',
									                    'value'=> 'Operative',
									                    'onfocus' => "if (this.value=='Operative') this.value=''",
									                    'onblur'  => "if (this.value=='') this.value='Operative'",
									                    'onsubmit'=> "if (this.value=='Operative') this.value=''",
													))."</div>
										<div class='rTableCell' style='width:30%;padding: 0px 0px'>".elgg_view('input/text', array(
														'name' => 'item[labels][]',
									                    'class'=> 'rTableform',
									                    'value'=> 'Labels',
									                    'onfocus' => "if (this.value=='Labels') this.value=''",
									                    'onblur'  => "if (this.value=='') this.value='Labels'",
									                    'onsubmit'=> "if (this.value=='Labels') this.value=''",
													))."</div>
										<div class='rTableCell' style='width:10%;padding: 0px 0px'>".elgg_view('input/text', array(
														'name' => 'item[dunno][]',
									                    'class'=> 'rTableform',
													))."</div>
									</div>
								</div>
							</div>
							<div class='rTableCell' style='width:15%;padding: 0px 0px;'>".elgg_view('input/submit', array(
																									'value' => 'save', 
																									'name' => 'submit', 
																									'class' => 'elgg-button-submit-element'))."</div>
							<div class='rTableCell' style='width:10%;padding: 0px 0px;'>[split]</div>
						</div>
					</div>";