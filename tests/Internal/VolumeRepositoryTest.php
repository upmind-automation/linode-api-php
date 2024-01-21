<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <http://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Internal;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Response;
use Linode\Entity\Volume;
use Linode\LinodeClient;
use Linode\ReflectionTrait;
use Linode\Repository\VolumeRepositoryInterface;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \Linode\Internal\VolumeRepository
 */
final class VolumeRepositoryTest extends TestCase
{
    use ReflectionTrait;

    protected VolumeRepositoryInterface $repository;

    protected function setUp(): void
    {
        $client = new LinodeClient();

        $this->repository = new VolumeRepository($client);
    }

    public function testCreate(): void
    {
        $request = [
            'json' => [
                'region'    => null,
                'linode_id' => 12346,
                'size'      => 20,
                'label'     => 'my-volume',
                'config_id' => 23456,
            ],
        ];

        $response = <<<'JSON'
                        {
                            "id": 12345,
                            "label": "my-volume",
                            "filesystem_path": "/dev/disk/by-id/scsi-0Linode_Volume_my-volume",
                            "status": "active",
                            "size": 20,
                            "region": "us-east",
                            "linode_id": 12346,
                            "created": "2018-01-01T00:01:01",
                            "updated": "2018-01-01T00:01:01"
                        }
            JSON;

        $client = $this->createMock(Client::class);
        $client
            ->method('request')
            ->willReturnMap([
                ['POST', 'https://api.linode.com/v4/volumes', $request, new Response(200, [], $response)],
            ])
        ;

        /** @var Client $client */
        $repository = $this->mockRepository($client);

        $entity = $repository->create([
            Volume::FIELD_REGION    => null,
            Volume::FIELD_LINODE_ID => 12346,
            Volume::FIELD_SIZE      => 20,
            Volume::FIELD_LABEL     => 'my-volume',
            Volume::FIELD_CONFIG_ID => 23456,
        ]);

        self::assertInstanceOf(Volume::class, $entity);
        self::assertSame(12345, $entity->id);
        self::assertSame('my-volume', $entity->label);
    }

    public function testUpdate(): void
    {
        $request = [
            'json' => [
                'label'     => 'my-volume',
                'size'      => 30,
                'linode_id' => 12346,
            ],
        ];

        $response = <<<'JSON'
                        {
                            "id": 12345,
                            "label": "my-volume",
                            "filesystem_path": "/dev/disk/by-id/scsi-0Linode_Volume_my-volume",
                            "status": "active",
                            "size": 30,
                            "region": "us-east",
                            "linode_id": 12346,
                            "created": "2018-01-01T00:01:01",
                            "updated": "2018-01-01T00:01:01"
                        }
            JSON;

        $client = $this->createMock(Client::class);
        $client
            ->method('request')
            ->willReturnMap([
                ['PUT', 'https://api.linode.com/v4/volumes/12345', $request, new Response(200, [], $response)],
            ])
        ;

        /** @var Client $client */
        $repository = $this->mockRepository($client);

        $entity = $repository->update(12345, [
            Volume::FIELD_LABEL     => 'my-volume',
            Volume::FIELD_SIZE      => 30,
            Volume::FIELD_LINODE_ID => 12346,
        ]);

        self::assertInstanceOf(Volume::class, $entity);
        self::assertSame(12345, $entity->id);
        self::assertSame('my-volume', $entity->label);
    }

    public function testDelete(): void
    {
        $client = $this->createMock(Client::class);
        $client
            ->method('request')
            ->willReturnMap([
                ['DELETE', 'https://api.linode.com/v4/volumes/12345', [], new Response(200, [], null)],
            ])
        ;

        /** @var Client $client */
        $repository = $this->mockRepository($client);

        $repository->delete(12345);

        self::assertTrue(true);
    }

    public function testClone(): void
    {
        $request = [
            'json' => [
                'label' => 'my-volume',
            ],
        ];

        $client = $this->createMock(Client::class);
        $client
            ->method('request')
            ->willReturnMap([
                ['POST', 'https://api.linode.com/v4/volumes/12345/clone', $request, new Response(200, [], null)],
            ])
        ;

        /** @var Client $client */
        $repository = $this->mockRepository($client);

        $repository->clone(12345, [
            Volume::FIELD_LABEL => 'my-volume',
        ]);

        self::assertTrue(true);
    }

    public function testResize(): void
    {
        $request = [
            'json' => [
                'size' => 30,
            ],
        ];

        $client = $this->createMock(Client::class);
        $client
            ->method('request')
            ->willReturnMap([
                ['POST', 'https://api.linode.com/v4/volumes/12345/resize', $request, new Response(200, [], null)],
            ])
        ;

        /** @var Client $client */
        $repository = $this->mockRepository($client);

        $repository->resize(12345, [
            Volume::FIELD_SIZE => 30,
        ]);

        self::assertTrue(true);
    }

    public function testAttach(): void
    {
        $request = [
            'json' => [
                'linode_id' => 12346,
                'config_id' => 23456,
            ],
        ];

        $response = <<<'JSON'
                        {
                            "id": 12345,
                            "label": "my-volume",
                            "filesystem_path": "/dev/disk/by-id/scsi-0Linode_Volume_my-volume",
                            "status": "active",
                            "size": 30,
                            "region": "us-east",
                            "linode_id": 12346,
                            "created": "2018-01-01T00:01:01",
                            "updated": "2018-01-01T00:01:01"
                        }
            JSON;

        $client = $this->createMock(Client::class);
        $client
            ->method('request')
            ->willReturnMap([
                ['POST', 'https://api.linode.com/v4/volumes/12345/attach', $request, new Response(200, [], $response)],
            ])
        ;

        /** @var Client $client */
        $repository = $this->mockRepository($client);

        $entity = $repository->attach(12345, [
            Volume::FIELD_LINODE_ID => 12346,
            Volume::FIELD_CONFIG_ID => 23456,
        ]);

        self::assertInstanceOf(Volume::class, $entity);
        self::assertSame(12345, $entity->id);
        self::assertSame('my-volume', $entity->label);
    }

    public function testDetach(): void
    {
        $client = $this->createMock(Client::class);
        $client
            ->method('request')
            ->willReturnMap([
                ['POST', 'https://api.linode.com/v4/volumes/12345/detach', [], new Response(200, [], null)],
            ])
        ;

        /** @var Client $client */
        $repository = $this->mockRepository($client);

        $repository->detach(12345);

        self::assertTrue(true);
    }

    public function testGetBaseUri(): void
    {
        $expected = '/volumes';

        self::assertSame($expected, $this->callMethod($this->repository, 'getBaseUri'));
    }

    public function testGetSupportedFields(): void
    {
        $expected = [
            'id',
            'label',
            'status',
            'size',
            'region',
            'linode_id',
            'config_id',
        ];

        self::assertSame($expected, $this->callMethod($this->repository, 'getSupportedFields'));
    }

    public function testJsonToEntity(): void
    {
        self::assertInstanceOf(Volume::class, $this->callMethod($this->repository, 'jsonToEntity', [[]]));
    }

    protected function mockRepository(Client $client): VolumeRepositoryInterface
    {
        $linodeClient = new LinodeClient();
        $this->setProperty($linodeClient, 'client', $client);

        return new VolumeRepository($linodeClient);
    }
}
