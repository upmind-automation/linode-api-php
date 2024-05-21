<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Account;

use Linode\Entity;

/**
 * Payment Method Response Object.
 *
 * @property int                       $id         The unique ID of this Payment Method.
 * @property string                    $type       The type of Payment Method.
 * @property bool                      $is_default Whether this Payment Method is the default method for automatically processing
 *                                                 service charges.
 * @property string                    $created    When the Payment Method was added to the Account.
 * @property CreditCardData|PayPalData $data
 */
class PaymentMethod extends Entity
{
    // Available fields.
    public const FIELD_ID         = 'id';
    public const FIELD_TYPE       = 'type';
    public const FIELD_IS_DEFAULT = 'is_default';
    public const FIELD_CREATED    = 'created';
    public const FIELD_DATA       = 'data';

    // `FIELD_TYPE` values.
    public const TYPE_CREDIT_CARD = 'credit_card';
    public const TYPE_GOOGLE_PAY  = 'google_pay';
    public const TYPE_PAYPAL      = 'paypal';

    /**
     * @codeCoverageIgnore This method was autogenerated.
     */
    public function __get(string $name): mixed
    {
        return match ($name) {
            self::FIELD_DATA => isset($this->data[$name]['card_type']) ? new CreditCardData($this->client, $this->data[$name]) : new PayPalData($this->client, $this->data[$name]),
            default          => parent::__get($name),
        };
    }
}
