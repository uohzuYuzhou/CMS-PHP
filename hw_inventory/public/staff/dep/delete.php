<?php

require_once('../../../private/initialize.php');

require_login();
require_admin_role();
$username = $_SESSION['username'];

if(!isset($_GET['id'])) {
  redirect_to(url_for('/staff/dep/index.php'));
}
$id = $_GET['id'];
$dep = find_dep_by_id($id);
// $old_name = $dep['dep'];
if(is_post_request()) {
  action_snapshot_user($username, "Deleted department (" . $dep['$dep '] .  ")", $username, timestamp());
  $result = delete_dep_by_id($id);
  $_SESSION['message'] = 'Department (' . $dep['dep'] . ') has been deleted.';
  redirect_to(url_for('/staff/dep/index.php'));
} else {
  // $admin = find_admin_by_id($id);
}

?>

<?php $page_title = 'Delete Department'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/dep/index.php'); ?>">&laquo; Back to List</a>

  <div class="admin delete">
    <h1>Delete Department</h1>
    <p style="color: red">Are you sure you want to delete this department?</p>
    <p class="item"><?php echo h($dep['dep']); ?></p>

    <form action="<?php echo url_for('/staff/dep/delete.php?id=' . h(u($dep['id']))); ?>" method="post">
      <div id="operations">
        <input type="submit" name="commit" value="Delete Department" />
      </div>
    </form>
  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
