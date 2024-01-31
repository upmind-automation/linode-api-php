<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\LinodeInstances;

use Linode\Entity;

/**
 * Device can be either a Disk or Volume identified by `disk_id` or `volume_id`.
 * Only one type per slot allowed.
 *
 * @property null|int $disk_id   The Disk ID, or `null` if a Volume is assigned to this slot.
 * @property null|int $volume_id The Volume ID, or `null` if a Disk is assigned to this slot.
 */
class Device extends Entity
{
    // Available fields.
    public const FIELD_DISK_ID   = 'disk_id';
    public const FIELD_VOLUME_ID = 'volume_id';
}
