<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\ObjectStorage\Repository;

use Linode\Entity;
use Linode\Internal\AbstractRepository;
use Linode\ObjectStorage\ObjectStorageKey;
use Linode\ObjectStorage\ObjectStorageKeyRepositoryInterface;

/**
 * @codeCoverageIgnore This class was autogenerated.
 */
class ObjectStorageKeyRepository extends AbstractRepository implements ObjectStorageKeyRepositoryInterface
{
    public function createObjectStorageKeys(array $parameters = []): ObjectStorageKey
    {
        $response = $this->client->post($this->getBaseUri(), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new ObjectStorageKey($this->client, $json);
    }

    public function updateObjectStorageKey(int $keyId, array $parameters = []): ObjectStorageKey
    {
        $response = $this->client->put(sprintf('%s/%s', $this->getBaseUri(), $keyId), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new ObjectStorageKey($this->client, $json);
    }

    public function deleteObjectStorageKey(int $keyId): void
    {
        $this->client->delete(sprintf('%s/%s', $this->getBaseUri(), $keyId));
    }

    protected function getBaseUri(): string
    {
        return '/object-storage/keys';
    }

    protected function getSupportedFields(): array
    {
        return [
            ObjectStorageKey::FIELD_ID,
            ObjectStorageKey::FIELD_LABEL,
            ObjectStorageKey::FIELD_ACCESS_KEY,
            ObjectStorageKey::FIELD_SECRET_KEY,
            ObjectStorageKey::FIELD_LIMITED,
            ObjectStorageKey::FIELD_BUCKET_ACCESS,
        ];
    }

    protected function jsonToEntity(array $json): Entity
    {
        return new ObjectStorageKey($this->client, $json);
    }
}
