<?php

namespace SkyForge;

/**
 * SkyForge Mustache Instance
 *
 * @since 0.1.0
 *
 * @var string $cache_filter_slug
 * @var string $path_filter_slug
 * @var string $extension_filter_slug
 * @var string $pragmas_filter_slug
 * @var Mustache_Engine $mustache
 * @var array $mustache_config
 * @var self $instance
 */
class Mustache
{
    /**
     * Cache filter Slug
     *
     * @var string
     */
    public $cache_filter_slug = 'skyforge_cache_adapter';

    /**
     * Template Path filter slug
     *
     * @var string
     */
    public $path_filter_slug = 'skyforge_template_path';

    /**
     * Mustache Pramas filter slug
     *
     * @var string
     */
    public $pragmas_filter_slug = 'skyforge_pragmas';

    /**
     * Template Extension filter slug
     *
     * @var string
     */
    public $extension_filter_slug = 'skyforge_template_extension';

    /**
     * Mustache Engine
     *
     * @var Mustache_Engine
     */
    public $mustache;

    /**
     * Mustache Engine configuration
     *
     * @var array
     */
    public $mustache_config;

    /**
     * Class Instance
     *
     * @var self
     */
    public static $instance = null;

    /**
     * Class constructor
     *
     * @method __construct
     *
     * @since 0.1.0
     */
    private function __construct()
    {
        $this->mustache_config = $this->getMustacheConfig();
        $this->mustache = $this->initMustacheEngine();
    }

    /**
     * Initialize Class
     *
     * @method int
     *
     * @since 0.1.0
     *
     * @return Mustache_Engine
     */
    public static function init() : \Mustache_Engine
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance->mustache;
    }

    /**
     * Initilize the Mustache Engine
     *
     * @method initMustacheEngine
     *
     * @since 0.1.0
     *
     * @link https://github.com/bobthecow/mustache.php
     *
     * @return Mustache_Engine
     */
    public function initMustacheEngine() : \Mustache_Engine
    {
        return new \Mustache_Engine($this->mustache_config);
    }

    /**
     * Get Mustache Configuration
     *
     * @method getMustacheConfig
     *
     * @since 0.1.0
     *
     * @return array
     */
    public function getMustacheConfig() : array
    {
        $default = $this->getMustacheConfigDefaults();
        $config = [
          'pragmas'     => $this->applySkyForgeConfigFilter($this->pragmas_filter_slug, $default['pragmas']),
          'cache'       => $this->applySkyForgeConfigFilter($this->cache_filter_slug, $default['cache']),
          'loader'      => $this->applySkyForgeConfigFilter($this->path_filter_slug, null)
        ];

        $config['loader']   = $this->createNewMustacheLoader($config['loader']);
        return $config;
    }

    /**
     * Create New Mustach Loader
     *
     * @method createNewMustacheLoader
     *
     * @since 0.1.0
     *
     * @link https://github.com/bobthecow/mustache.php/wiki/Template-Loading#filesystem-loader
     *
     * @param  string $path
     *
     * @return mixed
     */
    public function createNewMustacheLoader(string $path = null)
    {
        $options = [
          'extension' => $this->applySkyForgeConfigFilter($this->extension_filter_slug, '.html')
        ];
        if (null === $path) {
            return $this->createMustacheCascadingLoader($options);
        }

        return new \Mustache_Loader_FilesystemLoader($path, $options);
    }

    /**
     * Create Mustache Cascading Loader
     *
     * @method createMustacheCascadingLoader
     *
     * @since 0.1.0
     *
     * @link https://github.com/bobthecow/mustache.php/wiki/Template-Loading#cascading-loader
     * @link https://github.com/bobthecow/mustache.php/wiki/Template-Loading#filesystem-loader
     *
     * @param  array $options
     *
     * @return Mustache_Loader_CascadingLoader
     */
    public function createMustacheCascadingLoader(array $options) : \Mustache_Loader_CascadingLoader
    {
        $paths = [
            new \Mustache_Loader_FilesystemLoader(get_stylesheet_directory() . '/templates', $options),
            new \Mustache_Loader_FilesystemLoader(get_template_directory() . '/templates', $options)
        ];
        return new \Mustache_Loader_CascadingLoader($paths);
    }

    /**
     * Get Default configurations
     *
     * @method getMustacheConfigDefaults
     *
     * @since 0.1.0
     *
     * @link https://github.com/bobthecow/mustache.php/wiki/Pragmas
     * @link https://github.com/khromov/mustache-wordpress-cache
     *
     * @return array
     */
    public function getMustacheConfigDefaults() : array
    {
        $defaults = [
            'pragmas'     => [],
            'cache'       => new \Khromov\Mustache_Cache\Mustache_Cache_WordPressCache
        ];

        return $defaults;
    }

    /**
     * Apply SkyForge Config Filters
     *
     * @method applySkyForgeConfigFilter
     *
     * @since 0.1.0
     *
     * @param  string $filter
     * @param  mixed $default
     *
     * @return mixed
     */
    public function applySkyForgeConfigFilter(string $filter, $default)
    {
        $filtered = apply_filters($filter, $default);
        return $filtered;
    }
}
