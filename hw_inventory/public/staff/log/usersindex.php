<?php require_once('../../../private/initialize.php'); ?>

<?php
require_login();
require_admin_role();

// $id = isset($_GET['id']) ? $_GET['id'] : '1';
// $id = $_GET['id'] ?? '1'; // PHP > 7.0

// $board = find_board_by_id($id);
// echo $board;
if (is_admin_role()){
  $activity_set = find_all_activities_by_account_dep($_SESSION['dep']);
}else{  //super
  $activity_set = find_all_account_activities();
}

$index = 1;
?>

<?php $page_title = 'Account Activities'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">

  <a class="back-link" href="<?php echo url_for('/staff/index.php'); ?>">&laquo; Back to Main Menu</a>

    <div class="pages listing">
      <h2>Account Activities</h2>
       <form action="<?php echo url_for('/staff/log/usersactionrecords.php'); ?>/" method="post">
      <br>
        <input type="submit" value="Extract to Excel">
      </form>
      <br>
      <table class="list">
        <tr>
          <th>Index</th>  
          <th>Account</th>
          <th>Dep.</th>
          <th>Action</th>
          <th>Operator</th>
          <th>Timestamp</th>
        </tr>

        <?php while($activities = mysqli_fetch_assoc($activity_set)) { ?>
          <!-- <?php $subject //= find_subject_by_id($page['subject_id']); ?> -->
          <tr>
            <td><?php echo h($index); ?></td>
            <td><?php echo h($activities['account']); ?></td>
            <td><?php echo h($activities['dep']); ?></td>
            <td><?php echo h($activities['action']); ?></td>
            <td><?php echo h($activities['manipulator']); ?></td>
            <td><?php echo h($activities['timestamp']); ?></td>
          </tr>
       
        <?php $index++;} ?>
      </table>
      <?php mysqli_free_result($activity_set); ?>

    </div>
      <br>
      <a class="back-link" href="<?php echo url_for('/staff/index.php'); ?>">&laquo; Back to Main Menu</a>
      <form action="<?php echo url_for('/staff/log/usersactionrecords.php'); ?>/" method="post">
      <br>
      <br>
        <input type="submit" value="Extract to Excel">
      </form>
</div>
 <br>
 <br>
<?php include(SHARED_PATH . '/staff_footer.php'); ?>

