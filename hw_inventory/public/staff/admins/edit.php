<?php

require_once('../../../private/initialize.php');

require_login();
require_admin_role();
$username = $_SESSION['username'] ?? 'tester';

if(!isset($_GET['id'])) {
  redirect_to(url_for('/staff/admins/index.php'));
}
$id = $_GET['id'];
if (!is_super_role()){
  validate_admin_usersassets($id,$_SESSION['dep']);
}
if(is_post_request()) {
  $admin = [];
  $admin['id'] = $id;
  $admin['first_name'] = trim($_POST['first_name']);
  $admin['last_name'] = trim($_POST['last_name']);
  $admin['email'] = trim($_POST['email']);
  $admin['username'] = trim($_POST['username']);
  $admin['password'] = $_POST['password'] ?? '';
  $admin['confirm_password'] = $_POST['confirm_password'] ?? '';
  $admin['dep'] = $_POST['dep'] ?? '';
  $admin['role'] = $_POST['role'] ?? '';

  $result = update_admin($admin);
  if($result === true) {
    action_snapshot_user($admin['username'], $_SESSION['dep'], "updated user (".$admin['username'].") info", $username, timestamp());
    $_SESSION['message'] = 'User updated.';
    redirect_to(url_for('/staff/admins/show.php?id=' . $id));
  } else {
    $errors = $result;
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

  <a class="back-link" href="<?php echo url_for('/staff/admins/index.php'); ?>">&laquo; Back to List</a>

  <div class="admin edit">
    <h1>Edit User</h1>

    <?php echo display_errors($errors); ?>

    <form action="<?php echo url_for('/staff/admins/edit.php?id=' . h(u($id))); ?>" method="post">
      <dl>
        <dt>First name</dt>
        <dd><input type="text" name="first_name" value="<?php echo h($admin['first_name']); ?>" /></dd>
      </dl>

      <dl>
        <dt>Last name</dt>
        <dd><input type="text" name="last_name" value="<?php echo h($admin['last_name']); ?>" /></dd>
      </dl>

      <dl>
        <dt>Username</dt>
        <dd><input type="text" name="username" value="<?php echo h($admin['username']); ?>" /></dd>
      </dl>

      <dl>
        <dt>Email</dt>
        <dd><input type="text" name="email" value="<?php echo h($admin['email']); ?>" /><br /></dd>
      </dl>

      <dl>
        <dt>Department</dt>
        <?php if (is_super_role()){
              echo "<dd><select name=\"dep\">";
              while($deps = mysqli_fetch_assoc($dep_set)){
                $dep = $deps['dep'];
                echo "<option value=\"".$dep."\"";
                if($dep == $user['dep']) {
                  echo " selected";
                  }
                echo ">". $dep ."</option>";
               
              }
             echo "</dd></select>";  
            }else{
              echo "<dd>".h($_SESSION['dep']);
              echo "<input type=\"hidden\" name=\"dep\" value=\"";
              echo h($_SESSION['dep'])."\"></dd>";
            }
             // echo "</dd></select>"; 

        ?>         
         

      </dl>

      <dl>
        <dt>Role</dt>
      <dd>
          <select name="role">

            <?php if (is_super_role()){
                echo "<option value=\"super\">Super</option>";
                echo "<option value=\"admin\" selected>Admin</option>";
                echo "<option value=\"user\">User</option>";
                 }
              else{
                echo "<option value=\"admin\">Admin</option>";
                echo "<option value=\"user\" selected>User</option>";
              }
              ?>
          </select>
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
        <input type="submit" value="Edit User" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
