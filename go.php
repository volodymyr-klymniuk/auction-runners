<?php

set_include_path(dirname(__FILE__));
require_once('Processor.php');

//  Example Consider 5 potential buyers (A, B, C, D, E) 
    // who compete to acquire an object with a reserve price set at 100 euros, bidding as follows: 
        // A: 2 bids of 110 and 130 euros 
        // B: O bid 
        // C: 1 bid of 125 euros 
        // D: 3 bids of 105, 115 and 90 euros 
        // E: 3 bids of 132, 135 and 140 euros 

// The buyer E wins the auction at the price of 130 euros. 

$buyers = [
    'Arnold',
    'Bim',
    'Carrol',
    'Dan',
    'Ell',
];

$bets = [
    [110, 130],
    [],
    [125],
    [105, 115, 90],
    [132, 135, 140],
];

$processor = new Processor();
$processor->execute($buyers, $bets);
