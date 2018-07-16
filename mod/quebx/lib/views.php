<?php
/**
 * Exerpt from engine/lib/views.php.
 */

/**
 * Convenience function for generating a form from a view in a standard location.
 *
 * This function assumes that the body of the form is located at "forms/$action" and
 * sets the action by default to "action/$action".  Automatically wraps the forms/$action
 * view with a <form> tag and inserts the anti-csrf security tokens.
 *
 * @tip This automatically appends elgg-form-action-name to the form's class. It replaces any
 * slashes with dashes (blog/save becomes elgg-form-blog-save)
 *
 * @example
 * <code>echo elgg_view_form('login');</code>
 *
 * This would assume a "login" form body to be at "forms/login" and would set the action
 * of the form to "http://yoursite.com/action/login".
 *
 * If elgg_view('forms/login') is:
 * <input type="text" name="username" />
 * <input type="password" name="password" />
 *
 * Then elgg_view_form('login') generates:
 * <form action="http://yoursite.com/action/login" method="post">
 *     ...security tokens...
 *     <input type="text" name="username" />
 *     <input type="password" name="password" />
 * </form>
 *
 * @param string $action    The name of the action. An action name does not include
 *                          the leading "action/". For example, "login" is an action name.
 * @param array  $form_vars $vars environment passed to the "input/form" view
 * @param array  $body_vars $vars environment passed to the "forms/$action" view
 *
 * @return string The complete form
 */

//namespace quebx;
  
// 04/09/2013 - Created function.  No evidence that it is being used by the application.
function quebx_view_category_form($action, $category, $form_vars = array(), $body_vars = array()) {
	global $CONFIG;

	$defaults = array(
		'action' => $CONFIG->wwwroot . "action/$action/$category",
		'body' => elgg_view("forms/$action/$category", $body_vars)
	);

	$form_class = 'elgg-form-' . preg_replace('/[^a-z0-9]/i', '-', $action);

	// append elgg-form class to any class options set
	if (isset($form_vars['class'])) {
		$form_vars['class'] = $form_vars['class'] . " $form_class";
	} else {
		$form_vars['class'] = $form_class;
	}

	return elgg_view('input/form', array_merge($defaults, $form_vars));
}

/**
 * Returns rendered comments and a comment form for an entity.
 *
 * @tip Plugins can override the output by registering a handler
 * for the comments, $entity_type hook.  The handler is responsible
 * for formatting the comments and the add comment form.
 *
 * @param ElggEntity $entity      The entity to view comments of
 * @param bool       $add_comment Include a form to add comments?
 * @param array      $vars        Variables to pass to comment view
 *
 * @return string|false Rendered comments or false on failure
 */
function quebx_view_comments($entity, $add_comment = true, array $vars = array()) {
	if (!($entity instanceof ElggEntity)) {
		return false;
	}

	$vars['entity'] = $entity;
	$vars['show_add_form'] = $add_comment;
	$vars['class'] = elgg_extract('class', $vars, "{$entity->getSubtype()}-comments");

	$output = elgg_trigger_plugin_hook('comments', $entity->getType(), $vars, false);
	if ($output) {
		return $output;
	} else {
		return elgg_view('page/elements/comments', $vars);
	}
}
/**
 * View one of the quebx sprite icons
 *
 * Shorthand for <span class="quebx-icon quebx-icon-$name"></span>
 *
 * @param string $name  The specific icon to display
 * @param string $class Additional class: float, float-alt, or custom class
 *
 * @return string The html for displaying an icon
 */
function quebx_view_icon($name, $class = '') {

	$icon_class = array("quebx-icon-$name" , $class);

	return elgg_view("output/quebx_icon", array("class" => $icon_class));
}
