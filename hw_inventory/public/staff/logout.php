<?php
require_once('../../private/initialize.php');
if (isset($_SESSION['admin_id'])){
	log_out_admin();
}
redirect_to(url_for('/staff/login.php'));

?>
