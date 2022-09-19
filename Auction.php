<?php

require_once("AuctionInterface.php");
require_once("WinnerModel.php");

class Auction implements AuctionInterface
{
    private $state = true;

    /**
     * @var WinnerModel|null
     */
    private $winner = null;

    /**
     * @var BidModel[]
     */
    private $bids = [];

    /**
     * @var BidModel[]
     */
    private $rate = [];

    public function __construct()
    {

    }

    public function __destruct()
    {
        // flush on remote storage
    }

    public function stop(): void
    {
        $this->close();
    }

    public function loadBid(BidModel $bid): void
    {
        $bet = $bid->getBet();
        $buyerName = $bid->getBuyer()->getName();
        $this->bids[] = $bid;

        if (null === $this->rate[$buyerName]) {
            $this->rate[$buyerName] = $bid;

            return;
        }

        if ($this->rate[$buyerName]->getBet() < $bet) {
            $this->rate[$buyerName] = $bid;
        }
    }

    public function extractWinner(): ?WinnerModel
    {
        if (null !== $this->winner) {
            return $this->winner;
        }

        $length = \count($this->rate);

        if (0 === $length) {
            return null;
        }

        if (1 === $length) {
            $this->setWinner(
                new WinnerModel(
                    $this->rate[$length]->getBet()->getPrice(),
                    $this->rate[$length],
                    $this->rate[$length]->getBuyer()
                )
            );

            return $this->winner;
        }

        usort($this->rate, function(BidModel $a, BidModel$b) {
            /** @var BidModel a */
            /** @var BidModel b */
            return $a->getBet() > $b->getBet();
        });

        $this->setWinner(
            new WinnerModel(
                $this->rate[$length -2]->getBet()->getPrice(),
                $this->rate[$length -1],
                $this->rate[$length -1]->getBuyer()
            )
        );

        return $this->winner;
    }

    public function isActive(): bool
    {
        return (bool) $this->state;
    }

    public function close(): void
    {
        if (!$this->isActive()) {
            throw new \RuntimeException('Cannot close non active auction.');
        }

        $this->state = false;
    }

    private function setWinner(WinnerModel $winner): Auction
    {
        $this->winner = $winner;

        return $this;
    }
}
