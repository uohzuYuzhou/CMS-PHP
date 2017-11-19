<?php

require_once('../../../private/initialize.php');

require_login();
// require_admin_role();
require_super_role();

$username = $_SESSION['username'];
if(is_post_request()) {
  $dep = [];
  $dep['dep'] = trim($_POST['dep']);
  // echo $dep['dep'];
  $result = insert_dep($dep);
  if($result === true) {
    // $new_id = mysqli_insert_id($db);
    action_snapshot_user($username, "Created new Department (".$dep['dep'].")", $username, timestamp());
    $_SESSION['message'] = 'Department: ' . $dep['dep'] . ' has been created.';
    redirect_to(url_for('/staff/dep/index.php'));
  } else {
    $errors = $result;
  }

} else {
  // display the blank form
  $dep = [];
  $dep["dep"] = '';
}

?>

<?php $page_title = 'Create Department'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/dep/index.php'); ?>">&laquo; Back to List</a>

  <div class="admin new">
    <h1>Create Department</h1>

    <?php echo display_errors($errors); ?>

    <form action="<?php echo url_for('/staff/dep/new.php'); ?>" method="post">
      <dl>
        <dt>Department name</dt>
        <dd><input type="text" name="dep" value="<?php echo h($dep['dep']); ?>" /></dd>
      </dl>

      <p style="color: red">
        Department name should be at least 2 characters.
      </p>
      <br />

      <div id="operations">
        <input type="submit" value="Create Department" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
