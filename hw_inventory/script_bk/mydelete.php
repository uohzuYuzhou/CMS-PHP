<?php

require_once('../../../private/initialize.php');
redirect_to(url_for('/staff/index.php'));
require_login();
$user=$_SESSION['username'] ?? 'tester';

if(!isset($_GET['id'])) {
  redirect_to(url_for('/staff/management/myindex.php'));
}
$id = $_GET['id'];
validate_user_assets($id,$user);
if(is_post_request()) {

  $result = delete_board($id);
  action_snapshot_board($id,$_POST['sn'],$_POST['owner'],$_POST['project'],$_POST['type'],'delete',$user,timestamp());
  $_SESSION['message'] = 'The board was deleted successfully.';
  redirect_to(url_for('/staff/management/myindex.php'));

} else {
  $board = find_board_by_id($id);
}

?>

<?php $page_title = 'Delete Board'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/management/myindex.php'); ?>">&laquo; Back to List</a>

  <div class="subject delete">
    <h1>Delete Board</h1>
    <p style="color: red">Are you sure you want to delete this Board?</p>
    <p class="item">Board SN： <?php echo h($board['sn']); ?></p>

    <form action="<?php echo url_for('/staff/management/mydelete.php?id=' . h(u($board['id']))); ?>" method="post">
       <dl>
        <dd><input type="hidden" name="sn" value="<?php echo h($board['sn']); ?>" /></dd>
        <dd><input type="hidden" name="owner" value="<?php echo h($board['owner']); ?>" /></dd>
        <dd><input type="hidden" name="project" value="<?php echo h($board['project']); ?>" /></dd>
         <dd><input type="hidden" name="type" value="<?php echo h($board['type']); ?>" /></dd>
        <!-- <dd><input type="hidden" name="action" value="<?php// echo h($board['current_action']); ?>" /></dd> -->
      </dl>
      <div id="operations">
        <input type="submit" name="commit" value="Delete Board" />
      </div>
    </form>
  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
