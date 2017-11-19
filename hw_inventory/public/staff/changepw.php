<?php

require_once('../../private/initialize.php');

require_login();

// require_admin_role()
$username = $_SESSION['username'] ?? 'tester';

if(!isset($_GET['id'])) {
  redirect_to(url_for('/staff/index.php'));
}
$id = $_GET['id'];
is_user_id($id);
if(is_post_request()) {
  $admin = [];
  $admin['id'] = $id;
  $admin['username'] = $_POST['username'];
  $admin['password'] = $_POST['password'] ?? '';
  $admin['confirm_password'] = $_POST['confirm_password'] ?? '';
  $result = update_admin_pw($admin);
  if($result === true) {
    action_snapshot_user($admin['username'], $_SESSION['dep'], "Changed user (".$admin['username'].")'s PW", $username, timestamp());
    $_SESSION['message'] = 'User PW updated.';
    redirect_to(url_for('/staff/admins/show.php?id=' . $id));
  } else {
    $errors = $result;
    $admin = find_admin_by_id($id);
  }
} else {
  $admin = find_admin_by_id($id);
}
$dep_set = find_all_deps();
$user = find_admin_by_id($id);
?>

<?php $page_title = 'Edit User'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/index.php'); ?>">&laquo; Back to Menu</a>

  <div class="admin edit">
    <h1>Edit PW</h1>

    <?php echo display_errors($errors); ?>

    <form action="<?php echo url_for('/staff/changepw.php?id=' . h(u($id))); ?>" method="post">
      <dl>
        <dt>First name</dt>
        <!-- <dd><input type="text" name="first_name" value="<?php //echo h($admin['first_name']); ?>" /></dd> -->
        <dd><?php echo h($admin['first_name']); ?></dd>

      </dl>

      <dl>
        <dt>Last name</dt>
         <dd><?php echo h($admin['last_name']); ?></dd>
      </dl>

      <dl>
        <dt>Username</dt>
        <dd><?php echo h($admin['username']); ?></dd>
        <input type="hidden" name="username" value="<?php echo h($admin['username']); ?>">
      </dl>

      <dl>
        <dt>Email</dt>
        <dd><?php echo h($admin['email']); ?></dd>
        <!-- <dd><input type="text" name="email" value="<?php //echo h($admin['email']); ?>" /><br /></dd> -->
      </dl>

      <dl>
        <dt>Department</dt>
        <dd>
          <dd><?php echo h($admin['dep']); ?></dd>
        </dd>
      </dl>

      <dl>
        <dt>Role</dt>
      <dd>
         <dd><?php echo h($admin['role']); ?></dd>
      </dd>
      </dl>
<!--       <dl>
        <dt>Department</dt>
        <dd><?php 、//echo h($admin['dep']); ?></dd>
      </dl>
      <dl>
        <dt>Role</dt>
        <dd><?php //echo h($admin['role']); ?></dd>
      </dl> -->
      <dl>
        <dt>Password</dt>
        <dd><input type="password" name="password" value="" /></dd>
      </dl>

      <dl>
        <dt>Confirm Password</dt>
        <dd><input type="password" name="confirm_password" value="" /></dd>
      </dl>
      <p style="color: red">
        Passwords should be at least 8 characters and include at least one uppercase letter, lowercase letter, number, and symbol.
      </p>
      <br>

      <div id="operations">
        <input type="submit" value="Change PW" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>