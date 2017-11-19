<?php

require_once('../../../private/initialize.php');

require_login();

$id = $_GET['id'] ?? '1'; // PHP > 7.0
if (!is_super_role()){
  validate_admin_usersassets($id,$_SESSION['dep']);
}
$admin = find_admin_by_id($id);

?>

<?php $page_title = 'Show User'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/admins/index.php'); ?>">&laquo; Back to List</a>

  <div class="admin show">

    <h1>User: <?php echo h($admin['username']); ?></h1> 
    <?php
        if(is_admin_role() || is_super_role()){
              echo "<div>";
              echo "<a class=\"action\" href=\"";
              echo url_for('/staff/admins/edit.php?id=' . h(u($admin['id'])));
              echo "\">Edit</a>&nbsp&nbsp&nbsp";
              echo "<a class=\"action\" href=\"";
              echo url_for('/staff/admins/delete.php?id=' . h(u($admin['id'])));
              echo "\">Delete</a>";
              echo "</div>";
        }
    ?>
    <div class="attributes">
      <dl>
        <dt>First name</dt>
        <dd><?php echo h($admin['first_name']); ?></dd>
      </dl>
      <dl>
        <dt>Last name</dt>
        <dd><?php echo h($admin['last_name']); ?></dd>
      </dl>
      <dl>
        <dt>Email</dt>
        <dd><?php echo h($admin['email']); ?></dd>
      </dl>
      <dl>
        <dt>Username</dt>
        <dd><?php echo h($admin['username']); ?></dd>
      </dl>
      <dl>
        <dt>Department</dt>
        <dd><?php echo h($admin['dep']); ?></dd>
      </dl>
      <dl>
        <dt>Role</dt>
        <dd><?php echo h($admin['role']); ?></dd>
      </dl>
    </div>

  </div>

</div>
