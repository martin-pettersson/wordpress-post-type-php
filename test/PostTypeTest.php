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

#[CoversClass(PostType::class)]
final class PostTypeTest extends TestCase
{
    private PostType $postType;

    #[Before]
    public function setUp(): void
    {
        $this->postType = new Fixtures\PostType();
    }

    #[Test]
    public function shouldInitializeProperly(): void
    {
        $this->assertEmpty($this->postType->taxonomies->getIterator());
        $this->assertEmpty($this->postType->metaBoxes->getIterator());
        $this->assertEquals('key', $this->postType->key());
        $this->assertEmpty($this->postType->description());
        $this->assertEmpty($this->postType->labels());
        $this->assertFalse($this->postType->isPublic());
        $this->assertFalse($this->postType->isHierarchical());
        $this->assertFalse($this->postType->isIncludedInSearch());
        $this->assertFalse($this->postType->isPubliclyQueryable());
        $this->assertFalse($this->postType->hasUi());
        $this->assertFalse($this->postType->isVisibleInNavigationMenus());
        $this->assertFalse($this->postType->isVisibleInAdminBar());
        $this->assertFalse($this->postType->isIncludedInRestApi());
        $this->assertFalse($this->postType->isUsingDefaultMetaCapabilityHandling());
        $this->assertFalse($this->postType->canBeExported());
        $this->assertNull($this->postType->isDeletedWithUser());
        $this->assertFalse($this->postType->archive());
        $this->assertFalse($this->postType->menuLocation());
        $this->assertFalse($this->postType->restApiBase());
        $this->assertFalse($this->postType->restApiControllerClass());
        $this->assertNull($this->postType->menuPosition());
        $this->assertEquals('none', $this->postType->menuIcon());
        $this->assertEquals('post', $this->postType->capabilityBase());
        $this->assertEmpty($this->postType->capabilities());
        $this->assertEquals(['title', 'editor'], $this->postType->features());
        $this->assertTrue($this->postType->rewriteRules());
        $this->assertTrue($this->postType->queryParameterKey());
        $this->assertEmpty($this->postType->templateBlocks());
        $this->assertFalse($this->postType->templateLockStrategy());
    }
}
