<?php

return array(
	'item_tools' => "File Tools",

	'item_tools:file:actions' => 'Acciones',

	'item_tools:list:sort:type' => 'Tipo',
	'item_tools:list:sort:time_created' => 'Hora de creación',
	'item_tools:list:sort:asc' => 'Ascendente',
	'item_tools:list:sort:desc' => 'Descendente',

	// object name
	'item:object:folder' => "Carpeta de archivos",

	// menu items
	'item_tools:menu:mine' => "Tus carpetas",
	'item_tools:menu:user' => "carpetas de %s",
	'item_tools:menu:group' => "Carpetas de grupo",
	
	// group tool option
	'item_tools:group_tool_option:structure_management' => "Permitir la gestión de carpetas por los miembros",
	
	// views

	// object
	'item_tools:object:files' => "%s fichero(s) en esta carpeta",
	'item_tools:object:no_files' => "No hay ficheiros en esta carpeta",

	// input - folder select
	'item_tools:input:folder_select:main' => "Carpeta principal",

	// list
	'item_tools:list:title' => "Listar carpetas",
	
	'item_tools:list:folder:main' => "Carpeta principal",
	'item_tools:list:files:none' => "No se encontraron ficheros en esta carpeta",
	'item_tools:list:select_all' => 'Seleccionar todo',
	'item_tools:list:deselect_all' => 'Des-seleccionar todo',
	'item_tools:list:download_selected' => 'Descargar seleccionados',
	'item_tools:list:delete_selected' => 'Eliminar seleccionados',
	'item_tools:list:alert:not_all_deleted' => 'No se pueden borrar todos los ficheros',
	'item_tools:list:alert:none_selected' => 'No se seleccionó ningún elemento',
	

	'item_tools:list:tree:info' => "Lo sabías?",
	'item_tools:list:tree:info:1' => "Puedes arrastrar y soltar ficheros en las carpetas para organizarlos!",
	'item_tools:list:tree:info:2' => "Puedes hacer doble click en cualquier carpeta para expandir todas sus subcarpetas!",
	'item_tools:list:tree:info:3' => "¡Puedes reordenar las carpetas arrastrándolas hasta su nuevo lugar en el árbol!",
	'item_tools:list:tree:info:4' => "¡Puedes mover estructuras de carpetas completas!",
	'item_tools:list:tree:info:5' => "¡Si borras una carpeta, puedes escoger opcionalmente eliminar todos los archivos!",
	'item_tools:list:tree:info:6' => "¡Cuando borras una carpeta,  se borrarán también todas sus subcarpetas!",
	'item_tools:list:tree:info:7' => "¡Este mensaje es aleatorio!",
	'item_tools:list:tree:info:8' => "¡Cuando eliminas una carpeta, pero no sus archivos, los archivos aparecerán en la carpeta raíz!",
	'item_tools:list:tree:info:9' => "¡Una carpeta nueva se puede poner directamente en la subcarpeta correcta!",
	'item_tools:list:tree:info:10' => "¡Cuando subes o editas un fichero puedes escoger en que carpeta aparecerá!",
	'item_tools:list:tree:info:11' => "¡Arrastrar ficheros sólo está disponible en la vista de lista, no en la vista de galería!",
	'item_tools:list:tree:info:12' => "¡Puedes actualizar el nivel de acceso en todas las subcarpetas y también (opcional) en todos los ficheros cuando editas una carpeta!",

	'item_tools:list:files:options:sort_title' => 'Ordenando',
	'item_tools:list:files:options:view_title' => 'Ver',

	'item_tools:usersettings:time' => 'Visualización de la hora',
	'item_tools:usersettings:time:description' => 'Cambiar la manera en que se muestra la hora de un fichero/carpeta',
	'item_tools:usersettings:time:default' => 'Visualización de hora predeterminada',
	'item_tools:usersettings:time:date' => 'Fecha',
	'item_tools:usersettings:time:days' => 'Días atrás',
	
	// new/edit
	'item_tools:new:title' => "Nueva carpeta",
	'item_tools:edit:title' => "Editar carpeta",
	'item_tools:forms:edit:title' => "Título",
	'item_tools:forms:edit:description' => "Descripción",
	'item_tools:forms:edit:parent' => "Seleccionar la carpeta superior",
	'item_tools:forms:edit:change_children_access' => "Actualizar acceso en todas las subcarpetas",
	'item_tools:forms:edit:change_files_access' => "Actualizar acceso en todos los ficheiros en esta carpeta (y todas las subcarpetas si está seleccionado)",
	'item_tools:forms:browse' => 'Navegar..',
	'item_tools:forms:empty_queue' => 'Cola vacía',

	'item_tools:folder:delete:confirm_files' => "Quieres eliminar todos los ficheiros de las (sub)carpetas eliminadas",

	// upload
	'item_tools:upload:tabs:single' => "Fichero único",
	'item_tools:upload:tabs:multi' => "Multi-fichero",
	'item_tools:upload:tabs:zip' => "Fichero Zip",
	'item_tools:upload:form:choose' => 'Escoger fichero',
	'item_tools:upload:form:info' => 'Pulsa navegar para subir (multiples) archivos',
	'item_tools:upload:form:zip:info' => "Puedes subir un fichero zip. Se extraerá el contenido y se importará cada fichero por separado. Si  tienes carpetas en tu fichero zip también se importarán en su carpeta específica. Se saltarán los ficheros de tipos no permitidos.",
	
	// actions
	// edit
	'item_tools:action:edit:error:input' => "Entrada incorrecta para crear/editar una carpeta",
	'item_tools:action:edit:error:owner' => "No se pudo encontrar el propietario de la carpeta",
	'item_tools:action:edit:error:folder' => "No hai ningunha carpeta para crear/editar",
	'item_tools:action:edit:error:parent_guid' => "Carpeta superior inválida, la carpeta superior no puede ser la propia carpeta.",
	'item_tools:action:edit:error:save' => "Error desconocido al salvar la carpeta",
	'item_tools:action:edit:success' => "La carpeta se creó/editó correctamente",

	'item_tools:action:move:parent_error' => "No se puede soltar la carpeta sobre si misma",
	
	// delete
	'item_tools:actions:delete:error:input' => "Entrada incorrecta para borrar la carpeta",
	'item_tools:actions:delete:error:entity' => "No se pudo encontrar el GUID proporcionado",
	'item_tools:actions:delete:error:subtype' => "El GUID proporcionado no es una carpeta",
	'item_tools:actions:delete:error:delete' => "Ocurrió un error desconocido al eliminar la carpeta",
	'item_tools:actions:delete:success' => "La carpeta se eliminó correctamente",

	//errors
	'item_tools:error:pageowner' => 'Error al obtener el propietario de la página.',
	'item_tools:error:nofilesextracted' => 'No se encontraron archivos para extraer.',
	'item_tools:error:cantopenfile' => 'No se pudo abrir el archivo zip (comprueba que el archivo que has subido es un archivo .zip)',
	'item_tools:error:nozipfilefound' => 'El archivo subido no es un archivo .zip.',
	'item_tools:error:nofilefound' => 'Escoge un archivo para subir.',

	//messages
	'item_tools:error:fileuploadsuccess' => 'El archivo zip se subió y se extrajo correctamente.',
	
	// move
	'item_tools:action:move:success:file' => "El fichero se movió correctamente",
	'item_tools:action:move:success:folder' => "La carpeta se movió correctamente",
	
	// buld delete
	'item_tools:action:bulk_delete:success:files' => "Se han eliminado %s archivos correctamente",
	'item_tools:action:bulk_delete:error:files' => "Hubo un error al elminar algunos archivos",
	'item_tools:action:bulk_delete:success:folders' => "Se han eliminado %s carpetas correctamente",
	'item_tools:action:bulk_delete:error:folders' => "Hubo un error al eliminar algunas carpetas",
	
	// reorder
	'item_tools:action:folder:reorder:success' => "Se reordenaron las carpetas correctamente",
	
	//settings
	'item_tools:settings:allowed_extensions' => 'Extensiones permitidas (separadas por comas)',
	'item_tools:settings:use_folder_structure' => 'Usar estructura de carpetas',
	'item_tools:settings:sort:default' => 'Opciones de ordenación de carpetas',

	'file:type:application' => 'Aplicación',
	'file:type:text' => 'Texto',

	// widgets
	// file tree
	'widgets:file_tree:title' => "Carpetas",
	'widgets:file_tree:description' => "Escaparate de tus carpetas",
	
	'widgets:file_tree:edit:select' => "Seleccionar que carpeta(s) mostrar",
	'widgets:file_tree:edit:show_content' => "Mostrar el contenido de las carpetas",
	'widgets:file_tree:no_folders' => "No se configuraron carpetas",
	'widgets:file_tree:no_files' => "No se configuraron archivos",
	'widgets:file_tree:more' => "Más carpetas",

	'widget:file:edit:show_only_featured' => 'Mostrar solo archivos destacados',
	
	'widget:item_tools:show_file' => 'Archivo destacado (widget)',
	'widget:item_tools:hide_file' => 'Archivo sin destacar',

	'widgets:item_tools:more_files' => 'Más archivos',
	
	// Group files
	'widgets:group_files:description' => "Mostrar los últimos archivos del grupo",
	
	// index_file
	'widgets:index_file:description' => "Mostrar los últimos archivos en tu comunidad",

);
