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
use Linode\LinodeInstances\IPAddressRepositoryInterface;
use Linode\LinodeInstances\NetworkInformation;
use Linode\Networking\IPAddress;

/**
 * @codeCoverageIgnore This class was autogenerated.
 */
class IPAddressRepository extends AbstractRepository implements IPAddressRepositoryInterface
{
    /**
     * @param int $linodeId ID of the Linode to look up.
     */
    public function __construct(LinodeClient $client, protected int $linodeId)
    {
        parent::__construct($client);
    }

    public function getLinodeIPs(): NetworkInformation
    {
        $response = $this->client->get($this->getBaseUri());
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new NetworkInformation($this->client, $json);
    }

    public function getLinodeIP(string $address): IPAddress
    {
        $response = $this->client->get(sprintf('%s/%s', $this->getBaseUri(), $address));
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new IPAddress($this->client, $json);
    }

    public function addLinodeIP(array $parameters = []): IPAddress
    {
        $response = $this->client->post($this->getBaseUri(), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new IPAddress($this->client, $json);
    }

    public function updateLinodeIP(string $address, array $parameters = []): IPAddress
    {
        $response = $this->client->put(sprintf('%s/%s', $this->getBaseUri(), $address), $parameters);
        $contents = $response->getBody()->getContents();
        $json     = json_decode($contents, true);

        return new IPAddress($this->client, $json);
    }

    public function removeLinodeIP(string $address): void
    {
        $this->client->delete(sprintf('%s/%s', $this->getBaseUri(), $address));
    }

    protected function getBaseUri(): string
    {
        return sprintf('/linode/instances/%s/ips', $this->linodeId);
    }

    protected function getSupportedFields(): array
    {
        return [
            IPAddress::FIELD_ADDRESS,
            IPAddress::FIELD_TYPE,
            IPAddress::FIELD_PUBLIC,
            IPAddress::FIELD_RDNS,
            IPAddress::FIELD_REGION,
            IPAddress::FIELD_LINODE_ID,
            IPAddress::FIELD_GATEWAY,
            IPAddress::FIELD_SUBNET_MASK,
            IPAddress::FIELD_PREFIX,
        ];
    }

    protected function jsonToEntity(array $json): Entity
    {
        return new IPAddress($this->client, $json);
    }
}
