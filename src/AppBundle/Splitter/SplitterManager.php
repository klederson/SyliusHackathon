<?php

namespace AppBundle\Splitter;

use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItem;
use Sylius\Component\Core\Model\OrderItemInterface;
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
    private $orderFactory;

    public function __construct(FactoryInterface $factory)
    {
        $this->orderFactory = $factory;
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
    protected function getRules() : array
    {
        return $this->rules;
    }

    public function check(OrderInterface $order)
    {
        /**
         * @var OrderItemInterface[]
         */
        $orderItemsBucket = [];

        foreach($this->getRules() as $rule) {
            if( $rule->match($order) === true ) {
                $orderItemsBucket = $rule->apply($order);

                $this->splitOrder($order, $orderItemsBucket);
                break;
            }
        }
    }

    private function splitOrder(OrderInterface $originalOrder, array $orderItemsBuckets) {
        $orderCollection = [];

        foreach($orderItemsBuckets as $index => $orderItems) {
            if($index > 0) {
                /**
                 * @var OrderInterface $newOrder
                 */
                $newOrder = $this->orderFactory->createNew();

                $newOrder->setCustomer($originalOrder->getCustomer());
                $newOrder->setBillingAddress($originalOrder->getBillingAddress());
                $newOrder->setShippingAddress($originalOrder->getShippingAddress());
                $newOrder->setChannel($originalOrder->getChannel());
                $newOrder->setShippingState($originalOrder->getShippingState());

                foreach ($orderItems as $item) {
                    $originalOrder->removeItem($item);
                    $newOrder->addItem($item);
                }

                $orderCollection[] = $newOrder;
            }
        }

        $orderCollection[] = $originalOrder;

        return $orderCollection;
    }
}