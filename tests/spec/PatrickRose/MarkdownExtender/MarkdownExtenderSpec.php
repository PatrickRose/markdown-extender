<?php

namespace spec\PatrickRose\MarkdownExtender;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class MarkdownExtenderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PatrickRose\MarkdownExtender\MarkdownExtender');
    }

    function it_compiles_markdown_properly()
    {
        $this->compile("Hello")->shouldReturn("<p>Hello</p>" . PHP_EOL);
    }

    function it_creates_youtube_links_for_us()
    {
        $this->compile("[{youtube:BgAdeuxkUyY}]")->shouldReturn("<p><iframe width=\"560\" height=\"315\" src=\"//www.youtube.com/embed/BgAdeuxkUyY\" frameborder=\"0\" allowfullscreen></iframe></p>" . PHP_EOL);
    }

    function it_fails_gracefully_if_a_method_isnt_found()
    {
        $this->shouldThrow("PatrickRose\\MarkdownExtender\\InvalidMethodCallException")->duringCompile("[{foobar}]");
    }

    function it_can_be_extended_to_include_new_methods()
    {
        $this->extend("foobar", function($args) {
                return "foobar was called with {$args}";
            });
        $this->compile("[{foobar:baz}]")->shouldReturn("<p>foobar was called with baz</p>" . PHP_EOL);
    }

    function it_lets_us_know_what_functions_we_have_available()
    {
        $this->extend("foobar", function($args) {
                return "foobar was called with {$args}";
            });
        $this->extensions()->shouldReturn([
                "youtube",
                "foobar"
            ]);
    }

    function it_can_handle_multiple_arguments()
    {
        $this->extend("multipleargs", function ($one, $two, $three) {
                return "I have been given <em>{$one}</em>, <em>{$two}</em> and <em>{$three}</em>.";
            });
        $this->compile("[{multipleargs:one,two,3}]")->shouldReturn("<p>I have been given <em>one</em>, <em>two</em> and <em>3</em>.</p>" . PHP_EOL);
    }

    function it_can_handle_multiple_uses_of_the_extensions() {
        $this->compile("[{youtube:BgAdeuxkUyY}][{youtube:BgAdeuxkUyY}]")->shouldReturn("<p><iframe width=\"560\" height=\"315\" src=\"//www.youtube.com/embed/BgAdeuxkUyY\" frameborder=\"0\" allowfullscreen></iframe><iframe width=\"560\" height=\"315\" src=\"//www.youtube.com/embed/BgAdeuxkUyY\" frameborder=\"0\" allowfullscreen></iframe></p>" . PHP_EOL);
    }

}
