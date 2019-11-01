<?php
if (!function_exists('unslug')) {
    function unslug($slug)
    {
        return ucfirst(str_replace('-', ' ', $slug));
    }
}
