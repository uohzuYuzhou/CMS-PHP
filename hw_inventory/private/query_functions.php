<?php

	// Subjects

	function find_all_boards($options=[]) {
		global $db;

		$visible = $options['visible'] ?? false;

		$sql = "SELECT * FROM boards ";
		if($visible) {
			$sql .= "WHERE visible = true ";
		}
		$sql .= "ORDER BY timestamp ASC";
		//echo $sql;
		$result = mysqli_query($db, $sql);
		confirm_result_set($result);
		return $result;
		// return $sql;
	}

	function find_board_by_id($id, $options=[]) {
		global $db;

		$visible = $options['visible'] ?? false;

		$sql = "SELECT * FROM boards ";
		$sql .= "WHERE id='" . db_escape($db, $id) . "' ";
		if($visible) {
			$sql .= "AND visible = true";
		}
		// echo $sql;
		$result = mysqli_query($db, $sql);
		confirm_result_set($result);
		$boards = mysqli_fetch_assoc($result);
		mysqli_free_result($result);
		return $boards; // returns an assoc. array
	}

	
	function find_boards_by_username($username){
		global $db;
		$sql = "SELECT * FROM boards ";
		$sql .= "WHERE owner='" . db_escape($db, $username) . "' ";

		// $sql = "SELECT * FROM admins ";
		// $sql .= "WHERE id='" . db_escape($db, $username) . "' ";
		// $sql = "SELECT * FROM boards;";
		$boards = mysqli_query($db, $sql);
		// $boards = mysqli_query($db, "SELECT * FROM boards");
		confirm_result_set($boards);
		// $boards = mysqli_fetch_assoc($result);
		// mysqli_free_result($result);
		return $boards; // returns an assoc. array
	}

	function find_boards_by_dep($dep){
		global $db;
		$sql = "SELECT * FROM boards ";
		$sql .= "WHERE dep='" . db_escape($db, $dep) . "' ";
		$boards = mysqli_query($db, $sql);
		confirm_result_set($boards);
		return $boards; // returns an assoc. array
	}

	function board_duplicate_check($sn){
		$result = false;
		$boards = find_all_boards();
		foreach ($boards as $board) {
			if($board['sn'] == $sn){
				$result = true;
			}
			# code...
		}
		return $result;
	}

	function validate_board($board,$options=[]) {
		$errors = [];

		// Board's SN
		if(is_blank($board['sn'])) {
			$errors[] = "Board's SN cannot be blank.";
		} elseif(!has_length($board['sn'], ['min' => 2, 'max' => 255])) {
			$errors[] = "Board's SN must be between 2 and 255 characters.";
		} elseif (!isset($options['edit'])) {
			if (!has_unique_board_sn($board['sn'])){
				$errors[] = "Board's SN cannot be duplicated one, please check and correct.";
			}
		} elseif (!has_unique_board_sn($board['sn'],$board['id'])){
			
				$errors[] = "Board's SN cannot be duplicated one, please check and correct.";
			
			# code...
			// }
			
		}

		if(is_blank($board['dep'])) {
			$errors[] = "Board's dep cannot be blank.";
		} elseif(!has_length($board['dep'], ['min' => 2, 'max' => 255])) {
			$errors[] = "Board's dep must be between 2 and 255 characters.";
		}

		// project
		if(is_blank($board['project'])) {
			$errors[] = "Project cannot be blank.";
		} elseif(!has_length($board['project'], ['min' => 2, 'max' => 255])) {
			$errors[] = "Project must be between 2 and 255 characters.";
		}
		//type
		if(is_blank($board['type'])) {
			$errors[] = "Board's type cannot be blank.";
		} elseif(!has_length($board['type'], ['min' => 2, 'max' => 255])) {
			$errors[] = "Board's type must be between 2 and 255 characters.";
		}
		// action
		if(is_blank($board['current_action'])) {
			$errors[] = "Action cannot be blank.";
		} elseif(!has_length($board['current_action'], ['min' => 2, 'max' => 255])) {
			$errors[] = "Action must be between 2 and 255 characters.";
		}

		if(!check_owner_dep_conformance($board['owner'],$board['dep'])){
			$errors[] = "Owner's Dep. does not macth the department Info. been provided.";
		}

		return $errors;
	}

	function check_owner_dep_conformance($owner,$dep){
		$ownerdep = find_dep_by_username($owner);
		if ($ownerdep == $dep){
			return true;
		}else{
			return false;
		}
	}

	function insert_board($board) {
		global $db;
		// $test['dep'] = 'wowo';
		$errors = validate_board($board);
		if(!empty($errors)) {
			return $errors;
		}

		// shift_subject_positions(0, $subject['position']);
		$sql = "INSERT INTO boards ";
		$sql .= "(sn,owner,project,type,dep,current_action,last_action,update_at) ";
		$sql .= "VALUES (";
		$sql .= "'" . db_escape($db, $board['sn']) . "',";
		$sql .= "'" . db_escape($db, $board['owner']) . "',";
		$sql .= "'" . db_escape($db, $board['project']) . "',";
		$sql .= "'" . db_escape($db, strtoupper($board['type'])) . "',";
		$sql .= "'" . db_escape($db, $board['dep']) . "',";
		$sql .= "'" . db_escape($db, $board['current_action']) . "',";
		$sql .= "'" . db_escape($db, $board['last_action']) . "',";
		$sql .= "'" . db_escape($db, $board['update_at']) . "'";
		$sql .= ")";
		$result = mysqli_query($db, $sql);
		// For INSERT statements, $result is true/false
		if($result) {
			return true;
		} else {
			// INSERT failed
			echo mysqli_error($db);
			db_disconnect($db);
			exit;
		}
	}

  function update_baord($board) {
		global $db;
		$options = [];
		$options['edit']=true;
		$errors = validate_board($board,$options);
		if(!empty($errors)) {
			return $errors;
		}

		// $old_board = find_board_by_id($board['id']);
		// $old_position = $old_board['position'];
		// shift_subject_positions($old_position, $subject['position'], $subject['id']);
		
		$sql = "UPDATE boards SET ";
		$sql .= "sn='" . db_escape($db, $board['sn']) . "', ";
		$sql .= "owner='" . db_escape($db, $board['owner']) . "', ";
		$sql .= "project='" . db_escape($db, strtoupper($board['project'])). "', ";
		$sql .= "type='" . db_escape($db, strtoupper($board['type'])) . "', ";
		$sql .= "dep='" . db_escape($db, $board['dep']) . "', ";
		$sql .= "current_action='" . db_escape($db, $board['current_action']) . "', ";
		$sql .= "last_action='" . db_escape($db, $board['last_action']) . "', ";
		$sql .= "update_at='" . db_escape($db, $board['update_at']) . "' ";
		$sql .= "WHERE id='" . db_escape($db, $board['id']) . "' ";
		$sql .= "LIMIT 1";

		$result = mysqli_query($db, $sql);
		// For UPDATE statements, $result is true/false
		if($result) {
			return true;
		} else {
			// UPDATE failed
			echo mysqli_error($db);
			db_disconnect($db);
			exit;
		}

	}

	function delete_board($id) {
		global $db;

		// $old_subject = find_subject_by_id($id);
		// $old_position = $old_subject['position'];
		// shift_subject_positions($old_position, 0, $id);

		$sql = "DELETE FROM boards ";
		$sql .= "WHERE id='" . db_escape($db, $id) . "' ";
		$sql .= "LIMIT 1";
		$result = mysqli_query($db, $sql);

		// For DELETE statements, $result is true/false
		if($result) {
			return true;
		} else {
			// DELETE failed
			echo mysqli_error($db);
			db_disconnect($db);
			exit;
		}
	}

	function shift_subject_positions($start_pos, $end_pos, $current_id=0) {
		global $db;

		if($start_pos == $end_pos) { return; }

		$sql = "UPDATE subjects ";
		if($start_pos == 0) {
			// new item, +1 to items greater than $end_pos
			$sql .= "SET position = position + 1 ";
			$sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
		} elseif($end_pos == 0) {
			// delete item, -1 from items greater than $start_pos
			$sql .= "SET position = position - 1 ";
			$sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
		} elseif($start_pos < $end_pos) {
			// move later, -1 from items between (including $end_pos)
			$sql .= "SET position = position - 1 ";
			$sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
			$sql .= "AND position <= '" . db_escape($db, $end_pos) . "' ";
		} elseif($start_pos > $end_pos) {
			// move earlier, +1 to items between (including $end_pos)
			$sql .= "SET position = position + 1 ";
			$sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
			$sql .= "AND position < '" . db_escape($db, $start_pos) . "' ";
		}
		// Exclude the current_id in the SQL WHERE clause
		$sql .= "AND id != '" . db_escape($db, $current_id) . "' ";

		$result = mysqli_query($db, $sql);
		// For UPDATE statements, $result is true/false
		if($result) {
			return true;
		} else {
			// UPDATE failed
			echo mysqli_error($db);
			db_disconnect($db);
			exit;
		}
	}


	// Log
  function find_last_action_by_board($board){
		$actions_set = find_actions_by_board_id($board['id']);
		$action_count = mysqli_num_rows($actions_set);
		// print($action_count);
		if ($action_count == 1) {
		 mysqli_data_seek($actions_set,0);
		}else{
			mysqli_data_seek($actions_set,0);		
		}
		// if(!empty($errors)) {
		$result = mysqli_fetch_array($actions_set);
		return $result['action'];
		// }
		// print_r("12334");
	 } 

	function find_all_actions() {
		global $db;

		$sql = "SELECT * FROM log ";
		$sql .= "ORDER BY timestamp DESC";
		$result = mysqli_query($db, $sql);
		confirm_result_set($result);
		return $result;
	}

	function find_action_by_id($id, $options=[]) {
		global $db;

		$visible = $options['visible'] ?? false;

		$sql = "SELECT * FROM pages ";
		$sql .= "WHERE id='" . db_escape($db, $id) . "' ";
		if($visible) {
			$sql .= "AND visible = true";
		}
		$result = mysqli_query($db, $sql);
		confirm_result_set($result);
		$page = mysqli_fetch_assoc($result);
		mysqli_free_result($result);
		return $page; // returns an assoc. array
	}

	function find_actions_by_user_dep($dep){
		global $db;

		$sql = "SELECT * FROM log ";
		$sql .= "WHERE dep='" . db_escape($db, $dep) . "' ";
		$sql .= "ORDER BY timestamp DESC";
		$result = mysqli_query($db, $sql);
		confirm_result_set($result);
		return $result;
	}

	function delete_action($id) {
		global $db;

		$old_page = find_page_by_id($id);
		$old_position = $old_page['position'];
		shift_page_positions($old_position, 0, $old_page['subject_id'], $id);

		$sql = "DELETE FROM pages ";
		$sql .= "WHERE id='" . db_escape($db, $id) . "' ";
		$sql .= "LIMIT 1";
		$result = mysqli_query($db, $sql);

		// For DELETE statements, $result is true/false
		if($result) {
			return true;
		} else {
			// DELETE failed
			echo mysqli_error($db);
			db_disconnect($db);
			exit;
		}
	}

  function find_actions_by_board_id($board_id, $options=[]) {
		global $db;

		$visible = $options['visible'] ?? false;

		$sql = "SELECT * FROM log ";
		$sql .= "WHERE board_id='" . db_escape($db, $board_id) . "' ";
		if($visible) {
			$sql .= "AND visible = true ";
		}
		$sql .= "ORDER BY timestamp DESC";
		$result = mysqli_query($db, $sql);
		confirm_result_set($result);
		return $result;
	}

	function count_actions_by_board_id($board_id, $options=[]) {
		global $db;

		$visible = $options['visible'] ?? false;

		$sql = "SELECT COUNT(id) FROM log ";
		$sql .= "WHERE board_id='" . db_escape($db, $board_id) . "' ";
		if($visible) {
			$sql .= "AND visible = true ";
		}
		$sql .= "ORDER BY timestamp ASC";
		$result = mysqli_query($db, $sql);
		confirm_result_set($result);
		$row = mysqli_fetch_row($result);
		mysqli_free_result($result);
		$count = $row[0];
		return $count;
	}

	function shift_action_positions($start_pos, $end_pos, $subject_id, $current_id=0) {
		global $db;

		if($start_pos == $end_pos) { return; }

		$sql = "UPDATE pages ";
		if($start_pos == 0) {
			// new item, +1 to items greater than $end_pos
			$sql .= "SET position = position + 1 ";
			$sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
		} elseif($end_pos == 0) {
			// delete item, -1 from items greater than $start_pos
			$sql .= "SET position = position - 1 ";
			$sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
		} elseif($start_pos < $end_pos) {
			// move later, -1 from items between (including $end_pos)
			$sql .= "SET position = position - 1 ";
			$sql .= "WHERE position > '" . db_escape($db, $start_pos) . "' ";
			$sql .= "AND position <= '" . db_escape($db, $end_pos) . "' ";
		} elseif($start_pos > $end_pos) {
			// move earlier, +1 to items between (including $end_pos)
			$sql .= "SET position = position + 1 ";
			$sql .= "WHERE position >= '" . db_escape($db, $end_pos) . "' ";
			$sql .= "AND position < '" . db_escape($db, $start_pos) . "' ";
		}
		// Exclude the current_id in the SQL WHERE clause
		$sql .= "AND id != '" . db_escape($db, $current_id) . "' ";
		$sql .= "AND subject_id = '" . db_escape($db, $subject_id) . "'";

		$result = mysqli_query($db, $sql);
		// For UPDATE statements, $result is true/false
		if($result) {
			return true;
		} else {
			// UPDATE failed
			echo mysqli_error($db);
			db_disconnect($db);
			exit;
		}
	}


	// Admins

	// Find all admins, ordered last_name, first_name

	function find_all_admins() {
		global $db;

		$sql = "SELECT * FROM admins ";
		$sql .= "ORDER BY last_name ASC, first_name ASC";
		$result = mysqli_query($db, $sql);
		confirm_result_set($result);
		return $result;
	}

	function find_admin_by_id($id) {
		global $db;

		$sql = "SELECT * FROM admins ";
		$sql .= "WHERE id='" . db_escape($db, $id) . "' ";
		$sql .= "LIMIT 1";
		$result = mysqli_query($db, $sql);
		confirm_result_set($result);
		$admin = mysqli_fetch_assoc($result); // find first
		mysqli_free_result($result);
		return $admin; // returns an assoc. array
	}

	function find_admin_by_username($username) {
		global $db;

		$sql = "SELECT * FROM admins ";
		$sql .= "WHERE username='" . db_escape($db, $username) . "' ";
		$sql .= "LIMIT 1";
		$result = mysqli_query($db, $sql);
		confirm_result_set($result);
		$admin = mysqli_fetch_assoc($result); // find first
		mysqli_free_result($result);
		return $admin; // returns an assoc. array
	}

	function find_dep_by_username($username) {
		global $db;

		$sql = "SELECT * FROM admins ";
		$sql .= "WHERE username='" . db_escape($db, $username) . "' ";
		$sql .= "LIMIT 1";
		$result = mysqli_query($db, $sql);
		confirm_result_set($result);
		$admin = mysqli_fetch_assoc($result); // find first
		mysqli_free_result($result);
		return $admin['dep']; // returns an assoc. array
	}

	function find_admins_by_dep($dep){
		global $db;
		$sql = "SELECT * FROM admins ";
		$sql .= "WHERE dep='" . db_escape($db, $dep) . "' ";
		// $sql .= "ORDER BY last_name ASC, first_name ASC";
		$admins = mysqli_query($db, $sql);
		confirm_result_set($admins);
		return $admins;
	}


	function validate_admin($admin, $options=[]) {
		$errors = [];
		$password_required = $options['password_required'] ?? true;

		if(is_blank($admin['first_name'])) {
			$errors[] = "First name cannot be blank.";
		} elseif (!has_length($admin['first_name'], array('min' => 2, 'max' => 255))) {
			$errors[] = "First name must be between 2 and 255 characters.";
		}

		if(is_blank($admin['last_name'])) {
			$errors[] = "Last name cannot be blank.";
		} elseif (!has_length($admin['last_name'], array('min' => 2, 'max' => 255))) {
			$errors[] = "Last name must be between 2 and 255 characters.";
		}

		if(is_blank($admin['email'])) {
			$errors[] = "Email cannot be blank.";
		} elseif (!has_length($admin['email'], array('max' => 255))) {
			$errors[] = "Last name must be less than 255 characters.";
		} elseif (!has_valid_email_format($admin['email'])) {
			$errors[] = "Email must be a valid format.";
		}

		if(is_blank($admin['username'])) {
			$errors[] = "Username cannot be blank.";
		} elseif (!has_length($admin['username'], array('min' => 5, 'max' => 255))) {
			$errors[] = "Username must be between 5 and 255 characters.";
		} elseif (!has_unique_username($admin['username'], $admin['id'] ?? 0)) {
			$errors[] = "Username not allowed. Try another.";
		}

		if($password_required) {
			if(is_blank($admin['password'])) {
				$errors[] = "Password cannot be blank.";
			// } elseif (!has_length($admin['password'], array('min' => 12))) {
			// 	$errors[] = "Password must contain 12 or more characters";
			// } elseif (!preg_match('/[A-Z]/', $admin['password'])) {
			// 	$errors[] = "Password must contain at least 1 uppercase letter";
			// } elseif (!preg_match('/[a-z]/', $admin['password'])) {
			// 	$errors[] = "Password must contain at least 1 lowercase letter";
			// } elseif (!preg_match('/[0-9]/', $admin['password'])) {
			// 	$errors[] = "Password must contain at least 1 number";
			// } elseif (!preg_match('/[^A-Za-z0-9\s]/', $admin['password'])) {
			// 	$errors[] = "Password must contain at least 1 symbol";
			 }

			if(is_blank($admin['confirm_password'])) {
				$errors[] = "Confirm password cannot be blank.";
			} elseif ($admin['password'] !== $admin['confirm_password']) {
				$errors[] = "Password and confirm password must match.";
			}
		}

		return $errors;
	}

	function validate_admin_pw($admin, $options=[]) {
		$errors = [];
		$password_required = $options['password_required'] ?? true;
		
		if($password_required) {
			if(is_blank($admin['password'])) {
				$errors[] = "Password cannot be blank.";
			} elseif (!has_length($admin['password'], array('min' => 8))) {
				$errors[] = "Password must contain 8 or more characters";
			// } elseif (!preg_match('/[A-Z]/', $admin['password'])) {
			// 	$errors[] = "Password must contain at least 1 uppercase letter";
			// } elseif (!preg_match('/[a-z]/', $admin['password'])) {
			// 	$errors[] = "Password must contain at least 1 lowercase letter";
			// } elseif (!preg_match('/[0-9]/', $admin['password'])) {
			// 	$errors[] = "Password must contain at least 1 number";
			// } elseif (!preg_match('/[^A-Za-z0-9\s]/', $admin['password'])) {
			// 	$errors[] = "Password must contain at least 1 symbol";
			}

			if(is_blank($admin['confirm_password'])) {
				$errors[] = "Confirm password cannot be blank.";
			} elseif ($admin['password'] !== $admin['confirm_password']) {
				$errors[] = "Password and confirm password must match.";
			}
		}

		return $errors;
	}

	function insert_admin($admin) {
		global $db;

		// $errors = validate_admin($admin);
		if (!empty($errors)) {
			return $errors;
		}

		$hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT);

		$sql = "INSERT INTO admins ";
		$sql .= "(first_name, last_name, email, username, dep, role, hashed_password) ";
		$sql .= "VALUES (";
		$sql .= "'" . db_escape($db, $admin['first_name']) . "',";
		$sql .= "'" . db_escape($db, $admin['last_name']) . "',";
		$sql .= "'" . db_escape($db, $admin['email']) . "',";
		$sql .= "'" . db_escape($db, $admin['username']) . "',";
		$sql .= "'" . db_escape($db, $admin['dep']) . "',";
		$sql .= "'" . db_escape($db, $admin['role']) . "',";
		$sql .= "'" . db_escape($db, $hashed_password) . "'";
		$sql .= ")";
		$result = mysqli_query($db, $sql);

		// For INSERT statements, $result is true/false
		if($result) {
			return true;
		} else {
			// INSERT failed
			echo mysqli_error($db);
			db_disconnect($db);
			exit;
		}
	}

	function update_admin($admin) {
		global $db;

		$password_sent = !is_blank($admin['password']);

		$errors = validate_admin($admin, ['password_required' => $password_sent]);
		if (!empty($errors)) {
		  return $errors;
		}

		$hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT);

		$sql = "UPDATE admins SET ";
		$sql .= "first_name='" . db_escape($db, $admin['first_name']) . "', ";
		$sql .= "last_name='" . db_escape($db, $admin['last_name']) . "', ";
		$sql .= "email='" . db_escape($db, $admin['email']) . "', ";
		if($password_sent) {
			$sql .= "hashed_password='" . db_escape($db, $hashed_password) . "', ";
		}
		$sql .= "username='" . db_escape($db, $admin['username']) . "', ";
		$sql .= "dep='" . db_escape($db, $admin['dep']) . "', ";
		$sql .= "role='" . db_escape($db, $admin['role']) . "' ";
		$sql .= "WHERE id='" . db_escape($db, $admin['id']) . "' ";
		$sql .= "LIMIT 1";
		$result = mysqli_query($db, $sql);

		// For UPDATE statements, $result is true/false
		if($result) {
			return true;
		} else {
			// UPDATE failed
			echo mysqli_error($db);
			db_disconnect($db);
			exit;
		}
	}

	function update_admin_pw($admin) {
		global $db;

		// $password_sent = !is_blank($admin['password']);
		$password_sent = true;
		$errors = validate_admin_pw($admin);
		if (!empty($errors)) {
		  return $errors;
		}

		$hashed_password = password_hash($admin['password'], PASSWORD_BCRYPT);

		$sql = "UPDATE admins SET ";
		if($password_sent) {
			$sql .= "hashed_password='" . db_escape($db, $hashed_password) . "' ";
		}
		$sql .= "WHERE id='" . db_escape($db, $admin['id']) . "' ";
		$sql .= "LIMIT 1";
		$result = mysqli_query($db, $sql);

		// For UPDATE statements, $result is true/false
		if($result) {
			return true;
		} else {
			// UPDATE failed
			echo mysqli_error($db);
			db_disconnect($db);
			exit;
		}
	}
	function delete_admin($admin) {
		global $db;

		$sql = "DELETE FROM admins ";
		$sql .= "WHERE id='" . db_escape($db, $admin['id']) . "' ";
		$sql .= "LIMIT 1;";
		$result = mysqli_query($db, $sql);

		// For DELETE statements, $result is true/false
		if($result) {
			return true;
		} else {
			// DELETE failed
			echo mysqli_error($db);
			db_disconnect($db);
			exit;
		}
	}

	function find_all_account_activities() {
		global $db;

		$sql = "SELECT * FROM users_log ";
		$sql .= "ORDER BY timestamp DESC";
		$result = mysqli_query($db, $sql);
		confirm_result_set($result);
		return $result;
	}

	function find_all_activities_by_account_dep($dep){
		global $db;

		$sql = "SELECT * FROM users_log ";
		$sql .= "WHERE dep='" . db_escape($db, $dep) . "' ";
		$sql .= "ORDER BY timestamp DESC";
		$result = mysqli_query($db, $sql);
		confirm_result_set($result);
		return $result;
	}
// log

function action_snapshot_board($board_id,$sn,$owner,$project,$type,$action,$user,$timestamp){
	global $db;
	$sql = "INSERT INTO  log(board_id, sn,owner, project, type, action, user, timestamp) VALUES (";
	$sql .= "'" . db_escape($db, $board_id) . "',";
	$sql .= "'" . db_escape($db, $sn) . "',";
	$sql .= "'" . db_escape($db, $owner) . "',";
	$sql .= "'" . db_escape($db, $project) . "',";
	$sql .= "'" . db_escape($db, $type) . "',";
	$sql .= "'" . db_escape($db, $action) . "',";
	$sql .= "'" . db_escape($db, $user) . "',";
	$sql .= "'" . db_escape($db, $timestamp) . "'";
	$sql .= ")";
	$result = mysqli_query($db, $sql);
		// For INSERT statements, $result is true/false
	if($result) {
			return true;
		} else {
			// INSERT failed
			echo mysqli_error($db);
			db_disconnect($db);
			exit;
		}
} 

function action_snapshot_user($account, $dep, $action, $manipulator, $timestamp){
	global $db;
	$sql = "INSERT INTO users_log(account,dep,action,manipulator,timestamp) VALUES (";
	$sql .= "'" . db_escape($db, $account) . "',";
	$sql .= "'" . db_escape($db, $dep) . "',";
	$sql .= "'" . db_escape($db, $action) . "',";
	$sql .= "'" . db_escape($db, $manipulator) . "',";
	$sql .= "'" . db_escape($db, $timestamp) . "'";
	$sql .= ")";
	$result = mysqli_query($db, $sql);
		// For INSERT statements, $result is true/false
	if($result) {
			return true;
		} else {
			// INSERT failed
			echo mysqli_error($db);
			db_disconnect($db);
			exit;
		}
}
//dep
function find_all_deps(){
		global $db;

		$sql = "SELECT * FROM department ";
		$sql .= "ORDER BY dep ASC";
		$result = mysqli_query($db, $sql);
		confirm_result_set($result);
		return $result;
}

function insert_dep($dep) {
		global $db;

		$errors = validate_dep($dep);
		if (!empty($errors)) {
			return $errors;
		}

		$sql = "INSERT INTO department ";
		$sql .= "(dep) ";
		$sql .= "VALUES (";
		$sql .= "'" . db_escape($db, $dep['dep']) . "'";
		$sql .= ")";
		$result = mysqli_query($db, $sql);

		// For INSERT statements, $result is true/false
		if($result) {
			return true;
		} else {
			// INSERT failed
			echo mysqli_error($db);
			db_disconnect($db);
			exit;
		}
}

function update_dep($dep) {
		global $db;
		$options = [];
		$options['edit']=true;
		$errors = validate_dep($dep,$options);
		if (!empty($errors)) {
			return $errors;
		}
		$sql = "UPDATE department SET ";
		$sql .= "dep='" . db_escape($db, $dep['dep']) . "' ";
		$sql .= "WHERE id='" . db_escape($db, $dep['id']) . "' ";
		$sql .= "LIMIT 1";
		$result = mysqli_query($db, $sql);

		// For UPDATE statements, $result is true/false
		if($result) {
			return true;
		} else {
			// UPDATE failed
			echo mysqli_error($db);
			db_disconnect($db);
			exit;
		}
}

function delete_dep_by_id($id) {
		global $db;

		$sql = "DELETE FROM department ";
		$sql .= "WHERE id='" . db_escape($db, $id) . "' ";
		$sql .= "LIMIT 1;";
		$result = mysqli_query($db, $sql);

		// For DELETE statements, $result is true/false
		if($result) {
			return true;
		} else {
			// DELETE failed
			echo mysqli_error($db);
			db_disconnect($db);
			exit;
		}
}

function validate_dep($dep, $options=[]) {
	$errors = [];
	if(is_blank($dep['dep'])) {
		$errors[] = "Department's name cannot be blank.";
	} elseif (!has_length($dep['dep'], array('min' => 2, 'max' => 255))){
		$errors[] = "Department's name must be between 2 and 255 characters.";
	} elseif (!isset($options['edit'])){
		if (!has_unique_dep_name($dep['dep'])){
		$errors[] = "Department's name cannot be duplicated one, please check and correct.";
		}
	} elseif (!has_unique_dep_name($dep['dep'],$dep['id'])) {
		$errors[] = "Department's name cannot be duplicated one, please check and correct.";
	}
	return $errors;
}

function find_dep_by_id($id) {
		global $db;

		$sql = "SELECT * FROM department ";
		$sql .= "WHERE id='" . db_escape($db, $id) . "' ";
		$sql .= "LIMIT 1";
		$result = mysqli_query($db, $sql);
		confirm_result_set($result);
		$dep = mysqli_fetch_assoc($result); // find first
		mysqli_free_result($result);
		return $dep; // returns an assoc. array
}

function validate_user_assets($id,$username){
	$result = false;
	// $userobj = find_admin_by_username($username);
	$assets = find_boards_by_username($username);
	while($board = mysqli_fetch_assoc($assets)){
		if ($board['id'] == $id){
			$result = true;
		}
	}
	if(!$result) {
			redirect_to(url_for('/staff/index.php'));
		}
}

function validate_admin_boardassets($id,$dep){
	$result = false;
	// $userobj = find_admin_by_username($username);
	$board = find_board_by_id($id);
	// $board = mysqli_fetch_assoc($board);
	if ($board['dep'] == $dep){
		$result = true;
	}
	if(!$result) {
		redirect_to(url_for('/staff/index.php'));
	}
}

function validate_admin_usersassets($id,$dep){
	$result = false;
	// $userobj = find_admin_by_username($username);
	$admin = find_admin_by_id($id);
	// $admin = mysqli_fetch_assoc($admin);
	if ($admin['dep'] == $dep){
		$result = true;
	}
	if(!$result) {
		redirect_to(url_for('/staff/index.php'));
	}
}
?>