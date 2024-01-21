<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <http://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Repository\Account;

use Linode\Entity\Account\Payment;
use Linode\Exception\LinodeException;
use Linode\Repository\RepositoryInterface;

/**
 * Payment repository.
 */
interface PaymentRepositoryInterface extends RepositoryInterface
{
    /**
     * Makes a Payment to your Account via credit card. This will charge your credit card the requested amount.
     *
     * @param string $usd the amount in US Dollars of the Payment
     * @param string $cvv CVV (Card Verification Value) of the credit card to be used for the Payment
     *
     * @throws LinodeException
     */
    public function makeCreditCardPayment(string $usd, string $cvv): Payment;

    /**
     * This begins the process of submitting a Payment via PayPal. After calling
     * this endpoint, you must take the resulting `payment_id` along with
     * the `payer_id` from your PayPal account and call `executePayPalPayment()`
     * to complete the Payment.
     *
     * @param string $usd          the amount, in US dollars, of the Payment
     * @param string $redirect_url the URL to have PayPal redirect to when Payment is approved
     * @param string $cancel_url   the URL to have PayPal redirect to when Payment is cancelled
     *
     * @return string The paypal-generated ID for this Payment. Used when authorizing the Payment in PayPal's interface.
     *
     * @throws LinodeException
     */
    public function stagePayPalPayment(string $usd, string $redirect_url, string $cancel_url): string;

    /**
     * An object representing an execution of Payment to PayPal to capture the funds and credit your Linode Account.
     *
     * @param string $payer_id   the PayerID returned by PayPal during the transaction authorization process
     * @param string $payment_id the PaymentID returned from `stagePayPalPayment()` that has been approved with PayPal
     *
     * @throws LinodeException
     */
    public function executePayPalPayment(string $payer_id, string $payment_id): void;
}
