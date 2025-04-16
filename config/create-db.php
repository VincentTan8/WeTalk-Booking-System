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
			('20:30:00', '21:00:00'),
			('21:00:00', '21:30:00'),
			('21:30:00', '22:00:00')
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
		`ref_num` VARCHAR(100) NOT NULL UNIQUE,
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
		FOREIGN KEY (`language_id`) REFERENCES `language`(`id`) ON DELETE SET NULL,
		INDEX (`email`)
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
	$query = 'INSERT INTO `teacher` (`id`, `ref_num`, `fname`, `mname`, `lname`, `email`, `city`, `phone`, `birthday`, `gender`, `language_id`, `password`) VALUES
			(1, "WT-0004", "Kat", NULL, "Gecolea", "kat.gecolea@wetalk.com", "Taguig", "+639876543210", "1900-03-06", "Female", 2, "kat12@#"),
			(2, "WT-0062", "Jolife", NULL, "Lauro", "jolife.lauro@wetalk.com", "Taguig", "+639959400919", "1996-04-02", "Female", 2, "jolife@@123"),
			(3, "WT-0064", "Krizia", NULL, "Decena", "krizia.decena@wetalk.com", "Taguig", "", "1999-06-10", "Female", 2, "kriz12$e"),
			(4, "WT-0009", "Rachelle", NULL, "Bodino", "rachelle.bodino@wetalk.com", "Pasig", "", "1988-08-27", "Female", 2, "raechelle12$%"),
			(5, "WT-0021", "Jaser", NULL, "Placeros", "jaser.placeros@wetalk.com", "Taguig", "", "1997-10-15", "Female", 2, "jaser()90"),
			(6, "WT-0067", "Mia", NULL, "Cordez", "mia.cordez@wetalk.com", "Taguig", "", "2001-12-11", "Female", 2, "mia*&^567"),
			(7, "WT-0066", "Leeanne", NULL, "Sebastian", "leeanne.sebastian@wetalk.com", "Taguig", "", "2002-04-10", "Female", 2, "leeanne765?>("),
			(8, "WT-0088", "Vincent", NULL, "Tan", "vincent@oseamatrix.com", "Taguig", "+639326286802", "1998-01-08", "Male", 1, "vince");'
	;
	$conn->query($query);
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//Parent Table
$tablename = 'parent';
$query = "CREATE TABLE `$tablename` (
      	`id` INT AUTO_INCREMENT PRIMARY KEY,
		`ref_num` VARCHAR(100) NOT NULL UNIQUE,
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
		`password` VARCHAR(100) NOT NULL,
		INDEX (`email`)
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
		`ref_num` VARCHAR(100) NOT NULL UNIQUE,
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
		FOREIGN KEY (`parent_ref_num`) REFERENCES `parent`(`ref_num`) ON DELETE SET NULL,
		INDEX (`email`)
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
	$query = "INSERT INTO $tablename (`id`, `ref_num`, `fname`, `mname`, `lname`, `email`, `city`, `phone`, `birthday`, `gender`, `password`) VALUES
			(1, 'FT-0001', 'Jeff', NULL, 'Uy', 'jeff.uy@oseamatrix.com', 'Manila', '1', '2025-06-01', 'Male', 'jeff'),
			(2, 'FT-0002', 'Vincent', NULL, 'Tan', 'vincentdytan8@gmail.com', '', '0932', NULL, '', 'good');"
	;
	$conn->query($query);
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}

//student_teacher Junction Table
$tablename = 'student_teacher_junction';
$query = "CREATE TABLE `$tablename` (
		`student_ref_num` VARCHAR(100) NOT NULL,
		`teacher_ref_num` VARCHAR(100) NOT NULL,
		PRIMARY KEY (student_ref_num, teacher_ref_num),
		FOREIGN KEY (`student_ref_num`) REFERENCES `student`(`ref_num`) ON DELETE CASCADE,
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
		`ref_num` VARCHAR(100) NOT NULL UNIQUE,
		`scheddate` DATE,
		`schedstarttime` TIME,
		`schedendtime` TIME,
		`platform` INT NOT NULL,
		`teacher_ref_num` VARCHAR(100) NOT NULL,
		`booking_ref_num` VARCHAR(100) NULL,
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
		`ref_num` VARCHAR(100) NOT NULL UNIQUE,
		`schedule_ref_num` VARCHAR(100) NOT NULL,
		`student_ref_num` VARCHAR(100) NOT NULL,
		`encoded_by` VARCHAR(100) NOT NULL,
		FOREIGN KEY (`schedule_ref_num`) REFERENCES `schedule`(`ref_num`) ON DELETE CASCADE,
		FOREIGN KEY (`student_ref_num`) REFERENCES `student`(`ref_num`) ON DELETE CASCADE
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
		`ref_num` VARCHAR(100) NOT NULL UNIQUE,
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
		`password` VARCHAR(100) NOT NULL,
		INDEX (`email`)
	);"
;
if ($conn->query($query) === TRUE) {
	echo "Table $tablename created successfully.<br>";
	$query = "INSERT INTO $tablename (`id`, `ref_num`, `fname`, `mname`, `lname`, `email`, `city`, `phone`, `birthday`, `gender`, `password`) VALUES
			(1, 'WT-0061', 'Judy', NULL, 'Aplacador', 'judyann.aplacador@wetalk.com', 'Taguig', '+639673882051', '1997-11-11', 'Female', 'judy_@123!'),
			(2, 'WT-0056', 'Ma Glaidylle', NULL, 'Montenegro', 'ma.glaidylle.montenegro@wetalk.com', 'Paranaque', '+639177065864', '1999-02-05', 'Female', 'glai887%@'),
			(3, 'WT-0057', 'Jenny', NULL, 'Ralla', 'jenny.ralla@wetalk.com', 'Taguig', '+639171681680', '1998-04-16', 'Female', 'jen)(#234'),
			(4, 'WT-0065', 'Roni', NULL, 'Cruz', 'roni.cruz@wetalk.com', 'Taguig', '+639198646687', '1997-12-28', 'Female', 'roni23@#!'),
			(5, 'WT-0099', 'Harry', NULL, 'Macaraig', 'harry.macaraig@wetalk.com', 'Cavite', '+639327654345', '2020-03-20', 'Male', 'hars');"
	;
	$conn->query($query);
} else {
	echo "Error creating table `$tablename`: $conn->error <br>";
}
// Close connection
mysqli_close($conn);
?>