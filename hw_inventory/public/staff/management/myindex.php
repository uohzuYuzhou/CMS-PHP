<?php require_once('../../../private/initialize.php'); ?>

<?php

require_login();

$username = $_SESSION['username'] ?? 'tester';
// echo "string",$username;
$board_set = find_boards_by_username($username);
$index = 1;

?>

<?php $page_title = 'My Boards'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
   <a class="back-link" href="<?php echo url_for('/staff/index.php'); ?>">&laquo; Back to Main Menu</a>
  <div class="subjects listing">
    <h1>My Boards</h1>

    <div class="actions">
      <a class="action" href="<?php echo url_for('/staff/management/mynew.php') ?>">Add a New Board</a>
    </div>

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
        <th>Dep</th>
        <th>History</th>
  	    <th>&nbsp;</th>

  	  </tr>

      <?php while($boards = mysqli_fetch_assoc($board_set)) { ?>
        <?php $action_count = count_actions_by_board_id($boards['id']); ?>
        <!-- <?php// echo "LALA",$action_count;?> -->
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
          <td><a class="action" href="<?php echo url_for('/staff/management/showmine.php?id=' . h(u($boards['id']))); ?>" target="_Blank">(<?php echo $action_count; ?>)View</a></td>
          <td><a class="action" href="<?php echo url_for('/staff/management/myedit.php?id=' . h(u($boards['id']))); ?>" target="_Blank">Detail</a></td>
          <!-- <td><a class="action" href="<?php //echo url_for('/staff/management/mydelete.php?id=' . h(u($boards['id']))); ?>">Delete</a></td> -->
    	  </tr>
      <?php $index++; } ?>
  	</table>
    <br>
     <a class="back-link" href="<?php echo url_for('/staff/index.php'); ?>">&laquo; Back to Main Menu</a>
    <?php
      mysqli_free_result($board_set);
    ?>
  </div>
  <form action="<?php echo url_for('/staff/management/myboards.php'); ?>" method="post">
      <br>
       <!-- <input type="hidden" name="username" value="<?php// echo h(u($user))?>"> -->
      <br>
        <input type="submit" value="Extract to Excel">
      </form>
</div>
<br>
<br>
<br>
<?php include(SHARED_PATH . '/staff_footer.php'); ?>
