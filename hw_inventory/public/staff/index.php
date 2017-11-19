<?php require_once('../../private/initialize.php'); ?>

 <?php require_login(); ?>

<?php $page_title = 'Staff Menu'; ?>
<?php include(SHARED_PATH . '/staff_header.php'); ?>

<div id="content">
  <div id="main-menu">
    <!-- <br> -->
    <h2>Main Menu</h2>
    <ul>
      <li><a href="<?php echo url_for('/staff/management/myindex.php'); ?>">My Boards</a></li>
      <br>
      <li><a href="<?php echo url_for('/staff/management/index.php'); ?>">All Boards</a></li>
      <br>
      <li><a href="<?php echo url_for('/staff/log/boardsindex.php'); ?>">Boards Log</a></li>
      <br>
      <li><a href="<?php echo url_for('/staff/admins/index.php'); ?>">Users</a></li>
      <br>
      <li><a href="<?php echo url_for('/staff/dep/index.php'); ?>">Departments</a></li>
      <br>
      <?php  if (is_admin_role() || is_super_role()){
        echo "<li><a href=\"";
        echo url_for('/staff/log/usersindex.php');
        echo "\">User Activity Log</a></li>";
        
      }?>
      <!-- <li><a href="<?php //echo url_for('/staff/log/usersindex.php'); ?>">User Activity Log</a></li> -->
    </ul>
  </div>

</div>

<?php include(SHARED_PATH . '/staff_footer.php'); ?>
