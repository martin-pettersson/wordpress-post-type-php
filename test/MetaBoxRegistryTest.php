<?php

/*
 * Copyright (c) 2025 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace N7e\WordPress\PostType;

use PHPUnit\Framework\Attributes\Before;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(MetaBoxRegistry::class)]
final class MetaBoxRegistryTest extends TestCase
{
    private MetaBoxRegistry $registry;

    #[Before]
    public function setUp(): void
    {
        $this->registry = new MetaBoxRegistry();
    }

    #[Test]
    public function shouldBeEmptyByDefault(): void
    {
        $this->assertEmpty($this->registry->getIterator());
    }

    #[Test]
    public function shouldRegisterEntry(): void
    {
        $metaBoxMock = $this->getMockBuilder(MetaBox::class)->getMock();

        $this->registry->register($metaBoxMock);

        $this->assertCount(1, $this->registry->getIterator());

        foreach ($this->registry as $metaBox) {
            $this->assertSame($metaBoxMock, $metaBox);
        }
    }
}
