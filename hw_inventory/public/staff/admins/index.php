<?php

require_once('../../../private/initialize.php');

require_login();

// $admin_set = find_all_admins();
if (is_super_role()){
  $admin_set = find_all_admins();
}else{
  $admin_set = find_admins_by_dep($_SESSION['dep']);
}
$index = 1;
?>

<?php $page_title = 'Users'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <a class="back-link" href="<?php echo url_for('/staff/index.php'); ?>">&laquo; Back to Main Menu</a>
  <!-- <div class="admins listing"> -->
    <h1>Users</h1>
    <?php
    if (is_admin_role() || is_super_role()) {
      echo "<div class=\"actions\"><a class=\"action\" href=\"";
      echo url_for('/staff/admins/new.php'); 
      echo "\">Create New User</a></div>";
    }
    ?>
    <table class="list">
      <tr>
        <th>Index</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th>Email</th>
        <th>Username</th>
        <th>Dep.</th>
        <th>Role</th>
        <?php 
          if (is_admin_role() || is_super_role()){
            echo "<th>&nbsp;</th>
                  <th>&nbsp;</th>
                  <th>&nbsp;</th>";
          }else{
            echo "<th>&nbsp;</th>";
          }
        ?>
      </tr>

      <?php while($admin = mysqli_fetch_assoc($admin_set)) { ?>
        <tr>
          <td><?php echo h($index); ?></td>
          <td><?php echo h($admin['first_name']); ?></td>
          <td><?php echo h($admin['last_name']); ?></td>
          <td><?php echo h($admin['email']); ?></td>
          <td><?php echo h($admin['username']); ?></td>
          <td><?php echo h($admin['dep']); ?></td>
          <td><?php echo h($admin['role']); ?></td>
          <!-- is_admin_role() || is_super_role() -->
          <?php 
            if (is_admin_role() || is_super_role()){
              echo "<td>";
              echo "<a class=\"action\" href=\"";
              echo url_for('/staff/admins/show.php?id=' . h(u($admin['id'])));
              echo "\">View</a>";
              echo "</td>";
              echo "<td>";
              echo "<a class=\"action\" href=\"";
              echo url_for('/staff/admins/edit.php?id=' . h(u($admin['id'])));
              echo "\">Edit</a>";
              echo "</td>";
              echo "<td>";
              echo "<a class=\"action\" href=\"";
              echo url_for('/staff/admins/delete.php?id=' . h(u($admin['id'])));
              echo "\">Delete</a>";
              echo "</td>";

            }else{
              echo "<td>";
              echo "<a class=\"action\" href=\"";
              echo url_for('/staff/admins/show.php?id=' . h(u($admin['id'])));
              echo "\">View</a>";
              echo "</td>";
            }

          ?>
        </tr>
      <?php $index++;} ?>
    </table>
    <br>
    <a class="back-link" href="<?php echo url_for('/staff/index.php'); ?>">&laquo; Back to Main Menu</a>
    <?php
      mysqli_free_result($admin_set);
    ?>
  <!-- </div> -->

  <?php
  if (is_admin_role() || is_super_role()) {
    echo "<form action=\"";
    echo url_for('/staff/admins/users.php'); 
    echo "\" method=\"post\">
          <br>
          <br>
          <input type=\"submit\" value=\"Extract to Excel\">
          </form>";
    # code...
  }
  ?>
</div>
<br>
<br>
<br>
<?php include(SHARED_PATH . '/staff_footer.php'); ?>

