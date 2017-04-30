<?php

namespace AppBundle\Splitter\Rules;

use Doctrine\Common\Collections\Collection;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;
use Sylius\Component\Core\Model\Shipment;
use Sylius\Component\Core\Model\ShipmentInterface;

abstract class AbstractShipmentSplitterRule
{

    const SHIPMENT_ZERO = 0;

    /**
     * @param ShipmentInterface $newShipment
     * @param OrderInterface $order
     *
     * @return ShipmentInterface
     */
    public function setupShipment(ShipmentInterface $newShipment, OrderInterface $order) : ShipmentInterface
    {
        $shipmentZero = $order->getShipments()->get(static::SHIPMENT_ZERO);
        $newShipment->setMethod($shipmentZero->getMethod());

        return $newShipment;
    }

    /**
     * @param OrderInterface $order
     * @return array
     */
    abstract public function getBuckets(OrderInterface $order) : array;


    /**
     * @param OrderInterface[] $orderItems
     * @param ShipmentInterface $shipmentZero
     * @param ShipmentInterface $newShipment
     */
    public function moveUnits(array $orderItems, ShipmentInterface $shipmentZero, ShipmentInterface $newShipment): void
    {
        foreach ($orderItems as $item) {
            /** @var Shipment $shipmentZero */
            $shipmentZero->removeUnit($item);
            $newShipment->addUnit($item);
        }
    }
}