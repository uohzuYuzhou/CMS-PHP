<?php

require_once('../../../private/initialize.php');

require_login();
require_admin_role();

if(!isset($_GET['id'])) {
  redirect_to(url_for('/staff/admins/index.php'));
}
$id = $_GET['id'];

if (!is_super_role()){
  validate_admin_usersassets($id,$_SESSION['dep']);
}
$username = $_SESSION['username'] ?? 'tester';
if(is_post_request()) {
  $account = find_admin_by_id($id);
  $result = delete_admin($id);
  action_snapshot_user($account['username'], $_SESSION['dep'], "Delete user (".$account['username'].")", $username, timestamp());
  $_SESSION['message'] = 'User deleted.';
  redirect_to(url_for('/staff/admins/index.php'));
} else {
  $admin = find_admin_by_id($id);
}

?>

<?php $page_title = 'Delete User'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/admins/index.php'); ?>">&laquo; Back to List</a>

  <div class="admin delete">
    <h1>Delete User</h1>
    <p style="color: red">Are you sure you want to delete this User?</p>
    <p class="item">Username: <?php echo h($admin['username']); ?></p>

    <form action="<?php echo url_for('/staff/admins/delete.php?id=' . h(u($admin['id']))); ?>" method="post">
      <div id="operations">
        <input type="submit" name="commit" value="Delete Admin" />
      </div>
    </form>
  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
