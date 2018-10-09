<?php

namespace SkyForge\ContextData;

/**
 * Body context class
 *
 * @since 0.1.0
 *
 * @uses \SkyForge\Interfaces\ContextInterface
 */
class Body implements Interfaces\ContextInterface
{
    /**
     * Get context data
     *
     * @method getContext
     *
     * @since 0.1.0
     *
     * @param WP_Post $post
     *
     * @return array
     */
    public function getContext(\WP_Post $post) : array
    {
    }
}
