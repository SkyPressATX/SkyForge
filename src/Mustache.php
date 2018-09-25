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
          'cache'   => $this->applySkyForgeConfigFilter($this->cache_filter_slug, $default['cache']),
          'loader'  => $this->applySkyForgeConfigFilter($this->path_filter_slug, $default['loader'])
        ];

        $config['loader'] = $this->createNewMustacheLoader($config['loader']);
        return $config;
    }

    /**
     * Create New Mustach Loader
     *
     * @method createNewMustacheLoader
     *
     * @since 0.1.0
     *
     * @link https://github.com/bobthecow/mustache.php/wiki/Template-Loading
     *
     * @param  string $path
     *
     * @return Mustache_Loader_FilesystemLoader
     */
    public function createNewMustacheLoader(string $path) : \Mustache_Loader_FilesystemLoader
    {
        $options = [
          'extension' => $this->applySkyForgeConfigFilter($this->extension_filter_slug, '.html')
        ];
        $loader = new \Mustache_Loader_FilesystemLoader($path, $options);

        return $loader;
    }

    /**
     * Get Default configurations
     *
     * @method getMustacheConfigDefaults
     *
     * @since 0.1.0
     *
     * @link https://github.com/khromov/mustache-wordpress-cache
     *
     * @return array
     */
    public function getMustacheConfigDefaults() : array
    {
        $defaults = [
        'cache'   => new \Khromov\Mustache_Cache\Mustache_Cache_WordPressCache,
        'loader'  => $this->getDefaultLoaderPath()
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

    /**
     * Get default loader path
     *
     * @method getDefaultLoaderPath
     *
     * @since 0.1.0
     *
     * @return string
     */
    public function getDefaultLoaderPath() : string
    {
        if (is_dir(get_stylesheet_directory() . '/templates')) {
            return get_stylesheet_directory() . '/templates';
        }
        if (is_dir(get_template_directory() . '/templates')) {
            return get_template_directory() . '/templates';
        }

        return get_stylesheet_directory();
    }
}
