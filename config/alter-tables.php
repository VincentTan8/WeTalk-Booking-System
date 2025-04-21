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

// $filetoresult = ltrim($my_tables_use) . '_resources.`parent`';
// $resultat = mysqli_query(
// 	$conn,
// 	"CREATE TABLE IF NOT EXISTS $filetoresult (
// 				`id` INT AUTO_INCREMENT PRIMARY KEY,
// 				`ref_num` VARCHAR(100) NOT NULL,
// 				`username` VARCHAR(100) UNIQUE,
// 				`fname` VARCHAR(100) NOT NULL,
// 				`mname` VARCHAR(100),
// 				`lname` VARCHAR(100) NOT NULL,
// 				`email` VARCHAR(100) NOT NULL,
// 				`bio` VARCHAR(100),
// 				`city` VARCHAR(100),
// 				`phone` VARCHAR(50),
// 				`birthday` DATE,
// 				`gender` VARCHAR(20),
// 				`password` VARCHAR(100) NOT NULL
// 			);"
// );

//for adding new column
$tablename = $prefix . '_resources.`teachers_in_sched`';  //tablename
$col_name = 'language_id';
$column_attr = "INT DEFAULT NULL";
$col = mysqli_query($conn, "SELECT " . $col_name . " FROM " . $tablename);
if (!$col) {
	mysqli_query($conn, "ALTER TABLE " . $tablename . " ADD " . $col_name . " " . $column_attr);
	echo $col_name . ' added </br>';
} else {
	echo $col_name . ' already exists! </br>';
}

$tablename = $prefix . '_resources.`student`';  //tablename
$col_name = 'profile_pic';
$column_attr = "VARCHAR(100)";
$col = mysqli_query($conn, "SELECT " . $col_name . " FROM " . $tablename);
if (!$col) {
	mysqli_query($conn, "ALTER TABLE " . $tablename . " ADD " . $col_name . " " . $column_attr);
	echo $col_name . ' added </br>';
} else {
	echo $col_name . ' already exists! </br>';
}

$tablename = $prefix . '_resources.`teacher`';  //tablename
$col_name = 'profile_pic';
$column_attr = "VARCHAR(100)";
$col = mysqli_query($conn, "SELECT " . $col_name . " FROM " . $tablename);
if (!$col) {
	mysqli_query($conn, "ALTER TABLE " . $tablename . " ADD " . $col_name . " " . $column_attr);
	echo $col_name . ' added </br>';
} else {
	echo $col_name . ' already exists! </br>';
}

$tablename = $prefix . '_resources.`parent`';  //tablename
$col_name = 'profile_pic';
$column_attr = "VARCHAR(100)";
$col = mysqli_query($conn, "SELECT " . $col_name . " FROM " . $tablename);
if (!$col) {
	mysqli_query($conn, "ALTER TABLE " . $tablename . " ADD " . $col_name . " " . $column_attr);
	echo $col_name . ' added </br>';
} else {
	echo $col_name . ' already exists! </br>';
}

$tablename = $prefix . '_resources.`cs`';  //tablename
$col_name = 'profile_pic';
$column_attr = "VARCHAR(100)";
$col = mysqli_query($conn, "SELECT " . $col_name . " FROM " . $tablename);
if (!$col) {
	mysqli_query($conn, "ALTER TABLE " . $tablename . " ADD " . $col_name . " " . $column_attr);
	echo $col_name . ' added </br>';
} else {
	echo $col_name . ' already exists! </br>';
}

//for modifying existing column
$tablename = $prefix . '_resources.`teachers_in_sched`';  //tablename
$col_name = 'platform';
$column_attr = "INT NOT NULL";
$col = mysqli_query($conn, "SELECT " . $col_name . " FROM " . $tablename);
if ($col) {
	// Column exists, so modify it
	$alter = mysqli_query($conn, "ALTER TABLE " . $tablename . " MODIFY COLUMN " . $col_name . " " . $column_attr);
	if ($alter) {
		echo $col_name . ' modified </br>';
	} else {
		echo 'Failed to modify ' . $col_name . ': ' . mysqli_error($conn) . '</br>';
	}
} else {
	echo $col_name . ' does not exist!</br>';
}

// $tablename = $prefix . '_resources.`teacher`';  //tablename
// $col_name = 'email';
// $index_result = mysqli_query($conn, "SHOW INDEX FROM $tablename WHERE column_name = '$col_name'");
// if (mysqli_num_rows($index_result) == 0) {
// 	$add_index = "ALTER TABLE $tablename ADD INDEX ($col_name);";
// 	if (mysqli_query($conn, $add_index)) {
// 		echo "Index on $col_name added.</br>";
// 	} else {
// 		echo "Error adding index: " . mysqli_error($conn) . "</br>";
// 	}
// } else {
// 	echo "Index on $col_name already exists.</br>";
// }

// $tablename = $prefix . '_resources.`parent`';
// $col_name = 'email';
// $index_result = mysqli_query($conn, "SHOW INDEX FROM $tablename WHERE column_name = '$col_name'");
// if (mysqli_num_rows($index_result) == 0) {
// 	$add_index = "ALTER TABLE $tablename ADD INDEX ($col_name);";
// 	if (mysqli_query($conn, $add_index)) {
// 		echo "Index on $col_name added.</br>";
// 	} else {
// 		echo "Error adding index: " . mysqli_error($conn) . "</br>";
// 	}
// } else {
// 	echo "Index on $col_name already exists.</br>";
// }

// $tablename = $prefix . '_resources.`student`';
// $col_name = 'email';
// $index_result = mysqli_query($conn, "SHOW INDEX FROM $tablename WHERE column_name = '$col_name'");
// if (mysqli_num_rows($index_result) == 0) {
// 	$add_index = "ALTER TABLE $tablename ADD INDEX ($col_name);";
// 	if (mysqli_query($conn, $add_index)) {
// 		echo "Index on $col_name added.</br>";
// 	} else {
// 		echo "Error adding index: " . mysqli_error($conn) . "</br>";
// 	}
// } else {
// 	echo "Index on $col_name already exists.</br>";
// }

// $tablename = $prefix . '_resources.`cs`';
// $col_name = 'email';
// $index_result = mysqli_query($conn, "SHOW INDEX FROM $tablename WHERE column_name = '$col_name'");
// if (mysqli_num_rows($index_result) == 0) {
// 	$add_index = "ALTER TABLE $tablename ADD INDEX ($col_name);";
// 	if (mysqli_query($conn, $add_index)) {
// 		echo "Index on $col_name added.</br>";
// 	} else {
// 		echo "Error adding index: " . mysqli_error($conn) . "</br>";
// 	}
// } else {
// 	echo "Index on $col_name already exists.</br>";
// }

echo 'done!';

?>