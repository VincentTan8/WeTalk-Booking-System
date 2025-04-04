<?php
if (!isset($_SESSION)) {
	session_start();
	ob_start();
}
?>

<?php
if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
	$email = $_SESSION['email'];

	$tablename = $prefix . "_resources.`teacher`";
	$teacher = $conn->query("SELECT * FROM $tablename WHERE `email` = '$email' LIMIT 1;");

	if ($teacher) {
		for ($i = 0; $i < $teacher->num_rows; $i++) {
			$row = $teacher->fetch_assoc();
			$fname = $row["fname"];
			$lname = $row["lname"];
			$email = $row["email"];
			$city = $row["city"];
			$phone = $row["phone"];
			$gender = $row["gender"];
			$birthday = $row["birthday"];
			$id = $row["id"];
			$_SESSION['user_email'] = $row['email'];  // You can store email or other details as needed
		}
	} else {

	}
}
?>