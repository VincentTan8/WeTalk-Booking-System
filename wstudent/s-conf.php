<?php
if (!isset($_SESSION)) {
	session_start();
	ob_start();
}
?>

<?php
if (isset($_SESSION['ref_num']) && !empty($_SESSION['ref_num'])) {
	$ref_num = $_SESSION['ref_num'];

	$tablename = $prefix . "_resources.`student`";
	$student = $conn->query("SELECT * FROM $tablename WHERE `ref_num` = '$ref_num' LIMIT 1;");

	if ($student) {
		for ($i = 0; $i < $student->num_rows; $i++) {
			$row = $student->fetch_assoc();
			$_SESSION['email'] = $row['email'];
			$_SESSION['username'] = $row['username'];
			$_SESSION['fname'] = $row['fname'];
			$_SESSION['lname'] = $row['lname'];
			$_SESSION['nickname'] = $row['nickname'];
			$_SESSION['bio'] = $row['bio'];
			$_SESSION['city'] = $row['city'];
			$_SESSION['phone'] = $row['phone'];
			$_SESSION['gender'] = $row['gender'];
			$_SESSION['birthday'] = $row['birthday'];
			$_SESSION['age'] = $row['age'];
			$_SESSION['nationality'] = $row['nationality'];
			$_SESSION['parent_ref_num'] = $row['parent_ref_num'];
			$_SESSION['id'] = $row['id'];
			$_SESSION['profile_pic'] = $row['profile_pic'];
		}
	} else {

	}
}
?>