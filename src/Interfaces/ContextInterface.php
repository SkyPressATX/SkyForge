<?php

namespace SkyForge\Interfaces;

/**
 * Context Interface
 * Used by all classes that return data regarding the context
 *
 * @since 0.1.0
 */
interface ContextInterface
{
    /**
     * Get Context Data
     *
     * @method getContext
     *
     * @since 0.1.0
     *
     * @param WP_Post $post
     *
     * @return array
     */
    public function getContext(\WP_Post $post) : array;
}
