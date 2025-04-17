<?php
if (!isset($_SESSION)) {
	session_start();
	ob_start();
}
?>

<?php
if (isset($_SESSION['ref_num']) && !empty($_SESSION['ref_num'])) {
	$ref_num = $_SESSION['ref_num'];

	$tablename = $prefix . "_resources.`cs`";
	$cs = $conn->query("SELECT * FROM $tablename WHERE `ref_num` = '$ref_num' LIMIT 1;");

	if ($cs) {
		for ($i = 0; $i < $cs->num_rows; $i++) {
			$row = $cs->fetch_assoc();
			$_SESSION['email'] = $row['email'];
			$_SESSION['username'] = $row['username'];
			$_SESSION['fname'] = $row['fname'];
			$_SESSION['lname'] = $row['lname'];
			$_SESSION['bio'] = $row['bio'];
			$_SESSION['city'] = $row['city'];
			$_SESSION['phone'] = $row['phone'];
			$_SESSION['gender'] = $row['gender'];
			$_SESSION['birthday'] = $row['birthday'];
			$_SESSION['id'] = $row['id'];
			$_SESSION['profile_pic'] = $row['profile_pic'];
		}
	} else {

	}
}
?>