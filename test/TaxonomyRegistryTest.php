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

#[CoversClass(TaxonomyRegistry::class)]
final class TaxonomyRegistryTest extends TestCase
{
    private TaxonomyRegistry $registry;

    #[Before]
    public function setUp(): void
    {
        $this->registry = new TaxonomyRegistry();
    }

    #[Test]
    public function shouldBeEmptyByDefault(): void
    {
        $this->assertEmpty($this->registry->getIterator());
    }

    #[Test]
    public function shouldRegisterEntry(): void
    {
        $taxonomyMock = $this->getMockBuilder(Taxonomy::class)->getMock();

        $this->registry->register($taxonomyMock);

        $this->assertCount(1, $this->registry->getIterator());

        foreach ($this->registry as $taxonomy) {
            $this->assertSame($taxonomyMock, $taxonomy);
        }
    }
}
