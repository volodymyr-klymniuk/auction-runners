<?php

require_once('BetModel.php');

class BuyerModel 
{
    private $name;

    /**
     * @var BetModel[]
     */
    private $bets = [];

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function addBet(string $auctionId, BetModel $bet): void
    {
        if (null === $this->bets[$auctionId]) {
            $this->bets[$auctionId] = [];
        }

        $this->bets[$auctionId][] = $bet;
    }

    /**
     * @return BetModel[]
     */
    public function getAllBets(): array
    {
        return $this->bets;
    }

    /**
     * @return BetModel[]
     */
    public function getAuctionBets(string $auctionId): array
    {
        // maybe should throw error
        if (null === $this->bets[$auctionId]) {
            $this->bets[$auctionId] = [];
        }

        return $this->bets[$auctionId];
    }

    public function getName(): string
    {
        return $this->name;
    }
}
