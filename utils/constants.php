<?php
//Hierarchy matters here since if a teacher is given admin access, the admin table should be checked first so user type returned is one with higher permissions
$userTables = ['admin', 'cs', 'teacher', 'parent', 'student'];
?>