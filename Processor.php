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
        // foreach ($this->fetchBuyers($auctionId, $buyers, $bets) as $buyer
            // $this->auctionService->loadMember()
        // $this->auctionService->getWinner($auctionId);

        $this->auctionService->loadAuctionMembersActions($auctionId, $buyers);
        $winner = $this->auctionService->getWinner($auctionId);

        var_dump($winner);

        //  Example Consider 5 potential buyers (A, B, C, D, E) who compete to acquire an object with a reserve price set at 100 euros, bidding as follows:

            // A: 2 bids of 110 and 130 euros
            // B: O bid
            // C: 1 bid of 125 euros
            // D: 3 bids of 105, 115 and 90 euros
            // E: 3 bids of 132, 135 and 140 euros

        // Instruction
            // start new auction
            // do bets
            // get winner
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
