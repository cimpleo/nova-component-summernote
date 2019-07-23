<?php

namespace Cimpleo\NovaSummernote;

use Laravel\Nova\Fields\Field;
use Laravel\Nova\Http\Requests\NovaRequest;

class NovaSummernote extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'nova-summernote';

    public function options(array $options = [])
    {
        return $this->withMeta(['options' => $options]);
    }
}
