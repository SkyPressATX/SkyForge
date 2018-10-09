<?php

namespace SkyForge\ContextData;

/**
 * Header Context class
 *
 * @since 0.1.0
 *
 * @uses \SkyForge\Interfaces\ContextInterface
 *
 * @var string $nav_slug
 */
class Header implements Interfaces\ContextInterface
{
    /**
     * Default navigation slug
     *
     * @var string
     */
    public $nav_slug = 'main';

    /**
     * Get Context data
     *
     * @method getContext
     *
     * @since 0.1.0
     *
     * @link https://developer.wordpress.org/reference/functions/get_bloginfo/
     *
     * @param WP_Post $post
     *
     * @return array
     */
    public function getContext(\WP_Post $post) : array
    {
        $context = [
            'title' => get_bloginfo('name'),
            'nav'   => $this->getNavData()
        ];

        return $context;
    }

    /**
     * Get Navigation Data
     *
     * @method getNavData
     *
     * @since 0.1.0
     *
     * @link https://developer.wordpress.org/reference/functions/wp_get_nav_menu_items/
     *
     * @return mixed object | bool
     */
    public function getNavData()
    {
        return wp_get_nav_menu_items($this->nav_slug);
    }
}
