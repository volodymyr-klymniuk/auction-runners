<?php

require_once('BetModel.php');
require_once('BuyerModel.php');

class BidModel
{
    private $bet;
    private $buyer;

    public function __construct(BetModel $bet, BuyerModel $buyer)
    {
        $this->bet = $bet;
        $this->buyer = $buyer;
    }

    public function getBet(): BetModel
    {
        return $this->bet;
    }

    public function getBuyer(): BuyerModel
    {
        return $this->buyer;
    }
}
