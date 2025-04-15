<?php
function usernameExists($conn, $username, $userTables, $ref_num)
{
    $tables = $userTables;

    foreach ($tables as $table) {
        $query = "SELECT `ref_num` FROM `$table` WHERE `username` = ? LIMIT 1";
        $stmt = $conn->prepare($query);
        if (!$stmt)
            continue;

        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (!($ref_num === $row['ref_num'])) {
                $stmt->close();
                return true; // Found a match
            }
        }

        $stmt->close();
    }

    return false; // No match found in any table
}
?>