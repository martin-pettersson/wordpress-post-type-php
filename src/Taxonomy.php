<?php

/*
 * Copyright (c) 2025 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace N7e\WordPress\PostType;

/**
 * Represents a WordPress post type taxonomy.
 */
abstract class Taxonomy
{
    /**
     * Taxonomy key.
     *
     * Must not exceed 32 characters.
     *
     * @var string
     */
    protected string $key;

    /**
     * Whether the taxonomy is intended for use publicly either via the admin
     * interface or by front-end users.
     *
     * @var bool
     */
    protected bool $public = false;

    /**
     * Whether the post type is hierarchical.
     *
     * @var bool
     */
    protected bool $hierarchical = false;

    /**
     * Whether the taxonomy is publicly queryable.
     *
     * @var bool
     */
    protected bool $publiclyQueryable = false;

    /**
     * Default term to use for the taxonomy.
     *
     * Term array keys:
     *
     * - name (string)
     * - slug (string)
     * - description (string)
     *
     * @var array|null
     */
    protected ?array $defaultTerm;

    /**
     * Whether terms in this taxonomy should be sorted in the order they are
     * provided to {@see \wp_set_object_terms()}.
     *
     * @var bool
     */
    protected bool $sorted = false;

    /**
     * Whether to generate and allow a UI for managing the taxonomy in the admin
     * panel.
     *
     * @var bool
     */
    protected bool $showUi = false;

    /**
     * Whether to show the taxonomy in the admin menu.
     *
     * - If true, the taxonomy is shown as a submenu of the object type menu.
     * - If false, no menu is shown.
     *
     * @var bool
     */
    protected bool $showInMenu = false;

    /**
     * Whether this taxonomy is available for selection in navigation menus.
     *
     * @var bool
     */
    protected bool $showInNavigationMenus = false;

    /**
     * Whether to include the taxonomy in the REST API.
     *
     * Set this to true for the taxonomy to be available in the block editor.
     *
     * @var bool
     */
    protected bool $showInRestApi = false;

    /**
     * Base path of the REST API route.
     *
     * @var string|null
     */
    protected ?string $restApiBase;

    /**
     * REST API route namespace .
     *
     * @var string|null
     */
    protected ?string $restApiNamespace;

    /**
     * REST API controller class name.
     *
     * @var string|null
     */
    protected ?string $restApiControllerClass;

    /**
     * Whether to list the taxonomy in the Tag Cloud Widget controls.
     *
     * @var bool
     */
    protected bool $tagCloud = false;

    /**
     * Whether to show taxonomy in the quick/bulk edit panel.
     *
     * @var bool
     */
    protected bool $quickEdit = false;

    /**
     * Whether to display a column for the taxonomy on its post type listing
     * screens.
     *
     * @var bool
     */
    protected bool $adminColumn = false;

    /**
     * Capabilities associated with the taxonomy.
     *
     * @var string[]
     */
    protected array $capabilities = [];

    /**
     * Whether/how rewrite rules will be handled.
     *
     * To prevent rewrites, set to false. To use {@see static::$key} as slug,
     * set to true. To specify rewrite rules, pass an array with any of these
     * keys:
     *
     * - slug (string)
     *   Customize the permastruct slug, defaults to {@see static::$key}.
     * - with_front (bool)
     *   Whether the permastruct should be prepended with WP_Rewrite::$front,
     *   default true.
     * - hierarchical (bool)
     *   Either hierarchical rewrite tag or not, default false.
     * - ep_mask (int)
     *   Endpoint mask to assign, defaults to EP_NONE.
     *
     * @var array|bool
     */
    protected array|bool $rewriteRules = true;

    /**
     * The query_var key for this taxonomy.
     *
     * Defaults to {@see static::$key}. If false, a taxonomy cannot be loaded at
     * ?{query_var}={term_slug}. If specified as a string, the query
     * ?{query_var}={term_slug} will be valid.
     *
     * @var string|bool
     */
    protected string|bool $queryParameterKey = true;

    /**
     * Retrieve a short description of the taxonomy.
     *
     * @return string Short description of the taxonomy.
     */
    abstract public function description(): string;

    /**
     * Retrieve the taxonomy labels.
     *
     * If empty, tag labels are inherited for non-hierarchical types and
     * category labels for hierarchical taxonomies.
     *
     * @see https://developer.wordpress.org/reference/functions/get_taxonomy_labels/
     * @return array Taxonomy labels.
     */
    abstract public function labels(): array;

    /**
     * Retrieve the taxonomy key.
     *
     * @return string Taxonomy key.
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * Determine whether the taxonomy is intended for use publicly either via
     * the admin interface or by front-end users.
     *
     * @return bool True if the taxonomy is intended for public use.
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * Determine whether the taxonomy is hierarchical.
     *
     * @return bool True if the taxonomy is hierarchical.
     */
    public function isHierarchical(): bool
    {
        return $this->hierarchical;
    }

    /**
     * Determine whether queries can be performed on the front-end for the
     * taxonomy.
     *
     * @return bool True if queries can be performed on the front-end for the
     *              taxonomy.
     */
    public function isPubliclyQueryable(): bool
    {
        return $this->publiclyQueryable;
    }

    /**
     * Retrieve the default term used for the taxonomy.
     *
     * @return array|bool Default term used for the taxonomy.
     */
    public function defaultTerm(): array|bool
    {
        return $this->defaultTerm ?? false;
    }

    /**
     * Determine whether terms in this taxonomy should be sorted in the order
     * they are provided to {@see \wp_set_object_terms()}.
     *
     * @return bool True if terms should be sorted.
     */
    public function isSorted(): bool
    {
        return $this->sorted;
    }

    /**
     * Determine whether taxonomy has a UI for managing taxonomy in the admin
     * panel.
     *
     * @return bool True if taxonomy has a UI for managing taxonomy in the admin
     *              panel.
     */
    public function hasUi(): bool
    {
        return $this->showUi;
    }

    /**
     * Determine whether taxonomy should be visible in the admin menu.
     *
     * @return bool True if taxonomy should be visible in the admin menu.
     */
    public function isVisibleInMenu(): bool
    {
        return $this->showInMenu;
    }

    /**
     * Determine whether taxonomy is available for selection in navigation menus.
     *
     * @return bool True if taxonomy is available for selection in navigation
     *              menus.
     */
    public function isVisibleInNavigationMenus(): bool
    {
        return $this->showInNavigationMenus;
    }

    /**
     * Determine whether taxonomy is included in the REST API.
     *
     * @return bool True if taxonomy is included in the REST API.
     */
    public function isIncludedInRestApi(): bool
    {
        return $this->showInRestApi;
    }

    /**
     * Retrieve the REST API route base path.
     *
     * @return string|bool REST API route base path.
     */
    public function restApiBase(): string|bool
    {
        return $this->restApiBase ?? false;
    }

    /**
     * Retrieve the REST API route namespace.
     *
     * @return string|bool REST API route namespace.
     */
    public function restApiNamespace(): string|bool
    {
        return $this->restApiNamespace ?? false;
    }

    /**
     * Retrieve the REST API controller class name.
     *
     * @return string|bool REST API controller class name.
     */
    public function restApiControllerClass(): string|bool
    {
        return $this->restApiControllerClass ?? false;
    }

    /**
     * Determine whether to list the taxonomy in the Tag Cloud Widget controls.
     *
     * @return bool True if the taxonomy should be listed in the Tag Cloud
     *              Widget controls.
     */
    public function isVisibleInTagCloudWidgetControls(): bool
    {
        return $this->tagCloud;
    }

    /**
     * Determine whether to show taxonomy in the quick/bulk edit panel.
     *
     * @return bool True if the taxonomy should be visible in quick/bulk edit
     *              panel.
     */
    public function isVisibleInQuickEdit(): bool
    {
        return $this->quickEdit;
    }

    /**
     * Determine whether to display a column for the taxonomy on its post type
     * listing screens.
     *
     * @return bool True if there should be a column for the taxonomy on its
     *              post type listing screens.
     */
    public function haveAdminColumn(): bool
    {
        return $this->adminColumn;
    }

    /**
     * Retrieve capabilities associated with this taxonomy.
     *
     * @return string[] Capabilities associated with this taxonomy.
     */
    public function capabilities(): array
    {
        return $this->capabilities;
    }

    /**
     * Determine how rewrite rules will be handled.
     *
     * @return array|bool How rewrite rules will be handled.
     */
    public function rewriteRules(): array|bool
    {
        return $this->rewriteRules;
    }

    /**
     * Retrieve the query_var key for this taxonomy.
     *
     * @return string|bool The query_var key for this taxonomy.
     */
    public function queryParameterKey(): string|bool
    {
        return $this->queryParameterKey;
    }
}
