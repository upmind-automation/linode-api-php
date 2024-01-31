<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Networking;

use Linode\Entity;

/**
 * An object representing an IPv6 range.
 *
 * @property string $range  The IPv6 range of addresses in this pool.
 * @property string $region The region for this range of IPv6 addresses.
 */
class IPv6Range extends Entity
{
    // Available fields.
    public const FIELD_RANGE  = 'range';
    public const FIELD_REGION = 'region';
}
