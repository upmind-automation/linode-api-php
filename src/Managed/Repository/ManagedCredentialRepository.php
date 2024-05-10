<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Managed\Repository;

use Linode\Entity;
use Linode\Internal\AbstractRepository;
use Linode\Managed\ManagedCredential;
use Linode\Managed\ManagedCredentialRepositoryInterface;

/**
 * @codeCoverageIgnore This class was autogenerated.
 */
class ManagedCredentialRepository extends AbstractRepository implements ManagedCredentialRepositoryInterface
{
    public function createManagedCredential(array $parameters = []): ManagedCredential
    {
        $response = $this->client->post($this->getBaseUri(), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new ManagedCredential($this->client, $json);
    }

    public function updateManagedCredential(int $credentialId, array $parameters = []): ManagedCredential
    {
        $response = $this->client->put(sprintf('%s/%s', $this->getBaseUri(), $credentialId), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new ManagedCredential($this->client, $json);
    }

    public function updateManagedCredentialUsernamePassword(int $credentialId, ?string $username, string $password): void
    {
        $parameters = [
            'username' => $username,
            'password' => $password,
        ];

        $this->client->post(sprintf('%s/%s/update', $this->getBaseUri(), $credentialId), $parameters);
    }

    public function deleteManagedCredential(int $credentialId): void
    {
        $this->client->post(sprintf('%s/%s/revoke', $this->getBaseUri(), $credentialId));
    }

    public function viewManagedSSHKey(): string
    {
        $response = $this->client->get(sprintf('%s/sshkey', $this->getBaseUri()));
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return $json['ssh_key'];
    }

    protected function getBaseUri(): string
    {
        return '/managed/credentials';
    }

    protected function getSupportedFields(): array
    {
        return [
            ManagedCredential::FIELD_ID,
            ManagedCredential::FIELD_LABEL,
            ManagedCredential::FIELD_LAST_DECRYPTED,
        ];
    }

    protected function jsonToEntity(array $json): Entity
    {
        return new ManagedCredential($this->client, $json);
    }
}