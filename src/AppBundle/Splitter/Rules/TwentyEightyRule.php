<?php
/**
 * @author Moritz Deissner
 * @author Tino Wittig
 */

namespace AppBundle\Splitter\Rules;

use AppBundle\Splitter\SplitRuleInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Core\Model\OrderItemInterface;

/**
 * Class TwentyEightyRule
 * @package AppBundle\Splitter\Rules
 */
class TwentyEightyRule implements SplitRuleInterface
{

    /**
     * @param OrderInterface $order
     * @return bool
     */
    public function match(OrderInterface $order): bool
    {
        if ($order->getItemUnits()->count() > 2) {
            return true;
        }
        return false;
    }

    /**
     * @param OrderInterface $order
     * @return array
     */
    public function apply(OrderInterface $order): array
    {
        $orderItems = $order->getItems();

        $buckets = [];

        foreach ($orderItems as $index => $orderItem) {
            $bucketIndex = $index % 4 === 0 ? 0 : 1;
            $buckets[$bucketIndex] = $orderItem;
        }

        return $buckets;
    }
}
