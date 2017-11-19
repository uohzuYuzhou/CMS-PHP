 <?php require_once('../../../private/initialize.php'); ?>


<?php
require_login();
if (is_super_role()){
  $dataResult = find_all_boards();
}else{ // admin & user
  $dataResult = find_boards_by_dep($_SESSION['dep']);
}

// require_admin_role();

$headTitle = "<center><b>Board Inventory</b></center>"; 
$title = "Boards-".timestamp(); 
$headtitle = "<tr style='height:50px;border-style:none;><th border=\"0\" style='height:60px;width:270px;font-size:22px;' colspan='11' >{$headTitle}</th></tr>"; 
$titlename = "<tr>
              <th>Id</th>
              <th>SN</th>
              <th>Owner</th>
              <th>Project</th>
              <th>HW Type</th>
              <th>Department</th>
              <th>Current Action</th>
              <th>Last Action</th>
              <th>Update At</th>
              <th>Create At</th>
            </tr>"; 
           $filename = $title.".xls"; 
excelData($dataResult,$titlename,$headtitle,$filename); 
 ?>

<!-- 
<form action="<?php //echo url_for('/test.php'); ?>/" method="post">
用户名：<input type="text" name="username" >

<input type="submit" value="提交">
</form> -->