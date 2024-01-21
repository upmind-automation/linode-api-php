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

use Linode\Entity\Account\OAuthClient;
use Linode\Entity\Entity;
use Linode\Internal\AbstractRepository;
use Linode\Repository\Account\OAuthClientRepositoryInterface;

class OAuthClientRepository extends AbstractRepository implements OAuthClientRepositoryInterface
{
    public function create(array $parameters): OAuthClient
    {
        $this->checkParametersSupport($parameters);

        $response = $this->client->api($this->client::REQUEST_POST, $this->getBaseUri(), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new OAuthClient($this->client, $json);
    }

    public function update(string $id, array $parameters): OAuthClient
    {
        $this->checkParametersSupport($parameters);

        $response = $this->client->api($this->client::REQUEST_PUT, sprintf('%s/%s', $this->getBaseUri(), $id), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new OAuthClient($this->client, $json);
    }

    public function delete(string $id): void
    {
        $this->client->api($this->client::REQUEST_DELETE, sprintf('%s/%s', $this->getBaseUri(), $id));
    }

    public function resetSecret(string $id): OAuthClient
    {
        $response = $this->client->api($this->client::REQUEST_POST, sprintf('%s/%s/reset-secret', $this->getBaseUri(), $id));
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new OAuthClient($this->client, $json);
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
