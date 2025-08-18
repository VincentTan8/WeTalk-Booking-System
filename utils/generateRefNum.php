<?php
function generateRefNum($conn, $ref_num_prefix, $tablename)
{
    $prefixLength = strlen($ref_num_prefix);
    $stmt = $conn->prepare("SELECT MAX(CAST(SUBSTRING(`ref_num`, ? + 1) AS UNSIGNED)) as max_num
        FROM $tablename 
        WHERE `ref_num` LIKE CONCAT(?, '%')");
    $stmt->bind_param("is", $prefixLength, $ref_num_prefix);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    $max = $result['max_num'] ?? 0;

    $sequence = str_pad($max + 1, 4, '0', STR_PAD_LEFT); // e.g., 0001, 0002, ...
    $reference = $ref_num_prefix . $sequence;

    return $reference;
}
?>