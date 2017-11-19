 <?php require_once('../../../private/initialize.php'); ?>


<?php
require_login();
if (is_super_role()){
  $dataResult = find_all_actions();
}else{  //admin & user
  $dataResult = find_actions_by_user_dep($_SESSION['dep']);
}
$headTitle = "<center><b>Action Record</b></center>"; 
$title = "Action_Record-".timestamp(); 
$headtitle = "<tr style='height:50px;border-style:none;><th border=\"0\" style='height:60px;width:270px;font-size:22px;' colspan='11' >{$headTitle}</th></tr>"; 
$titlename = "<tr>
              <th>Id</th>
              <th>Board_Id</th>
              <th>SN</th>
              <th>Owner</th>
              <th>Project</th>
              <th>HW Type</th>
              <th>Action</th>
              <th>Operator</th>
              <th>Dep.</th>
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