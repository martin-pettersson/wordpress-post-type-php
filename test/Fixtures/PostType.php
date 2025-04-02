<?php

/*
 * Copyright (c) 2025 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace N7e\WordPress\PostType\Fixtures;

use N7e\WordPress;
use Override;

final class PostType extends WordPress\PostType\PostType
{
    protected string $key = 'key';

    #[Override]
    public function description(): string
    {
        return '';
    }

    #[Override]
    public function labels(): array
    {
        return [];
    }
}
