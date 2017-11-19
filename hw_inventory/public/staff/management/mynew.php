<?php

require_once('../../../private/initialize.php');

require_login();
$user=$_SESSION['username'] ?? '';
$ownerobj = find_admin_by_username($user);
// echo "string",$ownerobj["dep"];
$board_set = find_all_boards();
$boards_count = mysqli_num_rows($board_set) + 1;
mysqli_free_result($board_set);

if(is_post_request()) {
  // $user=$_SESSION['username'];
  // $ownerobj = find_admin_by_username($user);
  $board = [];
  $board['sn'] = trim($_POST['sn']);
  $board['owner'] = $user ?? '';
  $board['project'] = trim($_POST['project']);
  $board['type'] = trim($_POST['type']);
  $board['current_action'] = $_POST['action'] ?? '';
  $board['last_action'] = 'Initial CHECK IN';
  $board['dep'] = $ownerobj['dep'] ?? '';
  // $board['dep'] = $_POST['dep'] ?? '';
  $board['update_at'] = timestamp();
  $board['create_at'] = timestamp();
  $result = insert_board($board);
  if($result === true) {
    $new_id = mysqli_insert_id($db);
    action_snapshot_board($new_id,$board['sn'],$board['owner'],$board['project'],strtoupper($board['type']),$board['current_action'],$user,$board['update_at']);
    $_SESSION['message'] = 'The board was created successfully.';
    redirect_to(url_for('/staff/management/show.php?id=' . $new_id));
  } else {
    $errors = $result;
  }

} else {
  // display the blank form
  $board = [];
  $board["sn"] = '';
  $board["owner"] = '';
  $board["project"] = '';
  $board["type"] = '';
  // $board["dep"] = '';
  $board["current_action"] = '';
  $board['create_at'] = timestamp();
}

$owner_set = find_all_admins();
$owner_count = mysqli_num_rows($owner_set);
?>

<?php $page_title = 'Create Borad'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/management/myindex.php'); ?>">&laquo; Back to List</a>

  <div class="subject new">
    <h1>Check In a New Board</h1>

    <?php echo display_errors($errors); ?>

    <form action="<?php echo url_for('/staff/management/mynew.php'); ?>" method="post">
      <dl>
        <dt>Board SN</dt>
        <dd><input type="text" name="sn" value="<?php echo h($board['sn']); ?>" /></dd>
      </dl>
      <dl>
        <dt>Owner</dt>
        <dd><?php echo h($user);?>
<!--           <select name="owner">
          <?php //while($owners = mysqli_fetch_assoc($owner_set)) { ?>
              <?php //$owner = $owners['username'];
                  // echo "123",$owner;
                  //echo "<option value=\"$owner\"";
                  //if($owner == $user) {
                    //echo " selected";
                  //}
                  //echo ">{$owner}</option>";
              ?>
          <?php //} ?>
          </select> -->
        </dd>
      </dl>
      <dl>
      <dt>Project</dt>
        <dd><input type="text" name="project" value="<?php echo h($board['project']); ?>" /></dd>
      </dl>
      <dl>
      <dt>HW Type</dt>
        <dd><input type="text" name="type" value="<?php echo h($board['type']); ?>" /></dd>
      </dl>
      <dl>
      <dt>Department</dt>
        <dd><?php echo h($ownerobj['dep']);?></dd>
        <!-- <input type="hidden" name="dep" value="<?php //echo h(u($ownerobj['dep']))?>"> -->
     
      </dl>
       <dl>
        <dt>Create At</dt>
        <dd><?php echo h($board['create_at']); ?></dd>
      </dl>
      <dl>
        <dt>Action</dt>
        <!-- <dd><input type="text" name="action" value="<?php// echo h($board['current_action']); ?>" /></dd> -->
        <dd><textarea name="action" cols="60" rows="8"><?php echo h($board['current_action']); ?></textarea></dd>
      </dl>
      <div id="operations">
        <input type="submit" value="Create the Board" />
      </div>
    </form>

  </div>

</div>
<?php mysqli_free_result($owner_set);
      include(SHARED_PATH . '/staff_footer.php'); ?>
