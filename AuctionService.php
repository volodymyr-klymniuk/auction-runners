<?php

require_once("CarouselInterface.php");
require_once("BidModel.php");

class AuctionService implements CarouselInterface
{
    /**
     * @var Auction[]
     */
    private $races = [];

    public function start(): string
    {
        $id = uniqid();
        $this->rases[$id] = new Auction();

        return $id;
    }

    /**
     * @param string $auctionId
     * @param array|BuyerModel[] $buyers
     *
     * @return void
     */
    public function loadAuctionMembersActions(string $auctionId, array $buyers)
    {
        $auction = $this->getAuction($auctionId);

        if (false === $auction->isActive()) {
            throw new \Exception(\sprintf(
                "Auction id %s is closed. Can not load new members",
                $auctionId
            ));
        }

        foreach ($buyers as $buyer) {
            if (\get_class($buyer) !== BuyerModel::class) {
                throw new \RuntimeException(\sprintf(
                    "Auction id %s. Expect to process BuyerModelClass but got %s",
                    $auctionId,
                    \get_class($buyer)
                ));
            }

            $bets = $buyer->getAuctionBets($auctionId);
            foreach ($bets as $bet) {
                $auction->loadBid(new BidModel($bet, $buyer));
            }
        }
    }

    public function getWinner(string $auctionId): ?WinnerModel
    {
        $auction = $this->getAuction($auctionId);

        if (true === $auction->isActive()) {
            $auction->close();
        }

        return $auction->extractWinner();
    }

    private function getAuction(string $auctionId): Auction
    {
        if (null === $this->rases[$auctionId]) {
            throw new RuntimeException(\sprintf(
                "Auction id %s not exists",
                $auctionId,
            ));
        }

        return $this->rases[$auctionId];
    }
}
