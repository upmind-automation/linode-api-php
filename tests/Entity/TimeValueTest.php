<?php

// ---------------------------------------------------------------------
//
//  Copyright (C) 2018-2024 Artem Rodygin
//
//  You should have received a copy of the MIT License along with
//  this file. If not, see <http://opensource.org/licenses/MIT>.
//
// ---------------------------------------------------------------------

namespace Linode\Entity;

use Linode\LinodeInstances\TimeValue;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 *
 * @coversDefaultClass \Linode\LinodeInstances\TimeValue
 */
final class TimeValueTest extends TestCase
{
    public function testIsSet(): void
    {
        $entity = new TimeValue(1521483600000, 0.42);

        self::assertTrue(isset($entity->time));
        self::assertTrue(isset($entity->value));
        self::assertFalse(isset($entity->unknown));
    }

    public function testGet(): void
    {
        $entity = new TimeValue(1521483600000, 0.42);

        self::assertSame(1521483600000, $entity->time);
        self::assertSame(0.42, $entity->value);
        self::assertNull($entity->unknown);
    }
}
