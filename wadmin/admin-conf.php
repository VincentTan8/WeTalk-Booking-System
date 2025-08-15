<?php
if (!isset($_SESSION)) {
	session_start();
	ob_start();
}
?>

<?php
if (isset($_SESSION['ref_num']) && !empty($_SESSION['ref_num'])) {
	$ref_num = $_SESSION['ref_num'];

	$tablename = $prefix . "_resources.`admin`";
	$admin = $conn->query("SELECT * FROM $tablename WHERE `ref_num` = '$ref_num' LIMIT 1;");

	if ($admin) {
		for ($i = 0; $i < $admin->num_rows; $i++) {
			$row = $admin->fetch_assoc();
			$_SESSION['email'] = $row['email'];
			$_SESSION['username'] = $row['username'];
			$_SESSION['fname'] = $row['fname'];
			$_SESSION['lname'] = $row['lname'];
			$_SESSION['id'] = $row['id'];
		}
	} else {

	}
}
?>