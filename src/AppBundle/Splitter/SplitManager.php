<?php

namespace AppBundle\Splitter;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\ShipmentInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;

class SplitManager
{

    /**
     * @var SplitRuleInterface[]
     */
    private $rules = [];

    /**
     * @var FactoryInterface
     */
    private $shipmentFactory;

    public function __construct(FactoryInterface $factory)
    {
        $this->shipmentFactory = $factory;
    }

    /**
     * @param SplitRuleInterface $rule
     */
    public function appendRule(SplitRuleInterface $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * @return SplitRuleInterface[]
     */
    protected function getRules(): array
    {
        return $this->rules;
    }

    public function executeRules(OrderInterface $order)
    {
        foreach ($this->getRules() as $rule) {
            if ($rule->match($order) === true) {
                $this->applyRule($order, $rule);
                break;
            }
        }
    }

    public function applyRule(OrderInterface $order, SplitRuleInterface $rule)
    {
        $orderItemsBuckets = $rule->getBuckets($order);
        $shipments = $order->getShipments();
        $shipmentZero = $order->getShipments()->get(0);

        foreach ($orderItemsBuckets as $index => $orderItems) {
            if ($index > 0) {
                /**
                 * @var ShipmentInterface $newShipment
                 */
                $newShipment = $this->shipmentFactory->createNew();

                $newShipment->setOrder($order);

                $rule->setupShipment($newShipment, $order);
                $rule->moveUnits($orderItems, $shipmentZero, $newShipment);
                $shipments->add($newShipment);
            }
        }
    }




}