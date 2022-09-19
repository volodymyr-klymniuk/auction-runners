<?php

require_once("Auction.php");
require_once("AuctionService.php");
require_once("AuctionInterface.php");
require_once("CarouselInterface.php");
require_once ("BuyerModel.php");
require_once ("BetModel.php");

class Processor
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
    public function execute(array $buyers = [], array $bets = [])
    {
        $auctionId = $this->auctionService->start();
        $buyers = $this->fetchBuyers($auctionId, $buyers, $bets);

        $this->auctionService->loadAuctionMembersActions($auctionId, $buyers);
        $winner = $this->auctionService->getWinner($auctionId);

        var_dump($winner->getBuyer()->getName(), $winner->getPrice());
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

             // yield
             $tmp[] = $b;
         }

         return $tmp;
    }
}
