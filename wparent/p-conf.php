<?php
if (!isset($_SESSION)) {
	session_start();
	ob_start();
}
?>

<?php
if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
	$email = $_SESSION['email'];

	$tablename = $prefix . "_resources.`parent`";
	$student = $conn->query("SELECT * FROM $tablename WHERE `email` = '$email' LIMIT 1;");

	if ($student) {
		for ($i = 0; $i < $student->num_rows; $i++) {
			$row = $student->fetch_assoc();
			$_SESSION['username'] = $row['username'];
			$_SESSION['fname'] = $row['fname'];
			$_SESSION['lname'] = $row['lname'];
			$_SESSION['bio'] = $row['bio'];
			$_SESSION['city'] = $row['city'];
			$_SESSION['phone'] = $row['phone'];
			$_SESSION['gender'] = $row['gender'];
			$_SESSION['birthday'] = $row['birthday'];
			$_SESSION['id'] = $row['id'];
			$_SESSION['ref_num'] = $row['ref_num'];
			$_SESSION['profile_pic'] = $row['profile_pic'];
		}
	} else {

	}
}
?>