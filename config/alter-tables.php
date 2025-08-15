<?php
if (!isset($_SESSION)) {
	session_start();
	ob_start();
}
?>


<?php


mysqli_report(MYSQLI_REPORT_OFF);

set_time_limit(0);


$presentdate = date('Y-m-d');

include 'conf.php';

$filetoresult = ltrim($my_tables_use) . '_resources.`admin`';
$resultat = mysqli_query(
	$conn,
	"CREATE TABLE IF NOT EXISTS $filetoresult (
				`id` INT AUTO_INCREMENT PRIMARY KEY,
				`ref_num` VARCHAR(100) NOT NULL,
				`username` VARCHAR(100) UNIQUE,
				`fname` VARCHAR(100) NOT NULL,
				`mname` VARCHAR(100),
				`lname` VARCHAR(100) NOT NULL,
				`email` VARCHAR(100) NOT NULL,
				-- `bio` VARCHAR(100),
				-- `city` VARCHAR(100),
				-- `phone` VARCHAR(50),
				-- `birthday` DATE,
				-- `gender` VARCHAR(20),
				`password` VARCHAR(100) NOT NULL
			);"
);

//for adding new column
// $tablename = $prefix . '_resources.`teachers_in_sched`';  //tablename
// $col_name = 'language_id';
// $column_attr = "INT DEFAULT NULL";
// $col = mysqli_query($conn, "SELECT " . $col_name . " FROM " . $tablename);
// if (!$col) {
// 	mysqli_query($conn, "ALTER TABLE " . $tablename . " ADD " . $col_name . " " . $column_attr);
// 	echo $col_name . ' added </br>';
// } else {
// 	echo $col_name . ' already exists! </br>';
// }

// $tablename = $prefix . '_resources.`student`';  //tablename
// $col_name = 'profile_pic';
// $column_attr = "VARCHAR(100)";
// $col = mysqli_query($conn, "SELECT " . $col_name . " FROM " . $tablename);
// if (!$col) {
// 	mysqli_query($conn, "ALTER TABLE " . $tablename . " ADD " . $col_name . " " . $column_attr);
// 	echo $col_name . ' added </br>';
// } else {
// 	echo $col_name . ' already exists! </br>';
// }

//for modifying existing column
// "ALTER TABLE `schedule` ADD FOREIGN KEY (`booking_ref_num`) REFERENCES `booking`(`ref_num`) ON DELETE SET NULL"
// $tablename = $prefix . '_resources.`schedule`';  //tablename
// $col_name = 'booking_ref_num';
// $column_attr = "ON DELETE SET NULL";
// $col = mysqli_query($conn, "SELECT " . $col_name . " FROM " . $tablename);
// if ($col) {
// 	// Column exists, so alter it
// 	$alter = mysqli_query($conn, "ALTER TABLE " . $tablename . " ADD FOREIGN KEY (" . $col_name . ") REFERENCES `booking`(`ref_num`) " . $column_attr);
// 	if ($alter) {
// 		echo $col_name . ' modified </br>';
// 	} else {
// 		echo 'Failed to modify ' . $col_name . ': ' . mysqli_error($conn) . '</br>';
// 	}
// } else {
// 	echo $col_name . ' does not exist!</br>';
// }


echo 'done!';

?>