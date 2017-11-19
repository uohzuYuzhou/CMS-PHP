 <?php require_once('../../../private/initialize.php'); ?>


<?php
require_login();
require_admin_role();
if (is_admin_role()){
	$dataResult = find_all_activities_by_account_dep($_SESSION['dep']);
}else{  //super
	$dataResult = find_all_account_activities();
}
// $dataResult = find_all_account_activities();
$headTitle = "<center><b>Account Activity Record</b></center>"; 
$title = "Account_Activity_Record-".timestamp(); 
$headtitle = "<tr style='height:50px;border-style:none;><th border=\"0\" style='height:60px;width:270px;font-size:22px;' colspan='11' >{$headTitle}</th></tr>"; 
$titlename = "<tr>
              <th>Id</th>
              <th>Account</th>
              <th>Dep.</th>
              <th>Action</th>
              <th>Operator</th>
              <th>Timestamp</th>
            </tr>"; 
           $filename = $title.".xls"; 
excelData($dataResult,$titlename,$headtitle,$filename); 
 ?>

<!-- 
<form action="<?php //echo url_for('/test.php'); ?>/" method="post">
用户名：<input type="text" name="username" >

<input type="submit" value="提交">
</form> -->