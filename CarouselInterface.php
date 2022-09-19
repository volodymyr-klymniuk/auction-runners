<?php

interface CarouselInterface
{
    /**
     * @return string id of process
     */
    public function start(): string;
    public function getWinner(string $auctionId): ?WinnerModel;
}
