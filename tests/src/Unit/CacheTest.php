<?php

declare(strict_types=1);

namespace Deviantintegral\Har\Tests\Unit;

use Deviantintegral\Har\Cache;

class CacheTest extends HarTestBase
{
    public function testSerializeWhenEmpty()
    {
        $cache = (new Cache());
        $this->assertFalse($cache->hasAfterRequest());
        $this->assertNull($cache->getAfterRequest());
        $this->assertFalse($cache->hasBeforeRequest());
        $this->assertNull($cache->getBeforeRequest());
        $this->assertFalse($cache->hasComment());
        $this->assertNull($cache->getComment());

        $serializer = $this->getSerializer();
        $serialized = $serializer->serialize($cache, 'json');
        $this->assertEquals(
            [],
            json_decode($serialized, true)
        );

        $deserialized = $serializer->deserialize(
            $serialized,
            Cache::class,
            'json'
        );
        $this->assertEquals($cache, $deserialized);
    }
}
