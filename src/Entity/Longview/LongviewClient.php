<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <http://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Entity\Longview;

use Linode\Entity\Entity;

/**
 * A LongviewClient is a single monitor set up to track statistics about one of your servers.
 *
 * @property int          $id           This Client's unique ID.
 * @property string       $label        This Client's unique label. This is for display purposes only.
 * @property string       $api_key      The API key for this Client, used when configuring the Longview
 *                                      Client application on your Linode.
 * @property string       $install_code The install code for this Client, used when configuring the Longview
 *                                      Client application on your Linode.
 * @property LongviewApps $apps         The apps this Client is monitoring on your Linode. This is configured
 *                                      when you install the Longview Client application, and is present here
 *                                      for information purposes only.
 * @property string       $created      When this Longview Client was created.
 * @property string       $updated      When this Longview Client was last updated.
 */
class LongviewClient extends Entity
{
    // Available fields.
    public const FIELD_LABEL = 'label';

    public function __get(string $name): mixed
    {
        return match ($name) {
            'apps'  => new LongviewApps($this->client, $this->data['apps']),
            default => parent::__get($name),
        };
    }
}
