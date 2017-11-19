<?php require_once('../../../private/initialize.php'); ?>

<?php
require_login();

// $id = isset($_GET['id']) ? $_GET['id'] : '1';
// $id = $_GET['id'] ?? '1'; // PHP > 7.0

// $board = find_board_by_id($id);
// echo $board;
if (is_super_role()){
  $action_set = find_all_actions();
}else{ // admin & user
  $action_set = find_actions_by_user_dep($_SESSION['dep']);
}

$index = 1;
?>

<?php $page_title = 'Show log'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div class="panel-wrap">

  <a class="back-link" href="<?php echo url_for('/staff/index.php'); ?>">&laquo; Back to Main Menu</a>

    <!-- <div class="subjects listing"> -->
      <h2>Actions Record</h2>
      <form action="<?php echo url_for('/staff/log/boardsactionrecords.php'); ?>/" method="post">
      <br>
        <input type="submit" value="Extract to Excel">
      </form>
      <br>
      <table class="list">
        <tr>
          <th>Index</th>
          <th>SN</th>
          <th>Owner</th>
          <th>Project</th>
          <th>Type</th>
          <th>Action</th>
          <th>Operator</th>
          <th>Dep.</th>
          <th>Timestamp</th>
   <!--        <th>&nbsp;</th> -->
        </tr>

        <?php while($log = mysqli_fetch_assoc($action_set)) { ?>
          <!-- <?php $subject //= find_subject_by_id($page['subject_id']); ?> -->
          <tr>
            <td><?php echo h($index); ?></td>
            <td><?php echo h($log['sn']); ?></td>
            <td><?php echo h($log['owner']); ?></td>
            <td><?php echo h($log['project']); ?></td>
            <td><?php echo h($log['type']); ?></td>
            <td><?php echo h($log['action']); ?></td>
            <td><?php echo h($log['user']); ?></td>
            <td><?php echo h($log['dep']); ?></td>
            <td><?php echo h($log['timestamp']); ?></td>
          </tr>
       
        <?php $index++;} ?>
      </table>
      <?php mysqli_free_result($action_set); ?>

    <!-- </div> -->
      <br>
      <a class="back-link" href="<?php echo url_for('/staff/index.php'); ?>">&laquo; Back to Main Menu</a>
      <form action="<?php echo url_for('/staff/log/boardsactionrecords.php'); ?>/" method="post">
      <br>
      <br>
        <input type="submit" value="Extract to Excel">
      </form>
</div>
<br>
<br>
<br>
<?php include(SHARED_PATH . '/staff_footer.php'); ?>

