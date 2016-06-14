<?php
/**
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2014, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

return 
	array(
		//"base_url" => "http://localhost/hybridauth-git/hybridauth/", 
//		"base_url" => "http://dopmull.ru/hybridauth-master/hybridauth/", 
		"base_url" => "/account/hybridEndpoint", 
           // C:\Users\User\Documents\NetBeansProjects\wtf\application\libraries\hybridauth\hybridauth\index.php
            //hybridauth\hybridauth\index.php

		"providers" => array ( 
			// openid providers
			"OpenID" => array (
				"enabled" => true
			),

			"Yahoo" => array ( 
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" ),
			),

			"AOL"  => array ( 
				"enabled" => true 
			),

			"Google" => array ( 
				"enabled" => true,
				"keys"    => array ( 
                                    "id" => "556330387880.apps.googleusercontent.com",
                                    "secret" => "" 
                                    ), 
			),

			"Facebook" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "526472357444368", "secret" => "" ),
				"trustForwarded" => false
			),

			"Twitter" => array ( 
				"enabled" => true,
				"keys"    => array ( "key" => "9UDBRrzgsm9keKVhbrN81UkCs", "secret" => "" ) 
			),

			// windows live
			"Live" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" ) 
			),

			"LinkedIn" => array ( 
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" ) 
			),
                    
			"Vkontakte" => array ( 
				"enabled" => true,
				"keys"    => array ( "id" => "4581681", "secret" => "" ) 
			),

			"Foursquare" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" ) 
			),
			"Odnoklassniki" => array (
				"enabled" => true,
				"keys"    => array ( 
                                    "key"=>"",
                                    "id" => "1152937216", 
                                    "secret" => "" 
                                    ) 
			),
			"Mailru" => array (
				"enabled" => true,
				"keys"    => array ( 
                                    "id" => "737165", 
                                    "secret" => "" 
                                    ) 
			),
		),

		// If you want to enable logging, set 'debug_mode' to true.
		// You can also set it to
		// - "error" To log only error messages. Useful in production
		// - "info" To log info and error messages (ignore debug messages) 
		"debug_mode" => false,

		// Path to file writable by the web server. Required if 'debug_mode' is not false
		"debug_file" => "",
	);
