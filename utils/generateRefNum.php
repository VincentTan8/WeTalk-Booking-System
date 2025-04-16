<?php
function generateRefNum($conn, $ref_num_prefix, $tablename)
{
    // Count how many reference numbers already exist with this prefix
    $stmt = $conn->prepare("SELECT COUNT(`ref_num`) as count FROM $tablename WHERE `ref_num` LIKE CONCAT(?, '%')");
    $stmt->bind_param("s", $ref_num_prefix);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $count = $result['count'];

    $sequence = str_pad($count + 1, 4, '0', STR_PAD_LEFT); // e.g., 01, 02, ...
    $reference = $ref_num_prefix . $sequence;

    return $reference;
}
?>