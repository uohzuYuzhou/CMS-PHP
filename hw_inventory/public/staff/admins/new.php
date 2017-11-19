<?php

require_once('../../../private/initialize.php');

require_login();
require_admin_role();
$username = $_SESSION['username'] ?? 'tester';
if(is_post_request()) {
  $subject = [];
  $admin['first_name'] = trim($_POST['first_name']);
  $admin['last_name'] = trim($_POST['last_name']);
  $admin['email'] = trim($_POST['email']);
  $admin['username'] = trim($_POST['username']);
  $admin['password'] = $_POST['password'] ?? '';
  $admin['confirm_password'] = $_POST['confirm_password'] ?? '';
  $admin['dep'] = $_POST['dep'] ?? '';
  $admin['role'] = $_POST['role'] ?? '';

  $result = insert_admin($admin);
  if($result === true) {
    $new_id = mysqli_insert_id($db);
    action_snapshot_user($admin['username'],$_SESSION['dep'], "Created new user (".$admin['username'].")", $username, timestamp());
    // $_SESSION['message'] = 'Admin deleted.';
    $_SESSION['message'] = 'User created.';
    redirect_to(url_for('/staff/admins/show.php?id=' . $new_id));
  } else {
    $errors = $result;
  }

} else {
  // display the blank form
  $admin = [];
  $admin["first_name"] = '';
  $admin["last_name"] = '';
  $admin["email"] = '';
  $admin["username"] = '';
  $admin['password'] = '';
  $admin['confirm_password'] = '';
  $admin['dep'] = '';
  $admin['role'] = '';
}
$dep_set = find_all_deps();
$user = find_admin_by_username($admin["username"]);
?>

<?php $page_title = 'Create User'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/admins/index.php'); ?>">&laquo; Back to List</a>

  <div class="admin new">
    <h1>Create User</h1>

    <?php echo display_errors($errors); ?>

    <form action="<?php echo url_for('/staff/admins/new.php'); ?>" method="post">
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
        <dt>Email </dt>
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

            <?php if (is_admin_role()){
                echo "<option value=\"admin\">Admin</option>";
                echo "<option value=\"user\"selected>User</option>";
                 }
              else{
                echo "<option value=\"super\">Super</option>";
                echo "<option value=\"admin\">Admin</option>";
                echo "<option value=\"user\" selected>User</option>";
              }
              ?>
          </select>
      </dd>
      </dl>

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
      <br />

      <div id="operations">
        <input type="submit" value="Create Admin" />
      </div>
    </form>

  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
