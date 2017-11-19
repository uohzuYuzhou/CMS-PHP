 <?php require_once('../../../private/initialize.php'); ?>


<?php
require_login();
$id = $_POST['id'] ?? '';
if ($id == ''){
  redirect_to(url_for('/staff/index.php'));
}
$dataResult = find_actions_by_board_id($id);
$headTitle = "<center><b>History of: ".$_POST['sn']." </b></center>"; 
$title = "History-of-board(".$_POST['sn'].")-".timestamp(); 
$headtitle = "<tr style='height:50px;border-style:none;><th border=\"0\" style='height:60px;width:270px;font-size:22px;' colspan='11' >{$headTitle}</th></tr>"; 
$titlename = "<tr>
              <th>Id</th>
              <th>Board_Id</th>
              <th>SN</th>
              <th>Owner</th>
              <th>Project</th>
              <th>HW Type</th>
              <th>Action</th>
              <th>User</th>
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