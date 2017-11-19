 <?php require_once('../../../private/initialize.php'); ?>


<?php
require_login();
require_admin_role();
// $dataResult = find_all_admins();
if (is_super_role()){
  $dataResult = find_all_admins();
}else{
  $dataResult = find_admins_by_dep($_SESSION['dep']);
}
$headTitle = "<center><b>Users Info</b></center>"; 
$title = "Users-".timestamp(); 
$headtitle = "<tr style='height:50px;border-style:none;><th border=\"0\" style='height:60px;width:270px;font-size:22px;' colspan='11' >{$headTitle}</th></tr>"; 
$titlename = "<tr>
              <th>ID</th>
              <th>First Name</th>
              <th>Last Name</th>
              <th>Email</th>
              <th>Username</th>
              <th>Department</th>
              <th>Role</th>
              <th>Key</th>
            </tr>"; 
           $filename = $title.".xls"; 
excelData($dataResult,$titlename,$headtitle,$filename); 
 ?>
