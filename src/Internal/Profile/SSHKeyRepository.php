<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <http://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Internal\Profile;

use Linode\Entity\Entity;
use Linode\Entity\Profile\SSHKey;
use Linode\Internal\AbstractRepository;
use Linode\Repository\Profile\SSHKeyRepositoryInterface;

class SSHKeyRepository extends AbstractRepository implements SSHKeyRepositoryInterface
{
    public function add(array $parameters): SSHKey
    {
        $this->checkParametersSupport($parameters);

        $response = $this->client->api($this->client::REQUEST_POST, $this->getBaseUri(), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new SSHKey($this->client, $json);
    }

    public function update(int $id, array $parameters): SSHKey
    {
        $this->checkParametersSupport($parameters);

        $response = $this->client->api($this->client::REQUEST_PUT, sprintf('%s/%s', $this->getBaseUri(), $id), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new SSHKey($this->client, $json);
    }

    public function delete(int $id): void
    {
        $this->client->api($this->client::REQUEST_DELETE, sprintf('%s/%s', $this->getBaseUri(), $id));
    }

    protected function getBaseUri(): string
    {
        return '/profile/sshkeys';
    }

    protected function getSupportedFields(): array
    {
        return [
            SSHKey::FIELD_ID,
            SSHKey::FIELD_LABEL,
            SSHKey::FIELD_SSH_KEY,
            SSHKey::FIELD_CREATED,
        ];
    }

    protected function jsonToEntity(array $json): Entity
    {
        return new SSHKey($this->client, $json);
    }
}
