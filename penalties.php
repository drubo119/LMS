<?php
// penalties.php

// Fixed penalty amounts for lost or damaged books
define('LOST_PENALTY_AMOUNT', 150.00);      // Penalty for lost book
define('DAMAGED_PENALTY_AMOUNT', 120.00);   // Penalty for damaged book

/**
 * Get penalty based on the book return condition
 *
 * @param string $condition 'good', 'damaged', 'lost'
 * @return float Penalty amount
 */
function getPenalty($condition) {
    switch (strtolower($condition)) {
        case 'lost':
            return LOST_PENALTY_AMOUNT;
        case 'damaged':
            return DAMAGED_PENALTY_AMOUNT;
        case 'good':
        default:
            return 0.0;
    }
}
