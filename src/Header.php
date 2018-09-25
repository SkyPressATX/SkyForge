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
     * @link https://developer.wordpress.org/reference/functions/bloginfo/
     * @link https://developer.wordpress.org/reference/functions/get_body_class/
     *
     * @return array
     */
    public function getDefaultHeaderData() : array
    {
        $data = [
            'language_attributes' => get_language_attributes(),
            'blog_info'           => bloginfo(),
            'body_class'          => get_body_class()
        ];
        return $data;
    }
}
