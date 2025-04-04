<?php
if (!isset($_SESSION)) {
	session_start();
	ob_start();
}
?>

<?php
if (isset($_SESSION['email']) && !empty($_SESSION['email'])) {
	$email = $_SESSION['email'];

	$tablename = $prefix . "_resources.`student`";
	$student = $conn->query("SELECT * FROM $tablename WHERE `email` = '$email' LIMIT 1;");

	for ($i = 0; $i < $student->num_rows; $i++) {
		$row = $student->fetch_assoc();
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
}
?>