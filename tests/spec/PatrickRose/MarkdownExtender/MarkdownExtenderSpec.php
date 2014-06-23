<?php

namespace spec\PatrickRose\MarkdownExtender;

use Prophecy\Argument;
use PhpSpec\ObjectBehavior;

class MarkdownExtenderSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PatrickRose\MarkdownExtender\MarkdownExtender');
    }

    function it_compiles_markdown_properly()
    {
        $this->compile("Hello")->shouldReturn("<p>Hello</p>");
    }

    function it_creates_youtube_links_for_us()
    {
        $this->compile("[{youtube:BgAdeuxkUyY}]")->shouldReturn(
            "<p><iframe width=\"560\" height=\"315\" src=\"//www.youtube.com/embed/BgAdeuxkUyY\" frameborder=\"0\" allowfullscreen></iframe></p>"
        );
    }

    function it_fails_gracefully_if_a_method_isnt_found()
    {
        $this->shouldThrow("PatrickRose\\MarkdownExtender\\InvalidMethodCallException")->duringCompile("[{foobar}]");
    }

    function it_can_be_extended_to_include_new_methods()
    {
        $this->extend(
            "foobar",
            function ($args) {
                return "foobar was called with {$args}";
            }
        );
        $this->compile("[{foobar:baz}]")->shouldReturn("<p>foobar was called with baz</p>");
    }

    function it_lets_us_know_what_functions_we_have_available()
    {
        $this->extend(
            "foobar",
            function ($args) {
                return "foobar was called with {$args}";
            }
        );
        $this->extensions()->shouldReturn(
            [
                "youtube",
                "foobar"
            ]
        );
    }

    function it_can_handle_multiple_arguments()
    {
        $this->extend(
            "multipleargs",
            function ($one, $two, $three) {
                return "I have been given <em>{$one}</em>, <em>{$two}</em> and <em>{$three}</em>.";
            }
        );
        $this->compile("[{multipleargs:one,two,3}]")->shouldReturn(
            "<p>I have been given <em>one</em>, <em>two</em> and <em>3</em>.</p>"
        );
    }

    function it_can_handle_multiple_uses_of_the_extensions()
    {
        $this->compile("[{youtube:BgAdeuxkUyY}][{youtube:BgAdeuxkUyY}]")->shouldReturn(
            "<p><iframe width=\"560\" height=\"315\" src=\"//www.youtube.com/embed/BgAdeuxkUyY\" frameborder=\"0\" allowfullscreen></iframe><iframe width=\"560\" height=\"315\" src=\"//www.youtube.com/embed/BgAdeuxkUyY\" frameborder=\"0\" allowfullscreen></iframe></p>"
        );
    }

    function it_can_embed_vimeo_links()
    {
        $this->compile("[{vimeo:95810934}]")->shouldReturn(
            "<p><iframe src=\"//player.vimeo.com/video/95810934\" width=\"500\" height=\"281\" frameborder=\"0\" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe></p>"
        );
    }

    function it_can_create_description_lists()
    {
        $this->compile("[{description:first|first text,second|second text}]")->shouldReturn(
            "<p><dl><dt>first</dt><dd>first text</dd><dt>second</dt><dd>second text</dd></dl></p>"
        );
    }

    function it_can_create_twitter_links()
    {
        $this->compile("[{twitter:133640144317198338}]")->shouldReturn("<p><blockquote class=\"twitter-tweet\"><p>Search API will now always return &quot;real&quot; Twitter user IDs. The with_twitter_user_id parameter is no longer necessary. An era has ended. ^TS</p>&mdash; Twitter API (@twitterapi) <a href=\"https://twitter.com/twitterapi/statuses/133640144317198338\">November 7, 2011</a></blockquote>\n<script async src=\"//platform.twitter.com/widgets.js\" charset=\"utf-8\"></script></p>");
    }

}
