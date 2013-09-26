<?php

namespace Way\Generators\Generators;

use Illuminate\Filesystem\Filesystem as File;
use Illuminate\Support\Pluralizer;

class ControllerGenerator extends Generator {

    /**
     * Fetch the compiled template for a controller
     *
     * @param  string $template Path to template
     * @param  string $name
     * @return string Compiled template
     */
    protected function getTemplate($template, $className)
    {
        $this->template = $this->file->get($template);

        if ($this->needsScaffolding($template))
        {
            $this->template = $this->getScaffoldedController($template, $className);
        }

        $class = str_replace('Controller', '', $className);

        $this->template = str_replace('{{className}}', $className, $this->template);
        $this->template = str_replace('{{camel_case(class)}}', camel_case($class), $this->template);
        $this->template = str_replace('{{studly_case(class)}}', studly_case($class), $this->template);
        $this->template = str_replace('{{snake_case(class)}}', snake_case($class), $this->template);
        $this->template = str_replace('{{str_plural(snake_case(class))}}', str_plural(snake_case($class)), $this->template);
        return str_replace('{{str_plural(camel_case(class))}}', str_plural(camel_case($class)), $this->template);
    }

    /**
     * Get template for a scaffold
     *
     * @param  string $template Path to template
     * @param  string $name
     * @return string
     */
    protected function getScaffoldedController($template, $className)
    {
        $model = $this->cache->getModelName();  // post
        $models = Pluralizer::plural($model);   // posts
        $Models = ucwords($models);             // Posts
        $Model = Pluralizer::singular($Models); // Post

        foreach(array('model', 'models', 'Models', 'Model', 'className') as $var)
        {
            $this->template = str_replace('{{'.$var.'}}', $$var, $this->template);
        }

        return $this->template;
    }
}
