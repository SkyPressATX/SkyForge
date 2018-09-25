<?php
namespace SkyForge;

/**
 * SkyForge Initialize Class
 *
 * @since 0.1.0
 *
 * @var \SkyForge\Mustache $mustache
 * @var int $post_id
 * @var array $template_map
 */
class Init
{
    /**
     * Mustache Instance
     *
     * @since 0.1.0
     *
     * @var \SkyForge\Mustache
     */
    public $mustache;

    /**
     * Post ID
     *
     * @since 0.1.0
     *
     * @var int
     */
    public $post_id;

    /**
     * Template Map
     *
     * @since 0.1.0
     *
     * @var array
     */
    public $template_map;

    /**
     * Init constructor
     *
     * @method __construct
     *
     * @since 0.1.0
     *
     */
    public function __construct()
    {
        $this->mustache = Mustache::init();
    }

    /**
     * Render the template
     *
     * @method render
     *
     * @since 0.1.0
     *
     * @return string
     */
    public function render() : string
    {
        $this->template_map = $this->getTemplateMap();
        $post_data = $this->getPostData();
        $template = $this->getTemplate($post_data);
        return '<body>' . $template->render($post_data) . '</body>';
    }

    /**
     * Get Template
     *
     * @method getTemplate
     *
     * @since 0.1.0
     *
     * @param  object $post_data
     *
     * @return object
     */
    public function getTemplate(object $post_data) : object
    {
        // $slug = $this->getTemplateSlug($post_data);
        // $template = $this->template_map[$slug];
        return $this->mustache->loadTemplate('page');
    }

    /**
     * Get the Templage Slug
     *
     * @method getTemplateSlug
     *
     * @since 0.1.0
     *
     * @param  object $post_data
     *
     * @return string
     */
    public function getTemplateSlug(object $post_data) : string
    {
        if (! property_exists($post_data, 'type')) {
            return 'page';
        }
        if (! isset($this->template_map[$post_data->type])) {
            return 'page';
        }
        return $this->template_map[$post_data->type];
    }

    /**
     * Get the Template Map
     *
     * @method getTemplateMap
     *
     * @since 0.1.0
     *
     * @return array
     */
    public function getTemplateMap() : array
    {
        $default = [
          'page'  => 'page',
          'post'  => 'post'
        ];
        $filtered = apply_filters('skyforge_template_map', []);
        $map = array_merge($default, $filtered);
        return $map;
    }

    /**
     * Get Data from Rest Request based on slug
     *
     * @method getPostData
     *
     * @since 0.1.0
     *
     * @return object $data
     */
    public function getPostData() : object
    {
        $post = get_post();
        $api_endpoint = $this->determineApiEndpoint($post);
        $request      = $this->getNewRestRequest($api_endpoint);
        $data         = $this->getDataFromRestServer($request);
        return $data;
    }

    /**
     * Determine which API endpoint to use
     *
     * @method determineApiEndpoint
     *
     * @since 0.1.0
     *
     * @see SkyForge\RestEndpoint
     *
     * @param  object $post
     *
     * @return string
     */
    public function determineApiEndpoint(object $post) : string
    {
        $rest_endpoint = new RestEndpoint();
        return $rest_endpoint->getEndpoint($post->ID, $post->post_type);
    }

    /**
     * Get a new Rest Request class
     *
     * @method getNewRestRequest
     *
     * @since 0.1.0
     *
     * @link https://developer.wordpress.org/reference/classes/wp_rest_request/
     *
     * @param  string $endpoint
     *
     * @return WP_REST_Request
     */
    public function getNewRestRequest(string $endpoint) : \WP_REST_Request
    {
        $rest_request = new \WP_REST_Request('GET', $endpoint);
        return $rest_request;
    }

    /**
     * Get Data from WP_REST_Server
     *
     * @method getDataFromRestServer
     *
     * @since 0.1.0
     *
     * @link https://developer.wordpress.org/reference/functions/rest_do_request/
     * @link https://developer.wordpress.org/reference/functions/rest_get_server/
     * @link https://developer.wordpress.org/reference/classes/wp_rest_server/response_to_data/
     *
     * @param  WP_REST_Request $request
     *
     * @return object
     */
    public function getDataFromRestServer(\WP_REST_Request $request) : object
    {
        $response     = rest_do_request($request);
        $rest_server  = rest_get_server();
        $data         = $rest_server->response_to_data($response, false);
        return (object)$data;
    }
}
