<?php

require_once('../../../private/initialize.php');

require_login();
require_admin_role();
$username = $_SESSION['username'];
if(!isset($_GET['id'])) {
  redirect_to(url_for('/staff/dep/index.php'));
}
$id = $_GET['id'];

$old_name = find_dep_by_id($id)['dep'];
if(is_post_request()) {
  $dep = [];
  $dep['id'] = $id;
  $dep['dep'] = trim($_POST['dep']);
  $result = update_dep($dep);
  if($result === true) {
    action_snapshot_user($username, "Modified Department Name from (" . $old_name . ") to (" . $dep['dep'] . ")", $username, timestamp());
    $_SESSION['message'] = 'Department updated.';
    redirect_to(url_for('/staff/dep/index.php'));
  } else {
    $errors = $result;
  }
} else {
  // $dep = find_dep_by_id($id);
  $dep = [];
  $dep['dep'] = '';
}

?>

<?php $page_title = 'Edit Department'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/dep/index.php'); ?>">&laquo; Back to List</a>

  <div class="admin edit">
    <h1>Edit Department</h1>

    <?php echo display_errors($errors); ?>

    <form action="<?php echo url_for('/staff/dep/edit.php?id=' . h(u($id))); ?>" method="post">
      <dl>
        <dt>Old name</dt>
        <dd><input type="text" name="old_name" value="<?php echo h($old_name); ?>" /></dd>
      </dl>

      <dl>
        <dt>New name</dt>
        <dd><input type="text" name="dep" value="<?php echo h($dep['dep']); ?>" /></dd>
      </dl>

      <p style="color: red">
        Department should be at least 2 characters.
      </p>
      <br />

      <div id="operations">
        <input type="submit" value="Edit Department" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
