<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <http://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Entity\NodeBalancers;

use Linode\Entity\TimeValue;
use Linode\LinodeClient;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \Linode\Entity\NodeBalancers\NodeBalancerStatsData
 */
final class NodeBalancerStatsDataTest extends TestCase
{
    protected LinodeClient $client;

    protected function setUp(): void
    {
        $this->client = $this->createMock(LinodeClient::class);
    }

    public function testProperties(): void
    {
        $data = [
            'connections' => [
                [1526391300000, 12],
            ],
            'traffic'     => [
                'in'  => [
                    [1521483600000, 2004.36],
                ],
                'out' => [
                    [1521484000000, 3928.91],
                ],
            ],
        ];

        $entity = new NodeBalancerStatsData($this->client, $data);

        self::assertCount(1, $entity->connections);
        self::assertInstanceOf(TimeValue::class, $entity->connections[0]);
        self::assertSame(1526391300000, $entity->connections[0]->time);
        self::assertSame(12.0, $entity->connections[0]->value);

        self::assertInstanceOf(NodeTraffic::class, $entity->traffic);
        self::assertCount(1, $entity->traffic->in);
        self::assertCount(1, $entity->traffic->out);

        self::assertNull($entity->unknown);
    }
}
