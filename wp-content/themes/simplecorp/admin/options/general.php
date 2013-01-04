<?php
$options[] = array( "name" => "General",
						"sicon" => "advancedsettings.png",
                        "type" => "heading");


      $options[] = array( "name" => "Your Company Logo",
                        "desc" => "You can use your own company logo. Click to 'Upload Image' button and upload your logo.",
                        "id" => $shortname."_clogo",
                        "std" => "$blogpath/library/images/logo.png",
                        "type" => "upload");
						
	$options[] = array( "name" => "Text as Logo",
                        "desc" => "If you don't upload your own company logo, this text will show up in it's place.",
                        "id" => $shortname."_clogo_text",
                        "std" => "SimpleCorp",
                        "type" => "text");
	$options[] = array( "name" => "Theme Color Scheme",
                                    "id" => $shortname."_colorscheme",
                        "std" => "",
                                    "type" => "select",
                                    "options" => $colorschemes);					
	$options[] = array( "name" => "Custom Favicon",
                        "desc" => "You can use your own custom favicon. Click to 'Upload Image' button and upload your favicon.",
                        "id" => $shortname."_custom_shortcut_favicon",
                        "std" => "",
                        "type" => "upload");
      $options[] = array( "name" => "Enable Page Header Image",
                              "desc" => "By unchecking this the theme will disable sitewide page headers images.",
                              "id" => $shortname."_showpageheader",
                              "std" => "1",
                              "type" => "checkbox");
      $options[] = array( "name" => "Default Page Header Image",
                        "desc" => "You can use your page header image. Click to 'Upload Image' button and upload your image. The image should be 1020 x 200px wide for a proper display.",
                        "id" => $shortname."_pageheaderurl",
                        "std" => "$blogpath/library/images/pageheader.jpg",
                        "type" => "upload");

?>