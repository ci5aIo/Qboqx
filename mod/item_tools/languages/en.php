<?php

return array(
	'item_tools' => "Item Tools",

	'item_tools:item:actions' => 'Actions',

	'item_tools:list:sort:type' => 'Type',
	'item_tools:list:sort:time_created' => 'Time created',
	'item_tools:list:sort:asc' => 'Ascending',
	'item_tools:list:sort:desc' => 'Descending',
	'item_tools:show_more' => 'Show more items',

	// object name
	'item:object:folder' => "Item Folder",

	// menu items
	'item_tools:menu:mine' => "Your places",
	'item_tools:menu:user' => "%s's places",
	'item_tools:menu:group' => "Group places",
	
	// group tool option
	'item_tools:group_tool_option:structure_management' => "Allow management of folders by members",
	
	// views

	// object
	'item_tools:object:items' => "%s item(s) in this place",
	'item_tools:object:no_items' => "No items in this place",

	// input - folder select
	'item_tools:input:folder_select:main' => "Main folder",

	// list
	'item_tools:list:title' => "List places",
	
	'item_tools:list:folder:main' => "Main folder",
	'item_tools:list:items:none' => "No items found in this place",
	'item_tools:list:select_all' => 'Select all',
	'item_tools:list:deselect_all' => 'Deselect all',
	'item_tools:list:download_selected' => 'Download selected',
	'item_tools:list:delete_selected' => 'Delete selected',
	'item_tools:list:alert:not_all_deleted' => 'Not all items could be deleted',
	'item_tools:list:alert:none_selected' => 'No items selected',
	

	'item_tools:list:tree:info' => "Did you know?",
	'item_tools:list:tree:info:1' => "You can drag and drop items on to the places to organize them!",
	'item_tools:list:tree:info:2' => "You can double click on any folder to expand all of its subfolders!",
	'item_tools:list:tree:info:3' => "You can reorder folders by dragging them to their new place in the tree!",
	'item_tools:list:tree:info:4' => "You can move complete folder structures!",
	'item_tools:list:tree:info:5' => "If you delete a folder, you can optionally choose to delete all files!",
	'item_tools:list:tree:info:6' => "When you delete a folder, all subfolders will also be deleted!",
	'item_tools:list:tree:info:7' => "This message is random!",
	'item_tools:list:tree:info:8' => "When you remove a folder, but not it's files, the files will appear at the top level folder!",
	'item_tools:list:tree:info:9' => "A newly added folder can be placed directly in the correct subfolder!",
	'item_tools:list:tree:info:10' => "When uploading or editing a file you can choose in which folder it should appear!",
	'item_tools:list:tree:info:11' => "Dragging of files is only available in the list view, not in the gallery view!",
	'item_tools:list:tree:info:12' => "You can update the access level on all subfolders and even (optional) on all files when editing a folder!",

	'item_tools:list:files:options:sort_title' => 'Sorting',
	'item_tools:list:files:options:view_title' => 'View',

	'item_tools:usersettings:time' => 'Time display',
	'item_tools:usersettings:time:description' => 'Change the way the file/folder time is displayed ',
	'item_tools:usersettings:time:default' => 'Default time display',
	'item_tools:usersettings:time:date' => 'Date',
	'item_tools:usersettings:time:days' => 'Days ago',
	
	// new/edit
	'item_tools:new:title' => "New place",
	'item_tools:edit:title' => "Edit place",
	'item_tools:forms:edit:title' => "Title",
	'item_tools:forms:edit:description' => "Description",
	'item_tools:forms:edit:parent' => "Select a parent folder",
	'item_tools:forms:edit:change_children_access' => "Update access on all subfolders",
	'item_tools:forms:edit:change_items_access' => "Update access on all items in this place (and all interior spaces if selected)",
	'item_tools:forms:browse' => 'Browse..',
	'item_tools:forms:empty_queue' => 'Empty queue',

	'item_tools:folder:delete:confirm_files' => "Do you also wish to delete all items in the removed places",

	// upload
	'item_tools:upload:tabs:single' => "Single item",
	'item_tools:upload:tabs:multi' => "Multi item",
	'item_tools:upload:tabs:zip' => "Zip file",
	'item_tools:upload:form:choose' => 'Choose file',
	'item_tools:upload:form:info' => 'Click browse to upload (multiple) files',
	'item_tools:upload:form:zip:info' => "You can upload a zip file. It will be extracted and each item will be separately imported. Also if you have folders in your zip they will be imported into each specific folder. File types that are not allowed will be skipped.",
	
	// actions
	// edit
	'item_tools:action:edit:error:input' => "Incorrect input to create/edit a place",
	'item_tools:action:edit:error:owner' => "Could not find the owner of the place",
	'item_tools:action:edit:error:folder' => "No place to create/edit",
	'item_tools:action:edit:error:parent_guid' => "Invalid parent container, the parent container can't be the place itself",
	'item_tools:action:edit:error:save' => "Unknown error occured while saving the place",
	'item_tools:action:edit:success' => "Place successfully created/edited",

	'item_tools:action:move:parent_error' => "Can\'t drop the folder in itself.",
	
	// delete
	'item_tools:actions:delete:error:input' => "Invalid input to delete a file folder",
	'item_tools:actions:delete:error:entity' => "The given GUID could not be found",
	'item_tools:actions:delete:error:subtype' => "The given GUID is not a file folder",
	'item_tools:actions:delete:error:delete' => "An unknown error occured while deleting the file folder",
	'item_tools:actions:delete:success' => "The file folder was deleted successfully",

	//errors
	'item_tools:error:pageowner' => 'Error retrieving page owner.',
	'item_tools:error:nofilesextracted' => 'There were no allowed files found to extract.',
	'item_tools:error:cantopenfile' => 'Zip file couldn\'t be opened (check if the uploaded file is a .zip file).',
	'item_tools:error:nozipfilefound' => 'Uploaded file is not a .zip file.',
	'item_tools:error:nofilefound' => 'Choose a file to upload.',

	//messages
	'item_tools:error:fileuploadsuccess' => 'Zip file uploaded and extracted successfully.',
	
	// move
	'item_tools:action:move:success:file' => "The file was moved successfully",
	'item_tools:action:move:success:folder' => "The folder was moved successfully",
	
	// buld delete
	'item_tools:action:bulk_delete:success:files' => "Successfully removed %s files",
	'item_tools:action:bulk_delete:error:files' => "There was an error while removing some files",
	'item_tools:action:bulk_delete:success:folders' => "Successfully removed %s folders",
	'item_tools:action:bulk_delete:error:folders' => "There was an error while removing some folders",
	
	// reorder
	'item_tools:action:folder:reorder:success' => "Successfully reordered the folder(s)",
	
	//settings
	'item_tools:settings:allowed_extensions' => 'Allowed extensions (comma seperated)',
	'item_tools:settings:use_folder_structure' => 'Use folder structure',
	'item_tools:settings:sort:default' => 'Default folder sorting options',
	'item_tools:settings:list_length' => 'How many files to show in the listing',
	'item_tools:settings:list_length:unlimited' => 'Unlimited',

	'file:type:application' => 'Application',
	'file:type:text' => 'Text',

	// widgets
	// file tree
	'widgets:file_tree:title' => "Folders",
	'widgets:file_tree:description' => "Showcase your File folders",
	
	'widgets:file_tree:edit:select' => "Select which folder(s) to display",
	'widgets:file_tree:edit:show_content' => "Show the content of the folder(s)",
	'widgets:file_tree:no_folders' => "No folders configured",
	'widgets:file_tree:no_files' => "No files configured",
	'widgets:file_tree:more' => "More file folders",

	'widget:file:edit:show_only_featured' => 'Show only featured files',
	
	'widget:item_tools:show_file' => 'Feature file (widget)',
	'widget:item_tools:hide_file' => 'Unfeature file',

	'widgets:item_tools:more_files' => 'More files',
	
	// Group files
	'widgets:group_files:description' => "Show the latest group files",
	
	// index_file
	'widgets:index_file:description' => "Show the latest files on your community",

);
	