<?php
    $options[] = array( "name" => "Contact",
    					"sicon" => "mail.png",
                        "type" => "heading");
					
	$options[] = array( "name" => "Contact Address",
                        "id" => $shortname."_contact_address",
                        "std" => "75 Ninth Avenue 2nd and 4th Floors New York, NY 10011",
                        "type" => "text");

    $options[] = array( "name" => "Contact Phone",
                        "id" => $shortname."_contact_phone",
                        "std" => "+1 212-565-0000",
                        "type" => "text");
						
	$options[] = array( "name" => "Contact Fax",
                        "id" => $shortname."_contact_fax",
                        "std" => "+1 212-565-0000",
                        "type" => "text");
						
	$options[] = array( "name" => "Contact E-Mail",
                        "id" => $shortname."_contact_email",
                        "std" => "info@yoursite.com",
                        "type" => "text");

    $options[] = array( "name" => "Contact Map",
                        "id" => $shortname."_contact_map",
                        "std" => "",
                        "type" => "textarea");

?>