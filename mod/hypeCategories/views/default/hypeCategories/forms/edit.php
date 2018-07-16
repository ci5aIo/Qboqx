<script type="text/javascript">
    function check_mandatory_fields(form_container) {
        var check = true;
        $('.mandatory', form_container).each(function(){
            if ($(this).val() == '') {
                check = false;
            }
        });
        if (!check) alert('<?php echo elgg_echo('hypeCategories:mandatoryfieldmissing') ?>');
        return check;
    }
</script>
<form action="<?php echo $vars['url']; ?>action/category/save" method="post" enctype="multipart/form-data" onsubmit="return check_mandatory_fields($(this));">

    <?php
    if (!$vars['entity']) {
        $vars['entity']->title = '';
        $vars['entity']->description = '';
        $vars['entity']->guid = NULL;
    }
    if (!$vars['parent']) {
        $vars['parent']->guid = NULL;
    }

    if (!$vars['level']) {
        $vars['level'] = 0;
    }

    if (!$vars['container_guid']) {
        $vars['container_guid'] = 1;
    }

    echo '<div id="formWrapper">';
    echo elgg_view('input/securitytoken');
    echo '<label>' . elgg_echo('hypeCategories:admin:title') . '</label>' . elgg_view('input/text', array('value' => $vars['entity']->title,
        'internalname' => 'title', 'class' => 'mandatory'));

    echo '<label>' . elgg_echo('hypeCategories:admin:description') . '</label>' . elgg_view('input/longtext', array('value' => $vars['entity']->description,
        'internalname' => 'description'));
    if (get_entity($vars['container_guid']) instanceof ElggGroup && elgg_get_plugin_setting('allow_public_in_groups', 'hypeCategories') == 'no') {
        $access_array = get_access_array();
        foreach ($access_array as $access_collection_value) {
            $access_collection = get_access_collection($access_collection_value);
            if ($access_collection->owner_guid == $vars['container_guid']) $access = $access_collection->id;
        }
        echo elgg_view('input/hidden', array('value' => $access, 'internalname' => 'access'));
    } else {
        echo '<p><label>' . elgg_echo('hypeCategories:admin:access') . '</label>' . elgg_view('input/access', array('internalname' => 'access', 'value' => $vars['entity']->access_id)) . '</p>';
    }
    echo '<label>' . elgg_echo('hypeCategories:admin:icon') . '</label>' . elgg_view('input/file', array('internalname' => 'categoryicon')) . '<br>';

    echo elgg_view('input/hidden', array('value' => $vars['entity']->guid, 'internalname' => 'category_guid'));
    echo elgg_view('input/hidden', array('value' => $vars['parent']->guid, 'internalname' => 'parent_guid'));
    echo elgg_view('input/hidden', array('value' => $vars['level'], 'internalname' => 'level'));
    echo elgg_view('input/hidden', array('value' => $vars['container_guid'], 'internalname' => 'container_guid'));

    echo elgg_view('input/submit', array('value' => 'save', 'internalname' => 'save'));
    echo '<a href="javascript:void(0)" class="cancel_button">Cancel</a>';
    echo '</div>';
    ?>
</form>