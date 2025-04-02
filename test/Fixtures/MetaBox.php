<?php

/*
 * Copyright (c) 2025 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace N7e\WordPress\PostType\Fixtures;

use N7e\WordPress\PostType;
use Override;
use WP_Post;

final class MetaBox extends PostType\MetaBox
{
    protected string $id = 'id';
    protected string $title = 'title';

    #[Override]
    public function render(WP_Post $post): string
    {
        return '';
    }
}
