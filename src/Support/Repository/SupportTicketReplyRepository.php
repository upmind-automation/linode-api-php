<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Support\Repository;

use Linode\Entity;
use Linode\Internal\AbstractRepository;
use Linode\LinodeClient;
use Linode\Support\SupportTicketReply;
use Linode\Support\SupportTicketReplyRepositoryInterface;

/**
 * @codeCoverageIgnore This class was autogenerated.
 */
class SupportTicketReplyRepository extends AbstractRepository implements SupportTicketReplyRepositoryInterface
{
    /**
     * @param int $ticketId The ID of the Support Ticket.
     */
    public function __construct(LinodeClient $client, protected int $ticketId)
    {
        parent::__construct($client);
    }

    public function createTicketReply(array $parameters = []): SupportTicketReply
    {
        $response = $this->client->post($this->getBaseUri(), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new SupportTicketReply($this->client, $json);
    }

    protected function getBaseUri(): string
    {
        return sprintf('/support/tickets/%s/replies', $this->ticketId);
    }

    protected function getSupportedFields(): array
    {
        return [
            SupportTicketReply::FIELD_ID,
            SupportTicketReply::FIELD_CREATED_BY,
            SupportTicketReply::FIELD_CREATED,
            SupportTicketReply::FIELD_GRAVATAR_ID,
            SupportTicketReply::FIELD_FROM_LINODE,
            SupportTicketReply::FIELD_DESCRIPTION,
        ];
    }

    protected function jsonToEntity(array $json): Entity
    {
        return new SupportTicketReply($this->client, $json);
    }
}