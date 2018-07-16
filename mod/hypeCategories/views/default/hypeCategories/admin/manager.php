<?php
global $CONFIG;
gatekeeper();

$container_guid = elgg_get_page_owner_guid();
$level = (int) get_input('level');
$parent = get_input('parent');

if (empty($container_guid)) {

    $container_guid = 1;
    if (empty($level)) {
        $level = 1;
    } else {
        $level = $level + 1;
    }

    if (empty($parent)) {
        $global_categories = elgg_get_entities_from_metadata(array('metadata_name' => 'level', 'metadata_value' => 1, 'type' => 'object', 'subtype' => 'category', 'limit' => 9999,));
    } else {
        $global_categories = get_children($parent);
    }
} else {

    if (empty($parent)) {
        $parent = get_item_categories($container_guid);
        if (empty($parent)) {
            system_message(elgg_echo('hypeCategories:manage:assignfirst'));
            forward($_SERVER['HTTP_REFERER']);
        } else {
            $global_categories = get_children($parent);
        }
    } else {
        $global_categories = get_children($parent);
    }

    if (empty($level)) {
        $level = (int) get_entity($parent)->level + 1;
    } else {
        $level = $level + 1;
    }
}
?>
<script type="text/javascript">
    $(document).ready(function(){
        //$('.ajax_loader').each().hide();
        $('.category_wrapper').each(function(){
            var container         = $(this),
            bar               = $('#category_bar', container),
            button_toggle     = $('a.toggle_category_bar', container),
            title             = $('a.category_title', container),
            button_delete     = $('a.delete_button', container),
            button_edit       = $('a.edit_button', container),
            button_subs       = $('a.subcategories_button', container),
            category_owner    = $('a.category_owner', container),
            category_container = $('a.category_container', container),
            category_access   = $('a.category_access', container),
            details           = $('#category_description', container),
            ajax_loader       = $('.ajax_loader', container),
            ajax_result       = $('#ajax_result', container),
            add_new           = $('a.new_category_title', container),

            category_guid     = $(this).attr('value'),
            parent_guid       = '<?php echo $parent ?>',
            level             = <?php echo $level ?>,
            container_guid    = <?php echo $container_guid ?>,

            owner             = $('p.owner', category_owner).text(),
            owner_name        = $('p.owner_name', category_owner).text(),
            container         = $('p.container', category_container).text(),
            container_name    = $('p.container_name', category_container).text(),
            access            = $('p.access', category_access).text(),
            access_name       = $('p.access_name', category_access).text();


            button_subs.attr('href', '?parent='+category_guid+'&level='+level);

            category_owner.attr('title', '<?php echo elgg_echo("hypeCategories:manage:ownedby") ?> '+owner+': '+owner_name);
            var background = 'url(<?php echo $vars['url'] ?>mod/hypeCategories/graphics/hypeCategories/ui/owner_'+owner+'.png) no-repeat';
            category_owner.css({'background':background});

            category_container.attr('title', '<?php echo elgg_echo("hypeCategories:manage:containedby") ?> '+container+': '+container_name);
            background = 'url(<?php echo $vars['url'] ?>mod/hypeCategories/graphics/hypeCategories/ui/container_'+container+'.png) no-repeat';
            category_container.css({'background':background});

            category_access.attr('title', access_name);
            if (parseInt(access) <= 2) {
                background = 'url(<?php echo $vars['url'] ?>mod/hypeCategories/graphics/hypeCategories/ui/access'+access+'.png) no-repeat';
            } else {
                background = 'url(<?php echo $vars['url'] ?>mod/hypeCategories/graphics/hypeCategories/ui/accessG.png) no-repeat';
            }
            category_access.css({'background':background});

            title.click(function(){
                details.toggle();
                if (!ajax_result.html()) {
                    ajax_loader.show();
                    $.ajax ({
                        url: '<?php echo elgg_add_action_tokens_to_url($vars['url'] . 'action/category/actions') ?>',
                        type: 'POST',
                        dataType: 'html',
                        data: {
                            category_guid: category_guid,
                            action_type: 'get_details',
                            container_guid: container_guid
                        },
                        success: function(data) {
                            ajax_loader.hide();
                            ajax_result.html(data);
                        }

                    });
                };
            });
      
            button_delete.click(function(){
                $.ajax ({
                    beforeSend: function(){
                        var confirm_delete = confirm('<?php echo elgg_echo('hypeCategories:action:confirm:delete') ?>');
                        return confirm_delete;
                    },
                    url: '<?php echo elgg_add_action_tokens_to_url($vars['url'] . 'action/category/actions') ?>',
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        category_guid: category_guid,
                        action_type: 'delete',
                        container_guid: container_guid
                    },
                    success: function(data) {
                        if (data == 'true') {
                            bar.hide();
                            alert('<?php echo elgg_echo('hypeCategories:action:delete:success') ?>');
                        } else {
                            alert(data);
                        }
                    }

                });
            });

            button_edit.click(function(){
                ajax_result.empty();
                details.show();
                ajax_loader.show();
                $.ajax ({
                    url: '<?php echo elgg_add_action_tokens_to_url($vars['url'] . 'action/category/actions') ?>',
                    type: 'POST',
                    dataType: 'html',
                    data: {
                        category_guid: category_guid,
                        parent_guid: parent_guid,
                        level: level,
                        action_type: 'edit',
                        container_guid: container_guid
                    },
                    success: function(data) {
                        ajax_loader.hide();
                        ajax_result.html(data);
                        var button_cancel     = $('a.cancel_button', ajax_result);
                        button_cancel.click(function() {
                            details.toggle();
                            ajax_result.empty();
                            title.trigger('click');
                        });
                    }
                });
            });

            add_new.click(function(){
                details.toggle();
                if (!ajax_result.html()) {
                    ajax_loader.show();
                    $.ajax ({
                        url: '<?php echo elgg_add_action_tokens_to_url($vars['url'] . 'action/category/actions') ?>',
                        type: 'POST',
                        dataType: 'html',
                        data: {
                            category_guid: category_guid,
                            parent_guid: parent_guid,
                            level: level,
                            action_type: 'new',
                            container_guid: container_guid
                        },
                        success: function(data) {
                            ajax_loader.hide();
                            ajax_result.html(data);
                            var button_cancel     = $('a.cancel_button', ajax_result);
                            button_cancel.click(function() {
                                details.toggle();
                                ajax_result.empty();
                                title.trigger('click');
                            });
                        }
                    });
                }
            });
        });
    });
</script>


<?php
if (elgg_get_context() == 'admin') {
    echo elgg_view_title(elgg_echo('hypeCategories:admin:settings'));
} else {
    echo elgg_view_title(elgg_echo('hypeCategories:group:settings'));
}
?>



<div class="contentWrapper">

    <div class="categories_breadcrumbs">
        <?php
        $check = true;
        $breadcrumbs = '<b>';

        while ($check == true) {
            $parent = get_entity($parent);
            if ($parent->container_guid !== $container_guid && $container_guid !== 1) {
                $check = false;
            }
            if ($parent instanceof ElggObject) {
                $breadcrumbs = '<a href="manage.php?parent=' . $parent->guid . '&level=' . $parent->level . '">' . $parent->title . '</a> >> ' . $breadcrumbs;
                $parent = get_parent($parent->guid)->guid;
            } else {
                $check = false;
            }
        }
        if (elgg_get_context() == 'admin') {
            $root_echo = 'hypeCategories:site:root';
        } else {
            $root_echo = 'hypeCategories:group:root';
        }
        echo '<a href="manage.php">' . elgg_echo($root_echo) . '</a>: ' . $breadcrumbs . '</b><br>';
        ?>
    </div>
    <?php
        foreach ($global_categories as $global_category) {
            if ($global_category->container_guid == $container_guid or elgg_get_context() == 'admin') {
    ?>
                <div class="category_wrapper" value="<?php echo $global_category->guid ?>">
                    <div id ="category_bar">
                        <div class="left_side">
                <?php echo elgg_view('profile/icon', array('entity' => $global_category, 'size' => 'topbar')); ?>
                <a href="javascript:void(0)" class="category_title"><?php echo $global_category->title ?></a>
                <div class="clearfloat"></div>
            </div>
            <div class="right_side">
                <a href="javascript:void(0)" title="Delete this category" class="delete_button"></a>
                <a href="javascript:void(0)" title="Edit this category" class="edit_button"></a>
                <a href="javascript:void(0)" title="Manage subcategories" class="subcategories_button"></a>

                <a href="javascript:void(0)" title="" class="category_access">
                    <p class="access" style="display:none"><?php
                echo $global_category->access_id;
                ?></p>
                    <p class="access_name" style="display:none"><?php
                        $access_level = get_write_access_array();
                        echo $access_level[$global_category->access_id];
                ?></p>
                </a>
                <a href="javascript:void(0)" title="" class="category_container">
                    <p class="container" style="display:none"><?php
                        if (get_entity($global_category->container_guid) instanceof ElggGroup) {
                            echo 'Group';
                        } else {
                            echo 'Site';
                        }
                ?></p>
                    <p class="container_name" style="display:none"><?php
                        echo get_entity($global_category->container_guid)->name;
                ?></p>
                </a>
                <a href="javascript:void(0)" title="" class="category_owner">
                    <p class="owner" style="display:none"><?php
                        if (get_entity($global_category->owner_guid)->isAdmin()) {
                            echo 'Admin';
                        } else {
                            echo 'User';
                        }
                ?></p>
                    <p class="owner_name" style="display:none"><?php
                        echo get_entity($global_category->owner_guid)->name;
                ?></p>
                </a>

                <div class="clearfloat"></div>
            </div>
            <div class="clearfloat"></div>
        </div>
        <div id="category_description" style="display:none">
            <?php echo elgg_view('ajax/loader'); ?>
                        <div id="ajax_result"></div>
                    </div>
                </div>

    <?php
                    }
                }
    ?>
                <div class="category_wrapper" value="new">
                    <div id ="category_bar">
                        <div class="left_side">
                            <a href="javascript:void(0)" class="new_category_title"><?php echo elgg_echo('hypeCategories:action:addnew') ?></a>
                            <div class="clearfloat"></div>
                        </div>
                        <div class="clearfloat"></div>
                    </div>
                    <div id="category_description" style="display:none">
            <?php echo elgg_view('ajax/loader'); ?>
            <div id="ajax_result"></div>
        </div> 
    </div>
</div>