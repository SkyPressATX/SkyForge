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
    public $template_type;

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
        $data = [
            'header'  => $this->getHeaderData(),
            'body'    => $this->getPostData(),
            'footer'  => []
        ];
        if (empty($this->template_type)) {
            $this->template_type = 'page'; // Default to page
        }
        $html = $this->mustache->loadTemplate('header')->render($data['header']);
        $html .= $this->mustache->loadTemplate($this->template_type)->render($data['body']);
        $html .= $this->mustache->loadTemplate('footer')->render($data['footer']);
        return $html;
    }

    /**
     * Get Header Data
     *
     * @method getHeaderData
     *
     * @since 0.1.0
     *
     * @return array
     */
    public function getHeaderData() : array
    {
        $header = new Header();
        return $header->getData();
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
        $this->template_type    = strtolower($post->post_type);
        $api_endpoint           = $this->determineApiEndpoint($post->ID, $post->post_type);
        $request                = $this->getNewRestRequest($api_endpoint);
        $data                   = $this->getDataFromRestServer($request);
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
