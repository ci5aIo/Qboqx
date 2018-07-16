<?php
action_gatekeeper();
gatekeeper();

$category_guid = get_input('category_guid');
$category = get_entity($category_guid);

$parent_guid = get_input('parent_guid');
$parent = get_entity($parent_guid);

$level = (int) get_input('level');

$action = get_input('action_type');

$container_guid = (int) get_input('container_guid');
$container = get_entity($container_guid);

if (!$container->canEdit()) {
    echo elgg_echo('edifide:manage:noprivileges');
    die();
}
// Delete category
if ($action == 'delete') {
    if ($category instanceof ElggObject && $category->getSubtype() == 'category') {
        if ($category->canEdit()) {
            if (is_array(get_children($category->guid))) {
                echo elgg_echo('hypeCategories:manage:delete:error:subcategories');
            } elseif ((int) get_filed_items_count($category->guid) > 0) {
                echo elgg_echo('hypeCategories:manage:delete:error:items');
            } else {
                echo 'true';
                $category->delete();
            }
        } else {
            echo elgg_echo('hypeCategories:manage:noprivileges');
        }
    } else {
        echo elgg_echo('hypeCategories:manage:delete:error:cannotperform');
    }
}

if ($action == 'get_details') {
    if ($category instanceof ElggObject && $category->getSubtype() == 'category') {
        echo elgg_view_entity($category);
    }
}

if ($action == 'edit') {
    if ($category instanceof ElggObject && $category->getSubtype() == 'category') {
        if ($category->canEdit()) {
            echo elgg_view('hypeCategories/forms/edit', array('entity' => $category, 'parent' => $parent, 'level' => $level, 'container_guid' => $container_guid));
        } else {
            echo elgg_echo('hypeCategories:manage:noprivileges');
        }
    }
}

if ($action == 'new') {
    echo elgg_view('hypeCategories/forms/edit', array('parent' => $parent, 'level' => $level, 'container_guid' => $container_guid));
}

die();
?>