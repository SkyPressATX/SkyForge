<?php

namespace SkyForge;

/**
 * Render Class
 *
 * @since 0.1.0
 *
 * @var \SkyForge\Mustache $mustache
 *
 */
class Render
{
    /**
     * Mustache Instance
     *
     * @since 0.1.0
     *
     * @var \SkyForge\Mustache $mustache
     */
    public $mustache;

    /**
     * Class constructor
     *
     * @method __construct
     *
     * @since 0.1.0
     */
    public function __construct()
    {
        $this->mustache = Mustache::init();
    }

    /**
     * Template renderer
     *
     * @method render
     *
     * @since 0.1.0
     *
     * @param  string $template
     * @param  array $data
     *
     * @return string
     */
    public function render(string $template, array $data) : string
    {
        return $this->mustache->loadTemplate($template)->render($data);
    }
}
