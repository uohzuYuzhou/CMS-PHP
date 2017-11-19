<?php

require_once('../../../private/initialize.php');

require_login();
require_admin_role();

$user=$_SESSION['username'] ?? '';

if(!isset($_GET['id'])) {
  redirect_to(url_for('/staff/management/index.php'));
}
$id = $_GET['id'];
if (!is_super_role()){
  validate_admin_boardassets($id,$_SESSION['dep']);
}
$boardoobj =find_board_by_id($id);
if(is_post_request()) {

  // Handle form values sent by new.php

  $board = [];
  $board['id'] = $id;
  $board['sn'] = trim($_POST['sn']);
  $board['owner'] = $_POST['owner'] ?? '';
  $board['project'] = trim($_POST['project']);
  $board['type'] = trim($_POST['type']);
  $board['dep'] = $_POST['dep'] ?? '';
  $board['current_action'] = $_POST['action'] ?? '';
  $board['last_action'] = $_POST['last_action'] ?? '';
  // echo "string", $_POST['action'];
  $update_at = timestamp();
  $board['update_at'] = $update_at ?? '';
  $result = update_baord($board);
  if($result === true) {
    // action_snapshot();
    action_snapshot_board($board['id'],$board['sn'],$board['owner'],$board['project'],$board['type'],$board['current_action'],$user,$board['update_at']);
    $_SESSION['message'] = 'The board was updated successfully.';
    redirect_to(url_for('/staff/management/show.php?id=' . $id));
  } else {
    $errors = $result;
    //var_dump($errors);
  }

} else {

  $board = find_board_by_id($id);

}
// $owner = find_admin_by_id();
$baord_set = find_all_boards();
$boards_count = mysqli_num_rows($baord_set);
$owner_set = find_all_admins();
$owner_count = mysqli_num_rows($owner_set);
$dep_set = find_all_deps();
// $owner = find_owner_by_board_id(); ....
// $owners = mysqli_fetch_assoc($owner_set);
// echo "123",$board['owner'];
mysqli_free_result($baord_set);
// mysqli_free_result($owner_set);
?>

<?php $page_title = 'Edit Board'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/management/index.php'); ?>">&laquo; Back to List</a>

  <div class="subject edit">
    <h1>Edit Board</h1>

    <?php echo display_errors($errors); ?>

    <form action="<?php echo url_for('/staff/management/edit.php?id=' . h(u($id))); ?>" method="post">
      <dl>
        <dt>Board SN</dt>
        <dd><input type="text" name="sn" value="<?php echo h($board['sn']); ?>" /></dd>
      </dl>
      <dl>
        <dt>Owner</dt>
        <dd>
          <select name="owner">
          <?php while($owners = mysqli_fetch_assoc($owner_set)) { ?>
              <?php $owner = $owners['username'];
                  // echo "123",$owner;
                  echo "<option value=\"$owner\"";
                  if($owner == $board['owner']) {
                    echo " selected";
                  }
                  echo ">{$owner}</option>";
              ?>
          <?php } ?>
          </select>
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
        <dd>
          <select name="dep">
          <?php while($deps = mysqli_fetch_assoc($dep_set)) { ?>
              <?php $dep = $deps['dep'];
                  echo "<option value=\"$dep\"";
                  if($dep == $board['dep']) {
                    echo " selected";
                  }
                  echo ">{$dep}</option>";
              ?>
          <?php } ?>
          </select>
        </dd>
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
        <dd><?php echo h($board['last_action']); ?></dd>
         <input type="hidden" name="last_action" value="<?php echo h($board['current_action']); ?>">
      </dl>
      <dl>
        <dt>Action</dt>
        <!-- <dd><input type="text" name="action" value="<?php// echo h($board['current_action']); ?>" /></dd> -->
        <dd><textarea name="action" cols="60" rows="8"><?php echo h($board['current_action']); ?></textarea></dd>
      </dl>
     
      <div id="operations">
        <input type="submit" value="Edit Borad" />
      </div>
    </form>

  </div>

</div>
<?php mysqli_free_result($owner_set);
      mysqli_free_result($dep_set);
      include(SHARED_PATH . '/staff_footer.php'); ?>
