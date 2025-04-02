<?php

/*
 * Copyright (c) 2025 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace N7e\WordPress\PostType;

use WP_Post;

/**
 * Represents a WordPress post type meta box.
 */
abstract class MetaBox
{
    /**
     * Unique identifier used in the "id" attribute of the meta box.
     *
     * @var string
     */
    protected string $id;

    /**
     * Title of the meta box.
     *
     * @var string
     */
    protected string $title;

    /**
     * The context within the screen where the box should display.
     *
     * Available contexts vary from screen to screen. Post edit screen contexts
     * include 'normal', 'side', and 'advanced'. Comments screen contexts
     * include 'normal' and 'side'. Menus meta boxes (accordion sections) all
     * use the 'side' context.
     *
     * @var string
     */
    protected string $context = 'advanced';

    /**
     * The priority within the context where the box should show.
     *
     * Accepts high, core, default, or low.
     *
     * @var string
     */
    protected string $priority = 'default';

    /**
     * Retrieve a string representation of the rendered meta box.
     *
     * @param \WP_Post $post Post instance.
     * @return string String representation of the rendered meta box.
     */
    abstract public function render(WP_Post $post): string;

    /**
     * Retrieve the unique identifier.
     *
     * @return string Unique identifier.
     */
    public function id(): string
    {
        return $this->id;
    }

    /**
     * Retrieve the title of the meta box.
     *
     * @return string Title of the meta box.
     */
    public function title(): string
    {
        return $this->title;
    }

    /**
     * Retrieve the context within the screen where the box should display.
     *
     * @return string The context within the screen where the box should display.
     */
    public function context(): string
    {
        return $this->context;
    }

    /**
     * Retrieve the priority within the context where the box should show.
     *
     * @return string The priority within the context where the box should show.
     */
    public function priority(): string
    {
        return $this->priority;
    }
}
