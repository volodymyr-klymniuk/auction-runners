<?php

interface ProcessorInterface
{
    public function execute(array $buyers = [], array $bets = []): void;
}