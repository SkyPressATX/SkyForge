<?php
namespace SkyForge;

/**
 * Init Class
 *
 * @TODO Break up this big class into smaller ones
 */
class Init
{
    /**
     * @var $post_id
     */
    public $post_id;

    /**
     * Render Method
     *
     * @return String
     * @throws \Exception
     */
    public function render() : string
    {
        $this->post_id = $this->getPostID();
        echo "POST ID: {$this->post_id}\n";
        // $request_data = $this->getRequestDataFromSlug($slug);
        // var_dump($request_data);
        return '<h1>Hello SkyStache</h1>';
    }

    /**
     * Get Post ID
     *
     * @see https://codex.wordpress.org/Function_Reference/url_to_postid
     *
     * @return Int
     * @throws \Exception
     */
    public function getPostID() : int
    {
        global $wp;
        $request_url = add_home(add_query_arg(array(), $wp->request));
        $post_id = url_to_postid($request_url);
        return $post_id;
    }

    /**
     * Get Data from Rest Request based on slug
     * Uses an internal API call
     *
     * @param String $slug
     *
     * @return Object $data
     * @throws \Exception
     */
    public function getRequestDataFromSlug(string $slug) : object
    {
        $api_endpoint = $this->determineApiEndpoint($slug);
        $request      = $this->getNewRestRequest($api_endpoint);
        $data         = $this->getDataFromRestServer($request);
        return $data;
    }

    /**
     * Determine which API Endpoint to use
     *
     * @param String $slug
     *
     * @return String
     * @throws \Exception
     */
    public function determineApiEndpoint(string $slug) : string
    {
        $rest_endpoint = new RestEndpoint();
        return $rest_endpoint->getEndpoint($slug);
    }

    /**
     * Get a new Rest Request class
     *
     * @param String $endpoint
     *
     * @return \WP_REST_Request
     * @throws \Exception
     */
    public function getNewRestRequest(string $endpoint) : \WP_REST_Request
    {
        $rest_request = new \WP_REST_Request('GET', $endpoint);
        return $rest_request;
    }

    /**
     * Get Data from Rest Server
     *
     * @param \WP_REST_Request $request
     *
     * @return Object
     * @throws \Exception
     */
    public function getDataFromRestServer(\WP_REST_Request $request) : object
    {
        $response     = rest_do_request($request);
        $rest_server  = rest_get_server();
        $data         = $rest_server->response_to_data($response, false);
        return (object)$data;
    }
}
