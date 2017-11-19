<?php

require_once('../../../private/initialize.php');

require_login();

$user=$_SESSION['username'] ?? '';
$ownerobj = find_admin_by_username($user);
if(!isset($_GET['id'])) {
  redirect_to(url_for('/staff/management/myindex.php'));
}
$id = $_GET['id'];
validate_user_assets($id,$user);
$boardoobj =find_board_by_id($id);
// $last_action = find_last_action_by_board(find_board_by_id($id));
if(is_post_request()) {

  // Handle form values sent by new.php

  $board = [];
  $board['id'] = $id;
  $board['sn'] = $_POST['sn'] ?? '';
  $board['owner'] = $user ?? '';
  $board['project'] = $_POST['project'] ?? '';
  $board['dep'] = $boardoobj['dep'] ?? '';
  $board['current_action'] = $_POST['action'] ?? '';
  $board['last_action'] = $_POST['last_action'] ?? '';
  

  // echo "string", $boardoobj['dep'];
  $update_at = timestamp();
  $board['update_at'] = $update_at ?? '';
  $result = update_baord($board);
  if($result === true) {
    // action_snapshot();
    action_snapshot_board($board['id'],$board['sn'],$board['owner'],$board['project'],$board['current_action'],$user,$board['update_at']);
    $_SESSION['message'] = 'The board was updated successfully.';
    redirect_to(url_for('/staff/management/showmine.php?id=' . $id));
  } else {
    // $board = find_board_by_id($id);
    $board['action'] = $_POST['action'];
    $errors = $result;
    //var_dump($errors);
  }

} else {

  $board = find_board_by_id($id);
  $board['action'] ='';
}
// $owner = find_admin_by_id();
$baord_set = find_all_boards();
$boards_count = mysqli_num_rows($baord_set);
$owner_set = find_all_admins();
$owner_count = mysqli_num_rows($owner_set);
// $owners = mysqli_fetch_assoc($owner_set);
// echo "123",$board['owner'];
mysqli_free_result($baord_set);
// mysqli_free_result($owner_set);
?>

<?php $page_title = 'Edit Board'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div class="panel-wrap">

  <a class="back-link" href="<?php echo url_for('/staff/management/myindex.php'); ?>">&laquo; Back to List</a>

  <!-- <div class="subject edit"> -->
    <h1><br>Detial of Board: <?php echo h($board['sn']);?></h1>

    <?php echo display_errors($errors); ?>

    <!-- <form action="<?php //echo url_for('/staff/management/myedit.php?id=' . h(u($id))); ?>" method="post"> -->
      <dl>
        <dt>Board SN</dt>
        <!-- <dd><input type="text" name="sn" value="<?php //echo h($board['sn']); ?>" /></dd> -->
        <dd><?php echo h($board['sn']);?></dd>
        <input type="hidden" name="sn" value="<?php echo h(trim($board['sn'])); ?>">
     
      </dl>
      <dl>
        <dt>Owner</dt>
        <dd>
          <?php echo h($user);?>
          <!-- <select name="owner"> -->
<!--           <?php// while($owners = mysqli_fetch_assoc($owner_set)) { ?>
              <?php //$owner = $owners['username'];
                  // echo "123",$owner;
                  //echo "<option value=\"$owner\"";
                 // if($owner == $board['owner']) {
                  // echo " selected";
                  //}
                  //echo ">{$owner}</option>";
              ?>
          <?php //} ?> -->
          <!-- </select> -->
        </dd>
      </dl>
      <dl>
        <dt>Project</dt>
        <dd><?php echo h($board['project']);?></dd>
        <input type="hidden" name="project" value="<?php echo h($board['project'])?>">
        <!-- <dd><input type="text" name="project" value="<?php //echo h($board['project']); ?>" /></dd> -->
      </dl>
      <dl>
       <dt>Department</dt>
        <dd><?php echo h($boardoobj['dep']);?></dd>
        <!-- <input type="hidden" name="dep" value="<?php //echo h(u($ownerobj['dep']))?>"> -->
     
      </dl>
       <dl>
        <dt>Create At</dt>
        <dd><?php echo h($boardoobj['timestamp']); ?></dd>
      </dl>
      <dl>
        <dt>Update At</dt>
        <dd><?php echo h($board['update_at']); ?></dd>
      </dl>
      <dl>
        <dt>Last action</dt>
        <dd><?php echo h($boardoobj['last_action']); ?></dd>
         <!-- <input type="hidden" name="last_action" value="<?php// echo h($boardoobj['current_action']); ?>"> -->
      </dl>
      <dl>
        <dt>Action</dt>
        <!-- <dd><input type="text" name="action" value="<?php// echo h($board['current_action']); ?>" /></dd> -->
        <dd><?php echo h($board['current_action']); ?></dd>
        <!-- <dd><textarea name="action" cols="60" rows="8"><?php //echo h($board['action']); ?></textarea></dd> -->
      </dl>
<!--      
      <div id="operations">
        <input type="submit" value="Edit Borad" />
      </div> -->
    <!-- </form> -->

  <!-- </div> -->

</div>
<br>
<br>
<br>
<?php mysqli_free_result($owner_set);
      include(SHARED_PATH . '/staff_footer.php'); ?>
