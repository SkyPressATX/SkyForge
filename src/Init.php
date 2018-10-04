<?php
namespace SkyForge;

/**
 * SkyForge Initialize Class
 *
 * @since 0.1.0
 *
 * @var \SkyForge\Mustache $mustache
 * @var int $post_id
 * @var string $template_type
 * @var string $data_filter
 * @var string nav_slug_filter
 * @var string nav_slug_default
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
     * Template Type
     *
     * @var string
     */
    public $template_type = 'page';

    /**
     * SkyForge Render Data filter slug
     *
     * @var string
     */
    public $data_filter = 'skyforge_render_data';

    /**
     * Navigation Slug filter slug
     *
     * @var string
     */
    public $nav_slug_filter = 'skyforge_nav_slug';

    /**
     * Missing Filter slug
     *
     * @var string
     */
    public $missing_filter_slug = 'skyforge_not_found_slug';

    /**
     * Default navigation slug
     *
     * @var string
     */
    public $nav_slug_default = 'main';

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
        $data   = $this->getRenderData();
        $render = [
            'header'                => $this->getHeaderData(),
            $this->template_type    => $this->getRenderData()
        ];
        $filtered_data = apply_filters($this->data_filter, $render);
        // print_r($filtered_data['nav']);
        return $this->mustache->loadTemplate($this->template_type)->render($filtered_data);
    }

    public function getHeaderData()
    {
        $data = [
            'title' => get_bloginfo('name'),
            'nav'   => $this->getNavData()
        ];
        return $data;
    }

    /**
     * Get Navigation Data
     *
     * @method getNavData
     *
     * @since 0.1.0
     *
     * @return mixed object | bool
     */
    public function getNavData()
    {
        $nav_slug = apply_filters($this->nav_slug_filter, $this->nav_slug_default);
        return wp_get_nav_menu_items($nav_slug);
    }

    /**
     * Get Data from Rest Request based on slug
     *
     * @method getRenderData
     *
     * @since 0.1.0
     *
     * @return object $data
     */
    public function getRenderData() : object
    {
        $post = get_post();
        if (! $post->ID || null === $post->ID) {
            return $this->getMissingData();
        }
        $this->template_type    = strtolower($post->post_type);
        $api_endpoint           = $this->determineApiEndpoint($post->ID, $post->post_type);
        $request                = $this->getNewRestRequest($api_endpoint);
        $data                   = $this->getDataFromRestServer($request);
        return $data;
    }

    /**
     * Get Data for Missing Post request
     *
     * @method getMissingData
     *
     * @since 0.1.0
     *
     * @return object
     */
    public function getMissingData()
    {
        $this->template_type = '404';
        $data = apply_filters($this->missing_filter_slug, (object)[
            'title' => '404 Not Found',
            'text'  => 'The page requested could not be found'
        ]);
        return (object)$data;
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
     * @param  int $id
     * @param  string $type
     *
     * @return string
     */
    public function determineApiEndpoint(int $id, string $type) : string
    {
        $rest_endpoint = new RestEndpoint();
        return $rest_endpoint->getEndpoint($id, $type);
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
