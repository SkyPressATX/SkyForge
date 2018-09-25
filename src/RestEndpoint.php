<?php
namespace SkyForge;

/**
 * WP API Rest Endpoint
 *
 * @var string $api_prefix
 * @var int $api_version
 * @var string $api_namespace
 * @var string $api_endpoint
 * @var string $permalink
 */
class RestEndpoint
{
    /**
     * WP API Prefix
     *
     * @var string
     */
    public $api_prefix = 'wp';

    /**
     * WP API Version
     *
     * @var int
     */
    public $api_version = 2;

    /**
     * WP API Namespace
     *
     * @var string
     */
    public $api_namespace;

    /**
     * WP API Endpoint
     *
     * @var string
     */
    public $api_endpoint;

    /**
     * WP Permalink rule
     *
     * @var string
     */
    public $permalink;

    /**
     * Class Constructor
     *
     * @method __construct
     *
     * @since 0.1.0
     */
    public function __construct()
    {
        $this->api_namespace = "/{$this->api_prefix}/v{$this->api_version}";
        $this->permalink = get_option('permalink_structure');
    }

    /**
     * Get the proper Endpoint
     *
     * @method getEndpoint
     *
     * @since 0.1.0
     *
     * @param  int $id
     * @param  string $type
     *
     * @return string
     */
    public function getEndpoint(int $id = null, string $type) : string
    {
        $type = $this->getRestBase($type);
        $endpoint = "{$this->api_namespace}/{$type}";
        if (null !== $id || (int)0 !== $id) {
            $endpoint .= "/$id";
        }

        return $endpoint;
    }

    /**
     * Get the WP API Base
     *
     * @method getRestBase
     *
     * @since 0.1.0
     *
     * @link https://codex.wordpress.org/Function_Reference/get_post_type_object
     *
     * @param  string $type
     *
     * @return string
     */
    public function getRestBase(string $type) : string
    {
        $type_object = get_post_type_object($type);
        if (null === $type_object || empty($type_object) || ! is_object($type_object)) {
            return strtolower($type);
        }
        if (! property_exists($type_object, 'rest_base') || empty($type_object->rest_base)) {
            return strtolower($type);
        }
        return strtolower($type_object->rest_base);
    }
}
