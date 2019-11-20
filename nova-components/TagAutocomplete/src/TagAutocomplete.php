<?php

namespace Qingfengbaili\TagAutocomplete;

use Laravel\Nova\ResourceTool;

class TagAutocomplete extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Tag Autocomplete';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'tag-autocomplete';
    }
}
