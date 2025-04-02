<?php

/*
 * Copyright (c) 2025 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace N7e\WordPress\PostType;

use ArrayIterator;
use IteratorAggregate;
use Override;
use Traversable;

/**
 * Represents taxonomy registry.
 */
final class TaxonomyRegistry implements IteratorAggregate
{
    /**
     * Registered taxonomies.
     *
     * @var \N7e\WordPress\PostType\Taxonomy[]
     */
    private array $taxonomies = [];

    /**
     * Register given taxonomy.
     *
     * @param \N7e\WordPress\PostType\Taxonomy $taxonomy Arbitrary taxonomy.
     * @return \N7e\WordPress\PostType\TaxonomyRegistry Same instance for method chaining.
     */
    public function register(Taxonomy $taxonomy): TaxonomyRegistry
    {
        $this->taxonomies[] = $taxonomy;

        return $this;
    }

    #[Override]
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->taxonomies);
    }
}
