<?php

require_once('../../../private/initialize.php');

require_login();
// require_admin_role()
// $dep_set = find_all_deps();

?>

<?php $page_title = 'Departments'; 
$dep_set = find_all_deps();
$index = 1;?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <a class="back-link" href="<?php echo url_for('/staff/index.php'); ?>">&laquo; Back to Main Menu</a>
  <div class="admins listing">
    <h1>Departments</h1>
    <?php
    if (is_super_role()) {
      echo "<div class=\"actions\"><a class=\"action\" href=\"";
      echo url_for('/staff/dep/new.php'); 
      echo "\">Create New Department</a></div>";
    }
    ?>

    <table class="list">
      <tr>
        <th>Index</th>
        <th>Deparment Name</th>
        <?php 
          if (is_super_role()){
            echo "<th>&nbsp;</th>
                  <th>&nbsp;</th>";
          }else{
            // echo "<th>&nbsp;</th>";
          }
        ?>
      </tr>

      <?php while($dep = mysqli_fetch_assoc($dep_set)) { ?>
        <tr>
          <td><?php echo h($index); ?></td>
          <td><?php echo h($dep['dep']); ?></td>
          <?php 
            if (is_super_role()){
              echo "<td>";
              echo "<a class=\"action\" href=\"";
              echo url_for('/staff/dep/edit.php?id=' . h(u($dep['id'])));
              echo "\">Edit</a>";
              echo "</td>";
              echo "<td>";
              echo "<a class=\"action\" href=\"";
              echo url_for('/staff/dep/delete.php?id=' . h(u($dep['id'])));
              echo "\">Delete</a>";
              echo "</td>";

            }else{

            }

          ?>
        </tr>
      <?php $index++;} ?>
    </table>
    <br>
    <a class="back-link" href="<?php echo url_for('/staff/index.php'); ?>">&laquo; Back to Main Menu</a>
    <?php
      mysqli_free_result($dep_set);
    ?>
  </div>
<!--   <form action="<?php //echo url_for('/staff/dep/deps.php'); ?>/" method="post">
      <br>
      <br>
        <input type="submit" value="Extract to Excel">
      </form> -->
</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
