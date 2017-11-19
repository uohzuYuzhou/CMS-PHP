 <?php require_once('../../../private/initialize.php'); ?>


<?php
require_login();

$user = $_SESSION['username'] ?? 'tester';

$dataResult = find_boards_by_username($user);
$headTitle = "<center><b>Owner : ".$user."</b></center>"; 
$title = "Boards(".$user.")-".timestamp(); 
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
              <th>Create At</th>
              <th>Update At</th>
            </tr>"; 
           $filename = $title.".xls"; 
excelData($dataResult,$titlename,$headtitle,$filename); 
 ?>

<!-- 
<form action="<?php //echo url_for('/test.php'); ?>/" method="post">
用户名：<input type="text" name="username" >

<input type="submit" value="提交">
</form> -->