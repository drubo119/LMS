<?php
// util_fines.php
function getFinePolicy(mysqli $conn, string $membershipType): ?array {
    $stmt = $conn->prepare("SELECT Fine_Per_Day, Max_Fine FROM Fine_Policy WHERE Membership_Type=? LIMIT 1");
    $stmt->bind_param("s", $membershipType);
    $stmt->execute();
    $res = $stmt->get_result()->fetch_assoc();
    $stmt->close();
    return $res ?: null;
}

// Penalties (adjust as you like)
const LOST_PENALTY_AMOUNT    = 500.00; // replacement/processing fee
const DAMAGED_PENALTY_AMOUNT = 100.00; // repair fee
