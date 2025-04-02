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

#[CoversClass(MetaBox::class)]
final class MetaBoxTest extends TestCase
{
    private MetaBox $metaBox;

    #[Before]
    public function setUp(): void
    {
        $this->metaBox = new Fixtures\MetaBox();
    }

    #[Test]
    public function shouldInitializeProperly(): void
    {
        $this->assertEquals('id', $this->metaBox->id());
        $this->assertEquals('title', $this->metaBox->title());
        $this->assertEquals('advanced', $this->metaBox->context());
        $this->assertEquals('default', $this->metaBox->priority());
    }
}
