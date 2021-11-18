<?php

namespace ZerosDev\Durianpay\Services;

use ZerosDev\Durianpay\Client;
use ZerosDev\Durianpay\Constant;
use ZerosDev\Durianpay\Traits\SetterGetter;

class Orders
{
    use SetterGetter;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function create()
    {
        $this->client->setRequestPayload([
            "amount" => $this->getAmount(),
            "payment_option" => $this->getPaymentOption() ?? 'full_payment',
            "currency" => $this->getCurrency() ?? 'IDR',
            "order_ref_id" => $this->getOrderRefId(),
            "customer" => $this->getCustomer(Constant::ARRAY),
            "items"	=> $this->getItems(Constant::ARRAY),
            "metadata" => $this->getMetadata(Constant::ARRAY)
        ]);

        return $this->client->request('orders', 'POST');
    }

    public function fetch()
    {
        if ($this->getId()) {
            return $this->client->get('orders/'.$this->getId());
        }

        $query = http_build_query([
            'from'	=> $this->getFrom(),
            'to'	=> $this->getTo(),
            'skip'	=> $this->getSkip(),
            'limit'	=> $this->getLimit(),
        ]);

        return $this->client->request('orders'.($query ? '?'.$query : ''));
    }
}
