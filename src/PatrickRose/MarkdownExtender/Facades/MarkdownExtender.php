<?php
/**
 * Created by PhpStorm.
 * User: patrick
 * Date: 26/05/14
 * Time: 01:34
 */

namespace PatrickRose\MarkdownExtender\Facades;

use Illuminate\Support\Facades\Facade;

class MarkdownExtender extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'MarkdownExtender';
    }

} 