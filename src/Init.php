<?php
namespace SkyForge;

/**
 * SkyForge Initialize Class
 *
 * @since 0.1.0
 *
 * @var int $post_id
 * @var string $template_type
 * @var string $data_filter
 * @var string nav_slug_filter
 * @var string nav_slug_default
 */
class Init
{
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
     * Render the template
     *
     * @method render
     *
     * @since 0.1.0
     *
     * @link https://developer.wordpress.org/reference/functions/get_post/
     * @uses \SkyForge\Context
     * @uses \SkyForge\Render
     *
     * @return string
     */
    public function render() : string
    {
        $post       = get_post();
        $context    = call_user_func_array([new Context,'getContext'], [$post]);

        return call_user_func_array([new Render,'render'], [$post->post_type, $context]);
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
