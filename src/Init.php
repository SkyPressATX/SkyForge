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
     * @var $template_dir
     */
    public $template_dir;

    /**
     * Render Method
     *
     * @return String
     * @throws \Exception
     */
    public function render(string $template_dir = null) : string
    {
        $this->template_dir = $this->getTemplateDir($template_dir);
        $post_data = $this->getPostData();
        $template = file_get_contents($this->template_dir . '/home.html');

        return "<body>$template</body>";
    }

    /**
     * Get the Template Directory
     *
     * @param String $template_dir
     *
     * @return String
     * @throws \Exception
     */
    public function getTemplateDir(string $template_dir = null) : string
    {
        if (null !== $template_dir && is_dir($template_dir)) {
            return $template_dir;
        }
        if (is_dir(get_stylesheet_directory() . '/templates')) {
            return get_stylesheet_directory() . '/templates';
        }
        if (is_dir(get_template_directory() . '/templates')) {
            return get_template_directory() . '/templates';
        }

        throw new \Exception(__METHOD__ . ": Could not find a valid template directory");
    }

    /**
     * Get Data from Rest Request based on slug
     * Uses an internal API call
     *
     * @return Object $data
     * @throws \Exception
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
     * Determine which API Endpoint to use
     *
     * @param Object $post
     *
     * @return String
     * @throws \Exception
     */
    public function determineApiEndpoint(object $post) : string
    {
        $rest_endpoint = new RestEndpoint();
        return $rest_endpoint->getEndpoint($post->ID, $post->post_type);
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
