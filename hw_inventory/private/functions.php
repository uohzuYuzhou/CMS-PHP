<?php

function url_for($script_path) {
	// add the leading '/' if not present
	if($script_path[0] != '/') {
		$script_path = "/" . $script_path;
	}
	return WWW_ROOT . $script_path;
}

function u($string="") {
	return urlencode($string);
}

function raw_u($string="") {
	return rawurlencode($string);
}

function h($string="") {
	return htmlspecialchars($string);
}

function error_404() {
	header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
	exit();
}

function error_500() {
	header($_SERVER["SERVER_PROTOCOL"] . " 500 Internal Server Error");
	exit();
}

function redirect_to($location) {
	header("Location: " . $location);
	exit;
}

function is_post_request() {
	return $_SERVER['REQUEST_METHOD'] == 'POST';
}

function is_get_request() {
	return $_SERVER['REQUEST_METHOD'] == 'GET';
}

function display_errors($errors=array()) {
	$output = '';
	if(!empty($errors)) {
		$output .= "<div class=\"errors\">";
		$output .= "Please fix the following errors:";
		$output .= "<ul>";
		foreach($errors as $error) {
			$output .= "<li>" . h($error) . "</li>";
		}
		$output .= "</ul>";
		$output .= "</div>";
	}
	return $output;
}

function get_and_clear_session_message() {
	if(isset($_SESSION['message']) && $_SESSION['message'] != '') {
		$msg = $_SESSION['message'];
		unset($_SESSION['message']);
		return $msg;
	}
}

function display_session_message() {
	$msg = get_and_clear_session_message();
	if(!is_blank($msg)) {
		return '<div id="message">' . h($msg) . '</div>';
	}
}

function timestamp(){
	date_default_timezone_set('PRC');
	$t = time();
	return date("Y-m-d H:i:s",$t);
} 

function excelData($datas,$titlename,$title,$filename){ 
		$str = "<html xmlns:o=\"urn:schemas-microsoft-com:office:office\"\r\nxmlns:x=\"urn:schemas-microsoft-com:office:excel\"\r\nxmlns=\"http://www.w3.org/TR/REC-html40\">\r\n<head>\r\n<meta http-equiv=Content-Type content=\"text/html; charset=utf-8\">\r\n</head>\r\n<body>"; 
		$str .="<table border=1><head>".$titlename."</head>"; 
		$str .= $title; 
		foreach ($datas  as $key=> $rt ) 
		{ 
				$str .= "<tr>"; 
				foreach ( $rt as $k => $v ) 
				{ 
						$str .= "<td>{$v}</td>"; 
				} 
				$str .= "</tr>\n"; 
		} 
		$str .= "</table></body></html>"; 
		header( "Content-Type: application/vnd.ms-excel; name='excel'" ); 
		header( "Content-type: application/octet-stream" ); 
		header( "Content-Disposition: attachment; filename=".$filename ); 
		header( "Cache-Control: must-revalidate, post-check=0, pre-check=0" ); 
		header( "Pragma: no-cache" ); 
		header( "Expires: 0" ); 
		exit( $str ); 
} 

?>
