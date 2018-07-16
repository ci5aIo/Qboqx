<?php
$context = elgg_get_context();
elgg_set_context('category');
$categories = elgg_get_entities_from_metadata(array(
    'metadata_names' => 'level',
    'metadata_values' => 1,
    'types' => 'object',
    'subtypes' => 'category',
    'limit' => 9999,
    'order_by_metadata' => array('name' => 'sort', 'direction' => ASC, 'as' => text)
    ));

if (!empty($categories)) {
?>
    <script type="text/javascript">
        $(document).ready(function(){
            $('#hierarchybreadcrumb').menu({
                content: $('#hierarchybreadcrumb').next().html(),
                backLink: true,
                callerOnState: '',
                crumbDefaultText: '',
                flyOut: true
            });

        });

    </script>
    <div id="hypeCategories_topbar" style="float:left; margin-top:-3px;">
        <a tabindex="0" href="#hypeCategories" class="pagelinks" id="hierarchybreadcrumb"><?php echo elgg_echo('hypeCategories:categories') ?></a>
        <div id="hypeCategories" class="hidden">
        <?php
        $category_list = '';

        foreach ($categories as $category) {
            $category_list .= '<li class="categoryitem level1"><a href="' . $category->getURL() . '">' . elgg_view('profile/icon', array('entity' => $category, 'size' => 'topbar')) . $category->title . ' (' . get_filed_items_count($category->guid) . ')</a>';
            $category_list .= list_children($category->guid, 1, true, 1);
            $category_list .= '</li>';
        }

        echo '<ul>' . $category_list . '</ul>';
        ?>
    </div>
</div>
<?php }
elgg_set_context($context);
?>