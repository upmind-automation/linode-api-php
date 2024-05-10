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

use Linode\Account\OAuthClient;
use Linode\Account\OAuthClientRepositoryInterface;
use Linode\Entity;
use Linode\Internal\AbstractRepository;

/**
 * @codeCoverageIgnore This class was autogenerated.
 */
class OAuthClientRepository extends AbstractRepository implements OAuthClientRepositoryInterface
{
    public function createClient(array $parameters = []): OAuthClient
    {
        $response = $this->client->post($this->getBaseUri(), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new OAuthClient($this->client, $json);
    }

    public function updateClient(string $clientId, array $parameters = []): OAuthClient
    {
        $response = $this->client->put(sprintf('%s/%s', $this->getBaseUri(), $clientId), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new OAuthClient($this->client, $json);
    }

    public function deleteClient(string $clientId): void
    {
        $this->client->delete(sprintf('%s/%s', $this->getBaseUri(), $clientId));
    }

    public function resetClientSecret(string $clientId): OAuthClient
    {
        $response = $this->client->post(sprintf('%s/%s/reset-secret', $this->getBaseUri(), $clientId));
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new OAuthClient($this->client, $json);
    }

    public function getClientThumbnail(string $clientId): string
    {
        $response = $this->client->get(sprintf('%s/%s/thumbnail', $this->getBaseUri(), $clientId));

        return $response->getBody()->getContents();
    }

    public function setClientThumbnail(string $clientId, string $file): void
    {
        $options = [
            'body' => fopen($file, 'r'),
        ];

        $this->client->put(sprintf('%s/%s/thumbnail', $this->getBaseUri(), $clientId), [], $options);
    }

    protected function getBaseUri(): string
    {
        return '/account/oauth-clients';
    }

    protected function getSupportedFields(): array
    {
        return [
            OAuthClient::FIELD_ID,
            OAuthClient::FIELD_LABEL,
            OAuthClient::FIELD_STATUS,
            OAuthClient::FIELD_PUBLIC,
            OAuthClient::FIELD_REDIRECT_URI,
            OAuthClient::FIELD_SECRET,
            OAuthClient::FIELD_THUMBNAIL_URL,
        ];
    }

    protected function jsonToEntity(array $json): Entity
    {
        return new OAuthClient($this->client, $json);
    }
}