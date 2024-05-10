<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Account\Repository;

use Linode\Account\AccountAvailability;
use Linode\Account\AccountAvailabilityRepositoryInterface;
use Linode\Entity;
use Linode\Internal\AbstractRepository;

/**
 * @codeCoverageIgnore This class was autogenerated.
 */
class AccountAvailabilityRepository extends AbstractRepository implements AccountAvailabilityRepositoryInterface
{
    protected function getBaseUri(): string
    {
        return 'beta/account/availability';
    }

    protected function getSupportedFields(): array
    {
        return [
            AccountAvailability::FIELD_REGION,
            AccountAvailability::FIELD_UNAVAILABLE,
        ];
    }

    protected function jsonToEntity(array $json): Entity
    {
        return new AccountAvailability($this->client, $json);
    }
}
