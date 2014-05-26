<?php

namespace PatrickRose\MarkdownExtender;

use Michelf\MarkdownExtra;

class MarkdownExtender
{

    protected static $extensions = [];

    public function compile($text)
    {
        $compiled = MarkdownExtra::defaultTransform($text);
        if (preg_match_all("/\[\{(.*?)\}\]/", $compiled, $extensions)) {
            foreach ($extensions[0] as $key => $extension) {
                $functionCall = explode(":", $extensions[1][$key]);
                $method = $functionCall[0];
                $args = isset($functionCall[1]) ? explode(",", $functionCall[1]) : "";
                if (method_exists($this, $method)) {
                    $replacement = $this->call_method($method, $args);
                } elseif (array_key_exists($method, static::$extensions)) {
                    $replacement = $this->call_extension($method, $args);
                } else {
                    throw new InvalidMethodCallException("{method} is not a valid extension");
                }
                $compiled = str_replace($extension, $replacement, $compiled);
            }
        }

        return $compiled;
    }

    protected function youtube($id)
    {
        return "<iframe width=\"560\" height=\"315\" src=\"//www.youtube.com/embed/{$id}\" frameborder=\"0\" allowfullscreen></iframe>";
    }

    protected function vimeo($id)
    {
        return "<iframe src=\"//player.vimeo.com/video/{$id}\" width=\"500\" height=\"281\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>";
    }

    public function extend($marker, Callable $function)
    {
        static::$extensions[$marker] = $function;
    }

    public function extensions()
    {
        $extensions = ["youtube"];
        foreach (static::$extensions as $key => $value) {
            $extensions[] = $key;
        }

        return $extensions;
    }

    private function call_method($method, $args)
    {
        switch (count($args)) {
            case 1:
                return $this->$method($args[0]);
            case 2:
                return $this->$method($args[0], $args[1]);
            case 3:
                return $this->$method($args[0], $args[1], $args[2]);
            case 4:
                return $this->$method($args[0], $args[1], $args[2], $args[3]);
            default:
                return call_user_func_array($this->$method(), $args);
        }
    }

    private function call_extension($method, $args)
    {
        $function = static::$extensions[$method];
        switch (count($args)) {
            case 1:
                return $function($args[0]);
            case 2:
                return $function($args[0], $args[1]);
            case 3:
                return $function($args[0], $args[1], $args[2]);
            case 4:
                return $function($args[0], $args[1], $args[2], $args[3]);
            default:
                return call_user_func_array($function(), $args);
        }
    }
}
