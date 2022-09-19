<?php

require_once("AuctionInterface.php");

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

        $this->rate = usort($this->rate, function($a, $b) {
            return $a > $b;
        });


        $this->setWinner(
            new WinnerModel(
                $this->rate[$length - 1]->getBet(),
                $this->rate[$length],
                $this->rate[$length]->getBuyer()
            )
        );
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

    private function setWinner(WinnerModel $winner): void
    {
        $this->winner = $winner;
    }
}
