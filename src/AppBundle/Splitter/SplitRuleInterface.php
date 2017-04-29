<?php

namespace AppBundle\Splitter;

use Sylius\Component\Core\Model\OrderInterface;

interface SplitRuleInterface
{
    public function match(OrderInterface $order) : bool;
}