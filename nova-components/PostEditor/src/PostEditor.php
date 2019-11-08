<?php

namespace Qingfengbaili\PostEditor;

use Laravel\Nova\Fields\Field;
use Illuminate\Mail\Markdown;

class PostEditor extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'post-editor';

    public function jsonSerialize()
    {
        return array_merge(parent::jsonSerialize(), [
            'value_marked' => Markdown::parse($this->value)->toHtml(),
        ]);
    }
}
