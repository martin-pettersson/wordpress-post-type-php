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

#[CoversClass(Taxonomy::class)]
final class TaxonomyTest extends TestCase
{
    private Taxonomy $taxonomy;

    #[Before]
    public function setUp(): void
    {
        $this->taxonomy = new Fixtures\Taxonomy();
    }

    #[Test]
    public function shouldInitializeProperly(): void
    {
        $this->assertEquals('key', $this->taxonomy->key());
        $this->assertEmpty($this->taxonomy->description());
        $this->assertEmpty($this->taxonomy->labels());
        $this->assertFalse($this->taxonomy->isPublic());
        $this->assertFalse($this->taxonomy->isHierarchical());
        $this->assertFalse($this->taxonomy->isPubliclyQueryable());
        $this->assertFalse($this->taxonomy->hasUi());
        $this->assertFalse($this->taxonomy->isVisibleInNavigationMenus());
        $this->assertFalse($this->taxonomy->isIncludedInRestApi());
        $this->assertEmpty($this->taxonomy->defaultTerm());
        $this->assertFalse($this->taxonomy->isSorted());
        $this->assertFalse($this->taxonomy->isVisibleInMenu());
        $this->assertFalse($this->taxonomy->restApiBase());
        $this->assertFalse($this->taxonomy->restApiNamespace());
        $this->assertFalse($this->taxonomy->restApiControllerClass());
        $this->assertFalse($this->taxonomy->isVisibleInTagCloudWidgetControls());
        $this->assertFalse($this->taxonomy->isVisibleInQuickEdit());
        $this->assertFalse($this->taxonomy->haveAdminColumn());
        $this->assertEmpty($this->taxonomy->capabilities());
        $this->assertTrue($this->taxonomy->rewriteRules());
        $this->assertTrue($this->taxonomy->queryParameterKey());
    }
}
