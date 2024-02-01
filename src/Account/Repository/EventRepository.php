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

use Linode\Account\Event;
use Linode\Account\EventRepositoryInterface;
use Linode\Entity;
use Linode\Internal\AbstractRepository;

/**
 * @codeCoverageIgnore This class was autogenerated.
 */
class EventRepository extends AbstractRepository implements EventRepositoryInterface
{
    public function eventRead(int $eventId): void
    {
        $this->client->post(sprintf('%s/%s/read', $this->getBaseUri(), $eventId));
    }

    public function eventSeen(int $eventId): void
    {
        $this->client->post(sprintf('%s/%s/seen', $this->getBaseUri(), $eventId));
    }

    protected function getBaseUri(): string
    {
        return '/account/events';
    }

    protected function getSupportedFields(): array
    {
        return [
            Event::FIELD_ID,
            Event::FIELD_USERNAME,
            Event::FIELD_ACTION,
            Event::FIELD_ENTITY,
            Event::FIELD_SECONDARY_ENTITY,
            Event::FIELD_CREATED,
            Event::FIELD_DURATION,
            Event::FIELD_PERCENT_COMPLETE,
            Event::FIELD_RATE,
            Event::FIELD_READ,
            Event::FIELD_SEEN,
            Event::FIELD_STATUS,
            Event::FIELD_TIME_REMAINING,
            Event::FIELD_MESSAGE,
        ];
    }

    protected function jsonToEntity(array $json): Entity
    {
        return new Event($this->client, $json);
    }
}
