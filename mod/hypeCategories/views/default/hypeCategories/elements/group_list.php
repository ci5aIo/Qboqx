<?php
$page_owner = elgg_get_page_owner_entity();

if ($page_owner instanceof ElggGroup) {
    $container_guid = $page_owner->guid;
    $categories = get_children(get_item_categories($container_guid), $page_owner->guid);
    $module_title = 'hypeCategories:module:title:groups';
}
?>

<?php
if (!empty($categories)) {
?>
    <div class="hj-padding-ten">
        <div class="categories_list">
            <h3><?php echo elgg_echo($module_title); ?></h3>
        <?php
        $category_list = '';

        foreach ($categories as $category) {
            //if ($category->container_guid == $container_guid or $container_guid == 1) {  //this display groups categories in site categories
            if ($category->container_guid == $container_guid) {
                $category_list .= '<li class="closed categoryitem level1">' . elgg_view('profile/icon', array('entity' => $category, 'size' => 'topbar')) . '<a href="' . $category->getURL() . '">' . $category->title . ' <span class="category_count"> (' . get_filed_items_count($category->guid) . ')</span></a>';
                $category_list .= list_children($category->guid, $category->level, true, $container_guid);
                $category_list .= '</li>';
            }
        }

        echo '<ul id="category_sidebar_list">' . $category_list . '</ul>';
        ?>


    </div>
</div>

<?php
    }

    if ($page_owner instanceof ElggGroup
            && elgg_get_context() == 'groups'
            && $page_owner->canEdit()
            && $page_owner->categories_enable != "no") {
        echo '<div id="owner_block_submenu"><div class="submenu_group"><ul><li>
                    <a href="' . $CONFIG->wwwroot . 'pg/category/group/' . $page_owner->guid . '/">' . elgg_echo('hypeCategories:groups:manage') . '</a>
                        </li></ul></div></div>';
    }
?>


<script type="text/javascript">

    $(document).ready(function(){
        $('#category_sidebar_list').treeview({url:''});
    });

</script>