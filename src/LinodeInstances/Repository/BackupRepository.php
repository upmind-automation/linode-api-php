<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <https://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\LinodeInstances\Repository;

use Linode\Entity;
use Linode\Internal\AbstractRepository;
use Linode\LinodeClient;
use Linode\LinodeInstances\Backup;
use Linode\LinodeInstances\BackupRepositoryInterface;

/**
 * @codeCoverageIgnore This class was autogenerated.
 */
class BackupRepository extends AbstractRepository implements BackupRepositoryInterface
{
    /**
     * @param int $linodeId The ID of the Linode the backups belong to.
     */
    public function __construct(LinodeClient $client, protected int $linodeId)
    {
        parent::__construct($client);
    }

    public function createSnapshot(array $parameters = []): Backup
    {
        $response = $this->client->post($this->getBaseUri(), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new Backup($this->client, $json);
    }

    public function cancelBackups(): void
    {
        $this->client->post(sprintf('%s/cancel', $this->getBaseUri()));
    }

    public function enableBackups(): void
    {
        $this->client->post(sprintf('%s/enable', $this->getBaseUri()));
    }

    public function restoreBackup(int $backupId, int $linode_id, bool $overwrite): void
    {
        $parameters = [
            'linode_id' => $linode_id,
            'overwrite' => $overwrite,
        ];

        $this->client->post(sprintf('%s/%s/restore', $this->getBaseUri(), $backupId), $parameters);
    }

    protected function getBaseUri(): string
    {
        return sprintf('/linode/instances/%s/backups', $this->linodeId);
    }

    protected function getSupportedFields(): array
    {
        return [
            Backup::FIELD_ID,
            Backup::FIELD_STATUS,
            Backup::FIELD_TYPE,
            Backup::FIELD_CREATED,
            Backup::FIELD_UPDATED,
            Backup::FIELD_FINISHED,
            Backup::FIELD_LABEL,
            Backup::FIELD_CONFIGS,
            Backup::FIELD_DISKS,
            Backup::FIELD_AVAILABLE,
        ];
    }

    protected function jsonToEntity(array $json): Entity
    {
        return new Backup($this->client, $json);
    }
}
