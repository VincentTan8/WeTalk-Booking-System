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
//commented out since db in hosting does not allow this so create the db first then run this
// if (!$conn) {
// 	$conn = mysqli_connect($host, $uname, $pass);
// 	$sql = "CREATE DATABASE IF NOT EXISTS `$database`";
// 	if ($conn->query($sql) === TRUE) {
// 		include 'conf.php';
// 	}
// }

$filetoresult = ltrim($prefix) . $postfix . '.';


//Timeslots Table
$tablename = 'timeslots';
$query = "CREATE TABLE `$tablename` (
		`id` INT AUTO_INCREMENT PRIMARY KEY,
      	`starttime` time,
		`endtime` time 
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
	$query = "INSERT INTO `$tablename` 
			(`starttime`, `endtime`) 
			VALUES
			('07:00:00', '07:30:00'),
			('07:30:00', '08:00:00'),
			('08:00:00', '08:30:00'),
			('08:30:00', '09:00:00'),
			('09:00:00', '09:30:00'),
			('09:30:00', '10:00:00'),
			('10:00:00', '10:30:00'),
			('10:30:00', '11:00:00'),
			('11:00:00', '11:30:00'),
			('11:30:00', '12:00:00'),
			('12:00:00', '12:30:00'),
			('12:30:00', '13:00:00'),
			('13:00:00', '13:30:00'),
			('13:30:00', '14:00:00'),
			('14:00:00', '14:30:00'),
			('14:30:00', '15:00:00'),
			('15:00:00', '15:30:00'),
			('15:30:00', '16:00:00'),
			('16:00:00', '16:30:00'),
			('16:30:00', '17:00:00'),
			('17:00:00', '17:30:00'),
			('17:30:00', '18:00:00'),
			('18:00:00', '18:30:00'),
			('18:30:00', '19:00:00'),
			('19:00:00', '19:30:00'),
			('19:30:00', '20:00:00'),
			('20:00:00', '20:30:00'),
			('20:30:00', '21:00:00')
		;"
	;
	$conn->query($query);
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//Language Table
$tablename = 'language';
$query = "CREATE TABLE `$tablename` (
		`id` INT AUTO_INCREMENT PRIMARY KEY,
      	`code` VARCHAR(100) NOT NULL DEFAULT '',
      	`details` VARCHAR(100)
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
	$query = "INSERT INTO `$tablename` 
			(`code`, `details`) 
			VALUES
			('ZH', 'Mandarin'),
			('EN', 'English'),
			('FIL', 'Filipino')
		;"
	;
	$conn->query($query);
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//Teacher Table
$tablename = 'teacher';
$query = "CREATE TABLE `$tablename` (
      	`id` INT AUTO_INCREMENT PRIMARY KEY,
		`ref_num` VARCHAR(100) NOT NULL,
		`username` VARCHAR(100) UNIQUE,
      	`fname` VARCHAR(100) NOT NULL,
		`mname` VARCHAR(100),
      	`lname` VARCHAR(100) NOT NULL,
		`alias` VARCHAR(100),
	  	`email` VARCHAR(100) NOT NULL,
		`bio` VARCHAR(100),
		`city` VARCHAR(100),
		`phone` VARCHAR(50),
		`birthday` DATE,
		`gender` VARCHAR(20),
		`language_id` INT DEFAULT NULL,
		`password` VARCHAR(100) NOT NULL,
		FOREIGN KEY (`language_id`) REFERENCES `language`(`id`) ON DELETE SET NULL
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
	$query = "INSERT INTO `$tablename` 
			(`fname`, `lname`, `username`, `email`, `city`, `phone`, `birthday`, `gender`, `language_id`, `password`) 
			VALUES
			('Kat', 'Gecolea', 'katgecolea', 'kat@wetalk.com', 'Cavite', '+639876543210', '1990-05-01', 'Female', 2, 'test')
		;"
	;
	$conn->query($query);
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//Parent Table
$tablename = 'parent';
$query = "CREATE TABLE `$tablename` (
      	`id` INT AUTO_INCREMENT PRIMARY KEY,
		`ref_num` VARCHAR(100) NOT NULL,
		`username` VARCHAR(100) UNIQUE,
      	`fname` VARCHAR(100) NOT NULL,
		`mname` VARCHAR(100),
      	`lname` VARCHAR(100) NOT NULL,
	  	`email` VARCHAR(100) NOT NULL,
		`bio` VARCHAR(100),
		`city` VARCHAR(100),
		`phone` VARCHAR(50),
		`birthday` DATE,
		`gender` VARCHAR(20),
		`password` VARCHAR(100) NOT NULL
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//Student Table
$tablename = 'student';
$query = "CREATE TABLE `$tablename` (
      	`id` INT AUTO_INCREMENT PRIMARY KEY,
		`ref_num` VARCHAR(100) NOT NULL,
		`username` VARCHAR(100) UNIQUE,
      	`fname` VARCHAR(100) NOT NULL,
		`mname` VARCHAR(100),
      	`lname` VARCHAR(100) NOT NULL,
		`nickname` VARCHAR(100),
	  	`email` VARCHAR(100),
		`bio` VARCHAR(100),
		`city` VARCHAR(100),
		`phone` VARCHAR(50),
		`birthday` DATE,
		`age` INT,
		`nationality` VARCHAR(100),
		`gender` VARCHAR(20),
		`password` VARCHAR(100) NOT NULL,
		`parent_ref_num` VARCHAR(100) NULL,
		FOREIGN KEY (`parent_ref_num`) REFERENCES `parent`(`ref_num`) ON DELETE SET NULL
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//student_teacher Junction Table
$tablename = 'student_teacher_junction';
$query = "CREATE TABLE `$tablename` (
		`student_ref_num` VARCHAR(100) NOT NULL,
		`teacher_ref_num` VARCHAR(100) NOT NULL,
		PRIMARY KEY (student_ref_num, teacher_ref_num),
		FOREIGN KEY (`student_ref_num`) REFERENCES `student`(`ref_num`) ON DELETE CASCADE
		FOREIGN KEY (`teacher_ref_num`) REFERENCES `teacher`(`ref_num`) ON DELETE CASCADE
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//Schedule Table (made by teachers)
//platform: 0 - offline, 1 - online, 2 - both
$tablename = 'schedule';
$query = "CREATE TABLE `$tablename` (
      	`id` INT AUTO_INCREMENT PRIMARY KEY,
		`ref_num` VARCHAR(100) NOT NULL,
		`scheddate` DATE,
		`schedstarttime` TIME,
		`schedendtime` TIME,
		`platform` INT NOT NULL,
		`teacher_ref_num` VARCHAR(100) NOT NULL,
		`booking_ref_num` VARCHAR(100) NOT NULL,
		`language_id` INT DEFAULT NULL,
		FOREIGN KEY (`teacher_ref_num`) REFERENCES `teacher`(`ref_num`) ON DELETE CASCADE,
		FOREIGN KEY (`language_id`) REFERENCES `language`(`id`) ON DELETE SET NULL
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//Booking Table (made by students/CS)
$tablename = 'booking';
$query = "CREATE TABLE `$tablename` (
      	`id` INT AUTO_INCREMENT PRIMARY KEY,
		`ref_num` VARCHAR(100) NOT NULL,
		`schedule_ref_num` VARCHAR(100) NOT NULL,
		`student_ref_num` VARCHAR(100) NOT NULL,
		`encoded_by` VARCHAR(100) NOT NULL,
		FOREIGN KEY (`schedule_ref_num`) REFERENCES `schedule`(`ref_num`) ON DELETE SET NULL,
		FOREIGN KEY (`student_ref_num`) REFERENCES `student`(`ref_num`) ON DELETE SET NULL
	);"
;
//add foreign key fpr schedule
if ($conn->query($query) === TRUE) {
	mysqli_query($conn, "ALTER TABLE `schedule` ADD FOREIGN KEY (`booking_ref_num`) REFERENCES `booking`(`ref_num`) ON DELETE SET NULL");
	echo "Table $tablename created successfully.<br>";
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//Grouped Teachers Per Sched Table
$tablename = 'teachers_in_sched';
$query = "CREATE TABLE `$tablename` (
		`id` INT AUTO_INCREMENT PRIMARY KEY,  
		`scheddate` DATE,
		`schedstarttime` TIME,
		`schedendtime` TIME,
		`platform` BOOLEAN NOT NULL,
		`teacher_ids` TEXT NOT NULL
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//CS table
$tablename = 'cs';
$query = "CREATE TABLE `$tablename` (
      	`id` INT AUTO_INCREMENT PRIMARY KEY,
		`ref_num` VARCHAR(100) NOT NULL,
		`username` VARCHAR(100) UNIQUE,
      	`fname` VARCHAR(100) NOT NULL,
		`mname` VARCHAR(100),
      	`lname` VARCHAR(100) NOT NULL,
	  	`email` VARCHAR(100) NOT NULL,
		`bio` VARCHAR(100),
		`city` VARCHAR(100),
		`phone` VARCHAR(50),
		`birthday` DATE,
		`gender` VARCHAR(20),
		`password` VARCHAR(100) NOT NULL
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
	$query = "INSERT INTO `$tablename` 
			(`fname`, `lname`, `email`, `city`, `phone`, `birthday`, `gender`, `password`) 
			VALUES
			('Judy', 'Aplacador', 'judy@wetalk.com', 'Taguig', '+639182736450', '1997-04-30', 'Female', 'test')
		;"
	;
	$conn->query($query);
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}
// Close connection
mysqli_close($conn);
?>