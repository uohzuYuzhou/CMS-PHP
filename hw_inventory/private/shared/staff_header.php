<?php
  if(!isset($page_title)) { $page_title = 'Manangement Area'; }
?>

<!doctype html>

<html lang="en">
  <head>
    <title>HW Inventory Management System - <?php echo h($page_title); ?></title>
    <?php 
      echo "<link rel=\"icon\" type=\"image/jpg\" href=\"";
      echo url_for("images/favicon3.ico\"");
      echo ">";
    ?>
    <!-- <link rel="icon" type="image/jpg" href="../../images/favicon3.ico"> -->
    <meta charset="utf-8">
    <link rel="stylesheet" media="all" href="<?php echo url_for('/stylesheets/staff.css'); ?>" />
  </head>

  <body>
    <header>
      <h1>HW Inventory Management System - Manangement Area</h1>
    </header>
    <div class="panel-wrap">
      <navigation>
        <ul style="padding-bottom: 30px; font-weight: 700;">
          <li>User: <?php echo $_SESSION['username'] ?? ''; ?></li>
          <li><a href="<?php echo url_for('/staff/index.php'); ?>">Menu</a></li>
          <li><a href="<?php echo url_for('/staff/logout.php'); ?>">Logout</a></li>
          <?php echo "<li><a href=\"";
                echo url_for('/staff/changepw.php?id=' . h(u($_SESSION['admin_id'])));
                echo "\">Change Password</a></li>";
          ?>              
        </ul>
      </navigation>

    <?php echo display_session_message(); ?>