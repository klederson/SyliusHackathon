<?php

namespace AppBundle\Splitter\Shipment;

final class OrderShippingStates
{
    const STATE_CART = 'cart';
    const STATE_READY = 'ready';
    const STATE_SPLITTED = 'splitted';
    const STATE_AWAITING = 'awaiting';
    const STATE_CANCELLED = 'cancelled';
    const STATE_PARTIALLY_SHIPPED = 'partially_shipped';
    const STATE_SHIPPED = 'shipped';

    private function __construct()
    {
    }
}
