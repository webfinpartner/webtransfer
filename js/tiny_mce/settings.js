tinyMCE.init({
		// General options
		mode : "exact",
        elements : "text_edit, elm2",
		theme : "advanced",

                     relative_urls : false,
             convert_urls : false,
             remove_script_host : false,
             remove_linebreaks : true,

		language:"ru",
        plugins : "images,advimage,preview,paste",

		theme_advanced_buttons1 : "bold,italic,underline,formatselect,fontselect,fontsizeselect|,undo,redo,|,link,pasteword,|,image,images,|,forecolor,|,code,|,preview,|,ustifyleft,justifycenter,justifyright,justifyfull",
        theme_advanced_buttons2 : "",

		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		theme_advanced_resizing : true,

		// Example word content CSS (should be your site CSS) this one removes paragraph margins
		content_css : "css/word.css",

		// Drop lists for link/image/media/template dialogs
		template_external_list_url : "lists/template_list.js",
		external_link_list_url : "lists/link_list.js",
		external_image_list_url : "lists/image_list.js",
		media_external_list_url : "lists/media_list.js",

		// Replace values for the template plugin
		template_replace_values : {
			username : "Some User",
			staffid : "991234"
		}
	});
