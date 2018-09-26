<?php
namespace SkyForge;

/**
 * SkyForge Header data
 */
class Header
{
    /**
     * Header Data
     *
     * @var array
     */
    public $header_data;

    /**
     * Header Data Filter Slug
     *
     * @var string
     */
    public $header_data_filter_slug = 'skyforge_header_data';

    /**
     * Class Constructor
     *
     * @method __construct
     *
     * @since 0.1.0
     */
    public function __construct()
    {
        $this->header_data = $this->getHeaderData();
    }

    /**
     * Get Data
     *
     * @method getData
     *
     * @since 0.1.0
     *
     * @return array
     */
    public function getData() : array
    {
        if (empty($this->header_data)) {
            $this->header_data = $this->getHeaderData();
        }

        return $this->header_data;
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
        $default = $this->getDefaultHeaderData();
        $data = apply_filters($this->$header_data_filter_slug, $default);
        return $data;
    }

    /**
     * Get Default Header Data
     *
     * @method getDefaultHeaderData
     *
     * @since 0.1.0
     *
     * @link https://developer.wordpress.org/reference/functions/get_language_attributes/
     * @link https://developer.wordpress.org/reference/functions/get_bloginfo/
     * @link https://developer.wordpress.org/reference/functions/get_body_class/
     *
     * @return array
     */
    public function getDefaultHeaderData() : array
    {
        $data = [
            'body_class'            => implode(' ', get_body_class()),
            'language_attributes'   => get_language_attributes(),
            'charset'               => get_bloginfo('charset'),
            'name'                  => get_bloginfo('name'),
            'description'           => get_bloginfo('description'),
            'wp_head'               => $this->getWPHeadHTML()
        ];
        return $data;
    }

    /**
     * Get wp_head HTML
     *
     * @method getWPHeadHTML
     *
     * @since 0.1.0
     *
     * @link http://php.net/manual/en/function.ob-get-contents.php
     * @link https://codex.wordpress.org/Function_Reference/wp_head
     *
     * @return string
     */
    public function getWPHeadHTML() : string
    {
        ob_start();
        $html = ob_get_contents();
        ob_end_clean();
        return $html;
    }
}
