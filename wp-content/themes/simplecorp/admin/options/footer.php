<?php
	$options[] = array( "name" => "Footer",
   						"sicon" => "footer.png",
						"type" => "heading");

    $options[] = array( "name" => "Footer Copyright Area",
    					"desc" => "You can change the footer copyright area.",
						"id" => $shortname."_footer_copyright",
						"std" => "&copy; Copyright 2012 SimpleCorp by <a href='http://www.site5.com'>Site5.com</a>. All Rights Reserved. ",
						"type" => "textarea");

    $options[] = array( "name" => "Stats",
    					"sicon" => "stats.png",
						"type" => "heading");

    $options[] = array( "name" => "Stat Code",
    					"desc" => "You can use google analytics or other stats code in this area it will appear in the source.",
						"id" => $shortname."_stats",
						"std" => "",
						"type" => "textarea");
?>