<?php

namespace AppBundle\Splitter;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;

interface SplitRuleInterface
{
    public function match(OrderInterface $order) : bool;

    public function getBuckets(OrderInterface $order) : array;

    public function setupShipment(ShipmentInterface $newShipment, OrderInterface $order) : ShipmentInterface;

    public function moveUnits(array $orderItems, ShipmentInterface $shipmentZero, ShipmentInterface $newShipment): void;
}