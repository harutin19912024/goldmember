<?php
/**
 * Created by PhpStorm.
 * User: sky
 * Date: 12.12.2016
 * Time: 16:13
 */

namespace common\components;
use Klarna\XMLRPC\Klarna;
use Klarna\XMLRPC\Exception\KlarnaException;

class KlarnaOrdering extends Klarna
{
    public function createOrder(){
        Kl
        $create['cart']['items'] = array();

        $cart = array(
            array(
                'reference' => '123456789',
                'name' => 'Klarna t-shirt',
                'quantity' => 2,
                'unit_price' => 12300,
                'discount_rate' => 1000,
                'tax_rate' => 2500
            ),
            array(
                'type' => 'shipping_fee',
                'reference' => 'SHIPPING',
                'name' => 'Shipping Fee',
                'quantity' => 1,
                'unit_price' => 4900,
                'tax_rate' => 2500
            )
        );


        foreach ($cart as $item) {
            $create['cart']['items'][] = $item;
        }

        try {
            $order->create($create);
            $order->fetch();

            $orderID = $order['id'];

            echo sprintf('Order ID: %s', $orderID);
        } catch (Klarna_Checkout_ApiErrorException $e) {
            var_dump($e->getMessage());
            var_dump($e->getPayload());
            die;
        }
    }
}