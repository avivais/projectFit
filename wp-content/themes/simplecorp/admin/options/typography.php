<?php
    $options[] = array( "name" => "Typography",
    					"sicon" => "font.png",
						"type" => "heading");
						
	$options[] = array( "name" => "Custom Headings Font",
					"desc" => "This theme uses Google Web Font for headings. You can change it by entering the font details in the fields below.",
					"id" => $shortname."_customfontsinfo",
					"std" => "",
					"type" => "info");
						
	$options[] = array( "name" => "Enable Google Font",
						"desc" => "By unchecking this the theme will use default font for headings, Arial.",
						"id" => $shortname."_customtypography",
						"std" => "1",
						"type" => "checkbox");
						
    $options[] = array( "name" => "Headings Google Font Link",
                        "desc" => "Ex: &lt;link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'&gt;",
                        "id" => $shortname."_headingfontlink",
                        "std" => "&lt;link href='http://fonts.googleapis.com/css?family=Dosis' rel='stylesheet' type='text/css'&gt;",
                        "type" => "textarea");

    $options[] = array( "name" => "Headings Google Font Family",
                        "desc" => "Ex: font-family: 'Dosis', sans-serif",
                        "id" => $shortname."_headingfontface",
                        "std" => "font-family: 'Dosis', sans-serif",
                        "type" => "text");					

?>