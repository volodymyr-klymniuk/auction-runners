<?php

require_once("Auction.php");
require_once("AuctionService.php");
require_once("AuctionInterface.php");
require_once("CarouselInterface.php");
require_once ("BuyerModel.php");
require_once ("BetModel.php");
require_once ("ProcessorInterface.php");

class Processor implements ProcessorInterface
{
    /**
     * @var AuctionInterface
     */
    private $auctionService;

    public function __construct()
    {
        $this->auctionService = new AuctionService();
    }

    /**
     * @throws Exception
     */
    public function execute(array $buyers = [], array $bets = []): void
    {
        $auctionId = $this->auctionService->start();
        // Greedy load
        $buyers = $this->fetchBuyers($auctionId, $buyers, $bets);
        $this->auctionService->loadAuctionMembersActions($auctionId, $buyers);
        $winner = $this->auctionService->getWinner($auctionId);


        $auctionId2 = $this->auctionService->start();
        // Lightweight load
        foreach ($this->fetchBuyersGenerator($auctionId2, $buyers, $bets) as $member) {
            $this->auctionService->loadAuctionMember($auctionId2, $member);
        }

        $winner2 = $this->auctionService->getWinner($auctionId2);
    }

    private function fetchBuyersGenerator(string $auctionId, array $buyers = [], array $bets = []): iterable
    {
        foreach ($buyers as $key => $buyer) {
            $b = new BuyerModel($buyer);
            foreach ($bets[$key] as $bet) {
                $b->addBet($auctionId, (new BetModel($bet)));
            }

            yield $b;
        }
    }

    /**
     * @param string    $auctionId
     * @param array     $buyers
     * @param array     $bets
     *
     * @return array
     */
    private function fetchBuyers(string $auctionId, array $buyers = [], array $bets = []): array
    {
         $tmp = [];

         foreach ($buyers as $key => $buyer) {
             $b = new BuyerModel($buyer);
             foreach ($bets[$key] as $bet) {
                 $b->addBet($auctionId, (new BetModel($bet)));
             }

             $tmp[] = $b;
         }

         return $tmp;
    }
}
