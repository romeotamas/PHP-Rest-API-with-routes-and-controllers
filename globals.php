<?php 

	define( 'ABS_DIR', getcwd() . "/");
	define( 'BASEDIR', '/sos/sos-api/' );
	define( 'HOST', $_SERVER['HTTP_HOST']);
	define( 'PROTOCOL', $_SERVER['HTTPS'] == "on" ? "https" : "http");
	define( 'ABS_URL', PROTOCOL.'://'.HOST.BASEDIR);
	define( 'FILES_DIR', "files/");
	define( 'INCLUDES_DIR', "includes/");
	define( 'CONTROLLERS_DIR', "controllers/");
	define( 'FILES_PATH', ABS_DIR . FILES_DIR);
	define( 'FILES_URL', ABS_URL . FILES_DIR);
	define( 'INCLUDES_PATH', ABS_DIR . INCLUDES_DIR);
	define( 'CONTROLLERS_PATH', INCLUDES_PATH . CONTROLLERS_DIR);
	

	/*Database*/
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'sosdb');
	define('DB_USER', 'root');
	define('DB_PASS', '');

	/*Security*/
	define('SECRETE_KEY', 'test123');
	
	/*Data Type*/
	define('BOOLEAN', 	'1');
	define('INTEGER', 	'2');
	define('STRING', 	'3');

	/*Token expire minute*/
	define('TOKEN_EXPIRE_MINUTE', 180);

	/*Error Codes*/
	define('REQUEST_METHOD_NOT_VALID',		        100);
	define('REQUEST_CONTENTTYPE_NOT_VALID',	        101);
	define('REQUEST_NOT_VALID', 			        102);
    define('VALIDATE_PARAMETER_REQUIRED', 			103);
	define('VALIDATE_PARAMETER_DATATYPE', 			104);
	define('API_NAME_REQUIRED', 					105);
	define('API_PARAM_REQUIRED', 					106);
	define('API_DOST_NOT_EXIST', 					107);
	define('INVALID_USER_PASS', 					108);
	define('USER_NOT_ACTIVE', 						109);
	define('INVALID_ROUTE', 						110);
	define('NO_DATA_RESPONSE', 						111);
	define('INVALID_PARAMS', 						112);
	define('REQUIRED_PARAMS', 						113);

	/*Success*/
	define('SUCCESS_RESPONSE', 						200);

	/*Server Errors*/
	define('JWT_PROCESSING_ERROR',					300);
	define('ATHORIZATION_HEADER_NOT_FOUND',			301);
	define('ACCESS_TOKEN_ERRORS',					302);	
	define('INVALID_TOKEN',							303);