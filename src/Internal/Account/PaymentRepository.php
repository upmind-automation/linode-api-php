<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <http://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Internal\Account;

use Linode\Entity\Account\Payment;
use Linode\Entity\Entity;
use Linode\Internal\AbstractRepository;
use Linode\Repository\Account\PaymentRepositoryInterface;

/**
 * {@inheritdoc}
 */
class PaymentRepository extends AbstractRepository implements PaymentRepositoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function makeCreditCardPayment(string $usd, string $cvv): Payment
    {
        $parameters = [
            'usd' => $usd,
            'cvv' => $cvv,
        ];

        $response = $this->client->api($this->client::REQUEST_POST, $this->getBaseUri(), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new Payment($this->client, $json);
    }

    /**
     * {@inheritdoc}
     */
    public function stagePayPalPayment(string $usd, string $redirect_url, string $cancel_url): string
    {
        $parameters = [
            'usd'          => $usd,
            'redirect_url' => $redirect_url,
            'cancel_url'   => $cancel_url,
        ];

        $response = $this->client->api($this->client::REQUEST_POST, sprintf('%s/paypal', $this->getBaseUri()), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return $json['payment_id'];
    }

    /**
     * {@inheritdoc}
     */
    public function executePayPalPayment(string $payer_id, string $payment_id): void
    {
        $parameters = [
            'payer_id'   => $payer_id,
            'payment_id' => $payment_id,
        ];

        $this->client->api($this->client::REQUEST_POST, sprintf('%s/paypal/execute', $this->getBaseUri()), $parameters);
    }

    /**
     * {@inheritdoc}
     */
    protected function getBaseUri(): string
    {
        return '/account/payments';
    }

    /**
     * {@inheritdoc}
     */
    protected function getSupportedFields(): array
    {
        return [
            Payment::FIELD_ID,
            Payment::FIELD_DATE,
            Payment::FIELD_USD,
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function jsonToEntity(array $json): Entity
    {
        return new Payment($this->client, $json);
    }
}
