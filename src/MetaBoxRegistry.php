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
 * Represents meta box registry.
 */
final class MetaBoxRegistry implements IteratorAggregate
{
    /**
     * Registered meta boxes.
     *
     * @var \N7e\WordPress\PostType\MetaBox[]
     */
    private array $metaBoxes = [];

    /**
     * Register given meta box.
     *
     * @param \N7e\WordPress\PostType\MetaBox $metaBox Arbitrary meta box.
     * @return \N7e\WordPress\PostType\MetaBoxRegistry Same instance for method chaining.
     */
    public function register(MetaBox $metaBox): MetaBoxRegistry
    {
        $this->metaBoxes[] = $metaBox;

        return $this;
    }

    #[Override]
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->metaBoxes);
    }
}
