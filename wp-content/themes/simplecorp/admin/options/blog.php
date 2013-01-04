<?php
    $options[] = array( "name" => "Blog",
    					"sicon" => "blog.png",
						"type" => "heading");
						
	$options[] = array( "name" => "Choose the defined Blog page",
						"desc" => "The blog item page will use the same title description, if defined, as the selected page.",
						"id" => $shortname."_singledesc",
                        "type" => "select",
                        "options" => $options_pages);
	$options[] = array( "name" => "Display Author Box",
						"desc" => "Show Author box on the Blog Post page.",
						"id" => $shortname."_authorbox",
						"std" => "1",
						"type" => "checkbox");
	
	$options[] = array( "name" => "Show Featured Image in Articles Listings",
						"desc" => "Featured Image size must be greater 700x250px for a better display.",
						"id" => $shortname."_showfeaturedimage",
						//"std" => "1",
						"type" => "checkbox");
	
?>