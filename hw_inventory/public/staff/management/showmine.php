<?php require_once('../../../private/initialize.php'); ?>

<?php
require_login();
$user=$_SESSION['username'] ?? '';
// $id = isset($_GET['id']) ? $_GET['id'] : '1';
$id = $_GET['id'] ?? '1'; // PHP > 7.0
validate_user_assets($id,$user);
$board = find_board_by_id($id);
// echo $board;
$action_set = find_actions_by_board_id($id);
$index = 1;
?>

<?php $page_title = 'Show log'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/management/myindex.php'); ?>">&laquo; Back to List</a>

    <div class="pages listing">
      <h2>Actions Record</h2>

      <table class="list">
        <tr>
          <th>Index</th>
          <th>SN</th>
          <th>Owner</th>
          <th>Project</th>
          <th>Type</th>
          <th>Action</th>
          <th>Operator</th>
          <th>Timestamp</th>
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
            <td><?php echo h($log['timestamp']); ?></td>
          </tr>
       
        <?php $index++; } ?>
      </table>


      <?php mysqli_free_result($action_set); ?>

    </div>
      <br>
      <a class="back-link" href="<?php echo url_for('/staff/management/myindex.php'); ?>">&laquo; Back to List</a>
      <form action="<?php echo url_for('/staff/management/history.php'); ?>/" method="post">
      <br>
      <br>
        <input type="hidden" name="id" value="<?php echo h(u($id))?>">
        <input type="hidden" name="sn" value="<?php echo h(u($board['sn']))?>">
        <input type="submit" value="Extract to Excel">
      </form>

</div>
<br>
<br>
<br>
<?php include(SHARED_PATH . '/staff_footer.php'); ?>