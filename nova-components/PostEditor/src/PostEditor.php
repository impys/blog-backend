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

    /**
     * Append app url
     *
     * @param string $url
     * @return $this
     */
    public function appUrl(string $appUrl)
    {
        return $this->withMeta(['appUrl' => $appUrl]);
    }
}
