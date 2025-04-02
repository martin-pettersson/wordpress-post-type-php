<?php

/*
 * Copyright (c) 2025 Martin Pettersson
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace N7e\WordPress;

/**
 * Represents a WordPress post type.
 */
abstract class PostType
{
    /**
     * Registered post type meta boxes.
     *
     * @var \N7e\WordPress\MetaBoxCollection
     */
    public readonly MetaBoxCollection $metaBoxes;

    /**
     * Registered post type taxonomies.
     *
     * @var \N7e\WordPress\TaxonomyCollection
     */
    public readonly TaxonomyCollection $taxonomies;

    /**
     * Post type key.
     *
     * Must not exceed 20 characters and may only contain lowercase alphanumeric
     * characters, dashes, and underscores.
     *
     * @see https://developer.wordpress.org/reference/functions/sanitize_key/
     * @var string
     */
    protected string $key;

    /**
     * Whether the post type is intended for use publicly either via the admin
     * interface or by front-end users.
     *
     * @var bool
     */
    protected bool $public = false;

    /**
     * Whether the post type is hierarchical (e.g. page).
     *
     * @var bool
     */
    protected bool $hierarchical = false;

    /**
     * Whether to exclude posts of this post type from front-end search results.
     *
     * @var bool
     */
    protected bool $excludeFromSearch = true;

    /**
     * Whether queries can be performed on the front-end for the post type as
     * part of {@see parse_request()}.
     *
     * Endpoints would include:
     * - ?post_type={post_type_key}
     * - ?{post_type_key}={single_post_slug}
     * - ?{post_type_query_var}={single_post_slug}
     *
     * @var bool
     */
    protected bool $publiclyQueryable = false;

    /**
     * Whether to generate and allow a UI for managing the post type in the
     * admin panel.
     *
     * @var bool
     */
    protected bool $showUi = false;

    /**
     * Whether or where to show the post type in the admin menu.
     *
     * To be applicable, {@see static::$showUi} must be true.
     *
     * - If true, the post type is shown in its own top level menu.
     * - If false, no menu is shown.
     * - If a string of an existing top level menu (e.g. "tools.php" or
     *   "edit.php?post_type=page"), the post type will be placed in a submenu
     *   of that menu.
     *
     * @var string|bool
     */
    protected string|bool $showInMenu = false;

    /**
     * Where in the menu the post type should appear.
     *
     * To be applicable, {@see static::$showInMenu} must be true.
     *
     * @var int|null
     */
    protected ?int $menuPosition = null;

    /**
     * Whether this post type is available for selection in navigation menus.
     *
     * @var bool
     */
    protected bool $showInNavigationMenus = false;

    /**
     * Whether this post type is available via the admin bar.
     *
     * @var bool
     */
    protected bool $showInAdminBar = false;

    /**
     * Whether to include this post type in the REST API.
     *
     * Set this to true for the post type to be available in the block editor.
     *
     * @var bool
     */
    protected bool $showInRestApi = false;

    /**
     * Whether to use the internal default meta capability handling.
     *
     * @var bool
     */
    protected bool $useDefaultMetaCapabilityHandling = false;

    /**
     * Whether to allow this post type to be exported.
     *
     * @var bool
     */
    protected bool $exportable = false;

    /**
     * Whether to delete posts of this type when deleting the associated user.
     *
     * - If true, posts of this type belonging to the user will be moved to
     *   trash when the user is deleted.
     * - If false, posts of this type belonging to the user will *not* be
     *   trashed or deleted.
     * - If not set, posts of this type are trashed if post type supports the
     *   "author" feature. Otherwise, posts are not trashed or deleted.
     *
     * @var bool|null
     */
    protected ?bool $deleteWithUser = null;

    /**
     * Whether there should be post type archives, if a string, the archive slug
     * to use.
     *
     * Proper rewrite rules will be generated if {@see static::$rewriteRules} is
     * enabled.
     *
     * @var string|bool
     */
    protected string|bool $archive = false;

    /**
     * Icon to use in the menu.
     *
     * - Pass a base64-encoded SVG using a data URI, which will be colored to
     *   match the color scheme -- this should begin with
     *   "data:image/svg+xml;base64,".
     * - Pass the name of a Dashicons helper class to use a font icon, e.g.
     *   "dashicons-chart-pie".
     * - Pass "none" to leave div.wp-menu-image empty so an icon can be added
     *   via CSS.
     *
     * @var string
     */
    protected string $menuIcon = 'none';

    /**
     * Base path of the REST API route.
     *
     * @var string|null
     */
    protected ?string $restApiBase = null;

    /**
     * REST API controller class name.
     *
     * @var string|null
     */
    protected ?string $restApiControllerClass = null;

    /**
     * String used to build the read, edit and delete capabilities.
     *
     * It may be an array to allow for alternative plurals, e.g.
     * ['post', 'posts'].
     *
     * @var string|string[]
     */
    protected string|array $capabilityBase = 'post';

    /**
     * Capabilities associated with this post type.
     *
     * {@see static::$capabilityBase} is used as a base to create capabilities
     * by default.
     *
     * @var string[]
     */
    protected array $capabilities = [];

    /**
     * Supported post type feature(s).
     *
     * Core features include:
     * - title
     * - editor
     * - comments
     * - revisions
     * - trackbacks
     * - author
     * - excerpt
     * - page-attributes
     * - thumbnail
     * - custom-fields
     * - post-formats
     *
     * Additionally, the "revisions" feature dictates whether the post type will
     * store revisions, and the "comments" feature dictates whether the comments
     * count will show on the edit screen. A feature can also be specified as an
     * array of arguments to provide additional information about supporting
     * that feature. Example: ['my_feature' => ['field' => 'value']].
     *
     * @var array
     */
    protected array $features = [
        'title',
        'editor'
    ];

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
     * - feeds (bool)
     *   Whether the feed permastruct should be built for this post type,
     *   default is value of {@see static::$hasArchive}.
     * - pages (bool)
     *   Whether the permastruct should provide for pagination, default true.
     * - ep_mask (int)
     *   Endpoint mask to assign. If not specified and {@see static::endpointMask}
     *   is set, inherits from {@see static::endpointMask}. If not specified and
     *   {@see static::endpointMask} is not set, defaults to EP_PERMALINK.
     *
     * @var array|bool
     */
    protected array|bool $rewriteRules = true;

    /**
     * The rewrite endpoint bitmask.
     *
     * @var int|null
     */
    protected ?int $endpointMask = null;

    /**
     * The query_var key for this post type.
     *
     * Defaults to {@see static::$key}. If false, a post type cannot be loaded
     * at ?{query_var}={post_slug}. If specified as a string, the query
     * ?{query_var}={post_slug} will be valid.
     *
     * @var string|bool
     */
    protected string|bool $queryParameterKey = true;

    /**
     * Blocks used as the default initial state of an editor session.
     *
     * Each item should be an array containing block name and optional
     * attributes.
     *
     * @var array
     */
    protected array $templateBlocks = [];

    /**
     * Whether the block template should be locked if {@see static::$templateBlocks}
     * is set.
     *
     * - If set to "all", the user is unable to insert new blocks, move existing
     *   blocks and delete blocks.
     * - If set to "insert", the user is able to move existing blocks but is
     *   unable to insert new blocks and delete blocks.
     *
     * @var string|bool
     */
    protected string|bool $lockTemplate = false;

    /**
     * Create a new post type instance.
     */
    public function __construct()
    {
        $this->metaBoxes = new MetaBoxCollection();
        $this->taxonomies = new TaxonomyCollection();
    }

    /**
     * Retrieve a short description of the post type.
     *
     * @return string Short description of the post type.
     */
    abstract public function description(): string;

    /**
     * Retrieve the post type labels.
     *
     * If empty, post labels are inherited for non-hierarchical types and page
     * labels for hierarchical types.
     *
     * @see https://developer.wordpress.org/reference/functions/get_post_type_labels/
     * @return array Post type labels.
     */
    abstract public function labels(): array;

    /**
     * Retrieve the post type key.
     *
     * @return string Post type key.
     */
    public function key(): string
    {
        return $this->key;
    }

    /**
     * Determine whether the post type is intended for use publicly either via
     * the admin interface or by front-end users.
     *
     * @return bool True if the post type is intended for public use.
     */
    public function isPublic(): bool
    {
        return $this->public;
    }

    /**
     * Determine whether the post type is hierarchical (e.g. page).
     *
     * @return bool True if the post type is hierarchical.
     */
    public function isHierarchical(): bool
    {
        return $this->hierarchical;
    }

    /**
     * Determine whether post type is included in front-end search results.
     *
     * @return bool True if post type is included in front-end search results.
     */
    public function isIncludedInSearch(): bool
    {
        return ! $this->excludeFromSearch;
    }

    /**
     * Determine whether queries can be performed on the front-end for the post
     * type.
     *
     * @return bool True if queries can be performed on the front-end for the
     *              post type.
     */
    public function isPubliclyQueryable(): bool
    {
        return $this->publiclyQueryable;
    }

    /**
     * Determine whether post type has a UI for managing post type in the admin
     * panel.
     *
     * @return bool True if post type has a UI for managing post type in the
     *              admin panel.
     */
    public function hasUi(): bool
    {
        return $this->showUi;
    }

    /**
     * Determine where to show post type in the admin menu.
     *
     * @return string|bool Admin menu location.
     */
    public function menuLocation(): string|bool
    {
        return $this->showInMenu;
    }

    /**
     * Retrieve the menu position the post type should appear.
     *
     * @return int|null Menu position the post type should appear.
     */
    public function menuPosition(): ?int
    {
        return $this->menuPosition;
    }

    /**
     * Retrieve the icon to use in the menu.
     *
     * @return string Icon to use in the menu.
     */
    public function menuIcon(): string
    {
        return $this->menuIcon;
    }

    /**
     * Determine whether post type is available for selection in navigation
     * menus.
     *
     * @return bool True if post type is available for selection in navigation
     *              menus.
     */
    public function isVisibleInNavigationMenus(): bool
    {
        return $this->showInNavigationMenus;
    }

    /**
     * Determine whether post type is available via the admin bar.
     *
     * @return bool True if post type is available via the admin bar.
     */
    public function isVisibleInAdminBar(): bool
    {
        return $this->showInAdminBar;
    }

    /**
     * Determine whether post type is included in the REST API.
     *
     * @return bool True if post type is included in the REST API.
     */
    public function isIncludedInRestApi(): bool
    {
        return $this->showInRestApi;
    }

    /**
     * Determine whether post type uses the internal default meta capability
     * handling.
     *
     * @return bool True if post type uses the internal default meta capability
     *              handling.
     */
    public function isUsingDefaultMetaCapabilityHandling(): bool
    {
        return $this->useDefaultMetaCapabilityHandling;
    }

    /**
     * Determine whether post type can be exported.
     *
     * @return bool True if post type can be exported.
     */
    public function canBeExported(): bool
    {
        return $this->exportable;
    }

    /**
     * Determine whether posts of this type are deleted when deleting the
     * associated user.
     *
     * @return bool|null True if posts of this type are deleted when deleting
     *                   the associated user.
     */
    public function isDeletedWithUser(): ?bool
    {
        return $this->deleteWithUser;
    }

    /**
     * Determine whether there should be post type archives, if a string, the
     * archive slug to use.
     *
     * @return string|bool True if there should be post type archives, if a
     *                     string, the archive slug to use.
     */
    public function archive(): string|bool
    {
        return $this->archive;
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
     * Retrieve the REST API controller class name.
     *
     * @return string|bool REST API controller class name.
     */
    public function restApiControllerClass(): string|bool
    {
        return $this->restApiControllerClass ?? false;
    }

    /**
     * Retrieve string used to build the read, edit and delete capabilities.
     *
     * @return string|array String used to build the read, edit and delete
     *                      capabilities.
     */
    public function capabilityBase(): string|array
    {
        return $this->capabilityBase;
    }

    /**
     * Retrieve capabilities associated with this post type.
     *
     * @return string[] Capabilities associated with this post type.
     */
    public function capabilities(): array
    {
        return $this->capabilities;
    }

    /**
     * Retrieve supported post type feature(s).
     *
     * @return array Supported post type feature(s).
     */
    public function features(): array
    {
        return $this->features;
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
     * Retrieve the query_var key for this post type.
     *
     * @return string|bool The query_var key for this post type.
     */
    public function queryParameterKey(): string|bool
    {
        return $this->queryParameterKey;
    }

    /**
     * Retrieve blocks used as the default initial state of an editor session.
     *
     * @return array Blocks use as the default initial state of an editor
     *               session.
     */
    public function templateBlocks(): array
    {
        return $this->templateBlocks;
    }

    /**
     * Determine post type template lock strategy.
     *
     * @return string|bool Post type template lock strategy.
     */
    public function templateLockStrategy(): string|bool
    {
        return $this->lockTemplate;
    }
}
