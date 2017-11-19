<?php require_once('../../../private/initialize.php'); ?>

<?php

require_login();

if (is_super_role()){
  $board_set = find_all_boards();
}else{
  $board_set = find_boards_by_dep($_SESSION['dep']);
}

// echo "- ",$board_set;
$index = 1;


?>

<?php $page_title = 'Boards'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>
<!-- <div id="content"> -->
<div class="panel-wrap">
  <a class="back-link" href="<?php echo url_for('/staff/index.php'); ?>">&laquo; Back to Main Menu</a>
  <div >
    <h1>All Boards</h1>
    <?php
      if (is_admin_role() || is_super_role()) {
        echo "<div class=\"actions\"><a class=\"action\" href=\"";
        echo url_for('/staff/management/new.php'); 
        echo "\">Add New Board</a></div>";
      }else{
        echo "<div class=\"actions\"><a class=\"action\" href=\"";
        echo url_for('/staff/management/mynew.php'); 
        echo "\">Add New Board</a></div>";
      }
    ?>
  	<table class="list">
  	  <tr>
        <th>Index</th>
        <th>SN</th>
  	    <th>Project</th>
        <th>Type</th>
        <th>Owner</th>
        <th>Current_Action</th>
        <th>Last_Action</th>
        <th>Create_At</th>
        <th>Update_At</th>
        <th>Dep.</th>
        <th>History</th>
  	    <?php 
          if (is_admin_role() || is_super_role()){
            echo "<th>&nbsp;</th> 
                  <th>&nbsp;</th>";
          }else{
            // echo "<th>&nbsp;</th>";
          }
        ?>
  	  </tr>

      <?php while($boards = mysqli_fetch_assoc($board_set)) { ?>
        <?php $action_count = count_actions_by_board_id($boards['id']); ?>
        <tr>
          <td><?php echo h($index); ?></td>
          <td><?php echo h($boards['sn']); ?></td>
          <td><?php echo h($boards['project']); ?></td>
          <td><?php echo h($boards['type']); ?></td>
          <td><?php echo h($boards['owner']); ?></td>
          <td><?php echo h($boards['current_action']); ?></td>
          <td><?php echo h($boards['last_action']); ?></td>
          <td><?php echo h($boards['timestamp']); ?></td>
          <td><?php echo h($boards['update_at']); ?></td>
          <td><?php echo h($boards['dep']); ?></td>
          <!-- <td><?php //echo $boards['visible'] == 1 ? 'true' : 'false'; ?></td> -->
           <?php 
            if (is_admin_role() || is_super_role()){
              echo "<td>";
              echo "<a class=\"action\" href=\"";
              echo url_for('/staff/management/show.php?id=' . h(u($boards['id'])));
              echo "\" target=\"_Blank\">(" . h(u($action_count)) . ")View</a>";
              echo "</td>";
              echo "<td>";
              echo "<a class=\"action\" href=\"";
              echo url_for('/staff/management/edit.php?id=' . h(u($boards['id'])));
              echo "\">Edit</a>";
              echo "</td>";
              echo "<td>";
              echo "<a class=\"action\" href=\"";
              echo url_for('/staff/management/delete.php?id=' . h(u($boards['id'])));
              echo "\">Delete</a>";
              echo "</td>";

            }else{
              echo "<td>";
              echo "<a class=\"action\" href=\"";
              echo url_for('/staff/management/show.php?id=' . h(u($boards['id'])));
              echo "\" target=\"_Blank\">(" . h(u($action_count)) . ")View</a>";
              echo "</td>";
            }

          ?>

    	  </tr>
      <?php $index++; } ?>
  	</table>
    <br>
    <a class="back-link" href="<?php echo url_for('/staff/index.php'); ?>">&laquo; Back to Main Menu</a>
    <?php
      mysqli_free_result($board_set);
    ?>
  </div>
  <br>
  <!-- <a class="back-link" href="<?php //echo url_for('/staff/index.php'); ?>">&laquo; Back to Main Menu</a> -->
  <!-- <br> -->
  <form action="<?php echo url_for('/staff/management/boards.php'); ?>" method="post">
      <br>
      <br>
        <input type="submit" value="Extract to Excel">
      </form>
</div>
<br>
<br>
<br>
<?php include(SHARED_PATH . '/staff_footer.php'); ?>
