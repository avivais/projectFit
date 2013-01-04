<?php
	$options[] = array( "name" => "Homepage",
	                    "sicon" => "user-home.png",
	                    "type" => "heading");
					
	$options[] = array( "name" => "Display Blurb on Homepage",
						"id" => $shortname."_blurbhome",
						"std" => "1",
						"type" => "checkbox");
						
	$options[] = array( "name" => "Blurb Content",
                        "id" => $shortname."_blurb",
                        "std" => "Welcome to Our Small Agency. We specialize in <strong>Web Design</strong> and <strong>Development</strong>. Check out our outstanding portfolio, and get in touch with Us!",
						"class" => "sectionlast",
                        "type" => "textarea");
						
	$options[] = array( "name" => "Display Content Boxes on Homepage",
						"id" => $shortname."_homecontent",
						"std" => "1",
						"type" => "checkbox");
						
	$options[] = array( "name" => "Content Box 1 Title",
                        "id" => $shortname."_homecontent1title",
                        "std" => "Awesome Features",
                        "type" => "text");
						
	$options[] = array( "name" => "Content Box 1 Text",
                        "id" => $shortname."_homecontent1",
                        "std" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.",
                        "type" => "textarea");
						
	$options[] = array( "name" => "Content Box 1 Image",
                        "desc" => "Click to 'Upload Image' button and upload Content Box 1 image.",
                        "id" => $shortname."_homecontent1img",
                        "std" => "$blogpath/library/images/sampleimages/featured-img-01.png",
                        "type" => "upload");
						
	$options[] = array( "name" => "Content Box 1 URL",
                        "id" => $shortname."_homecontent1url",
                        "std" => "#",
						"class" => "sectionlast",
                        "type" => "text");
					
	$options[] = array( "name" => "Content Box 2 Title",
                        "id" => $shortname."_homecontent2title",
                        "std" => "Browser Compatibility",
                        "type" => "text");

	$options[] = array( "name" => "Content Box 2 Text",
                        "id" => $shortname."_homecontent2",
                        "std" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.",
                        "type" => "textarea");
						
	$options[] = array( "name" => "Content Box 2 Image",
                        "desc" => "Click to 'Upload Image' button and upload Content Box 2 image.",
                        "id" => $shortname."_homecontent2img",
                        "std" => "$blogpath/library/images/sampleimages/featured-img-02.png",
                        "type" => "upload");
						
	$options[] = array( "name" => "Content Box 2 URL",
                        "id" => $shortname."_homecontent2url",
                        "std" => "#",
						"class" => "sectionlast",
                        "type" => "text");	

	$options[] = array( "name" => "Content Box 3 Title",
                        "id" => $shortname."_homecontent3title",
                        "std" => "Works on Mobile Devices",
                        "type" => "text");
	
	$options[] = array( "name" => "Content Box 3",
                        "id" => $shortname."_homecontent3",
                        "std" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.",
                        "type" => "textarea");
						
	$options[] = array( "name" => "Content Box 3 Image",
                        "desc" => "Click to 'Upload Image' button and upload Content Box 3 image.",
                        "id" => $shortname."_homecontent3img",
                        "std" => "$blogpath/library/images/sampleimages/featured-img-03.png",
                        "type" => "upload");
						
	$options[] = array( "name" => "Content Box 3 URL",
                        "id" => $shortname."_homecontent3url",
                        "std" => "#",
						"class" => "sectionlast",
                        "type" => "text");
	
	$options[] = array( "name" => "Content Box 4 Title",
                        "id" => $shortname."_homecontent4title",
                        "std" => "Full Documentation",
                        "type" => "text");
	
	$options[] = array( "name" => "Content Box 4",
                        "id" => $shortname."_homecontent4",
                        "std" => "Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore.",
                        "type" => "textarea");
						
	$options[] = array( "name" => "Content Box 4 Image",
                        "desc" => "Click to 'Upload Image' button and upload Content Box 4 image.",
                        "id" => $shortname."_homecontent4img",
                        "std" => "$blogpath/library/images/sampleimages/featured-img-04.png",
                        "type" => "upload");
						
	$options[] = array( "name" => "Content Box 4 URL",
                        "id" => $shortname."_homecontent4url",
                        "std" => "#",
						"class" => "sectionlast",
                        "type" => "text");
						
	$options[] = array( "name" => "Display Portfolio on Homepage",
						"desc" => "do you want to display portfolio section on homepage ?",
						"id" => $shortname."_portfoliohome",
						"std" => "1",
						"type" => "checkbox");
	
	$options[] = array( "name" => "Portfolio section title",
                        "id" => $shortname."_portfoliohometitle",
                        "std" => "Some of Our Featured Projects",
                        "type" => "text");
						
	$options[] = array( "name" => "Portfolio section button title",
                        "id" => $shortname."_portfoliohomebuttontitle",
                        "std" => "View full portfolio",
                        "type" => "text");
	
	$options[] = array( "name" => "Portfolio section button URL",
                        "id" => $shortname."_portfoliohomebuttonurl",
                        "std" => "#",
                        "type" => "text");
						

?>