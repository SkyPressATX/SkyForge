<?php

namespace SkyForge;

/**
 * Context Class
 *
 * @since 0.1.0
 *
 * @uses \SkyForge\Interfaces\ContextInterface
 */
class Context implements Interfaces\ContextInterface
{
    /**
     * Get Context data
     *
     * @method getContext
     *
     * @since 0.1.0
     *
     * @param  WP_Post $post
     *
     * @return array
     */
    public function getContext(\WP_Post $post) : array
    {
        $type       = $this->getTemplateType($post);
        $context    = [
            'type'      => $type,
            'context'   => [
                'header'    => $this->getContextPart('Header', $post),
                $type       => ('404' === $type) ? $this->getContextPart('NotFound', $post) : $this->getContextPart('Body', $post),
                'footer'    => $this->getContextPart('Footer', $post)
            ]
        ];

        return $context;
    }

    /**
     * Get Template Type
     *
     * @method getTemplateType
     *
     * @since 0.1.0
     *
     * @param  WP_Post $post
     *
     * @return string
     */
    public function getTemplateType(\WP_Post $post) : string
    {
        $not_found = '404';
        if (null === $post->ID) {
            return $not_found;
        }
        if ((int)0 === $post->ID) {
            return $not_found;
        }
        if (!$post->ID) {
            return $not_found;
        }
        if (! property_exists($post, 'post_type')) {
            return $not_found;
        }

        return $post->post_type;
    }

    /**
     * Get partial data for Context
     *
     * @method getContextPart
     *
     * @since 0.1.0
     *
     * @param  string $part
     * @param  WP_Post $post
     *
     * @return array
     */
    public function getContextPart(string $part, \WP_Post $post) : array
    {
        $class = '\SkyForge\ContextData\\' . $part;
        if (! class_exists($class)) {
            throw new Exception(__method__ . ": $part is not a valid Context Part");
        }
        return call_user_func_array([new $class, 'getContext'], [$post]);
    }
}
