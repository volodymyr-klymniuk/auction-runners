<?php

include_once ('BuyerModel.php');

interface AuctionInterface
{
    public function extractWinner(): ?WinnerModel;
    public function loadBid(BidModel $bid): void;
    public function isActive(): bool;
    public function close(): void;
}