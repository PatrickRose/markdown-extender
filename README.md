# Markdown Extender

Do you use Markdown to write blog posts and websites? Do you really hate the fact that embedding things like
YouTube videos is a bit of a pain and you want it to be a bit more...Markdown-y? Meet Markdown Extender.

This extends the `michelf/php-markdown` package to provide new functionality and also allow you to extend.

## Installation

Add the following to your `composer.json`.

```json
"require": {
    "patrickrose/markdown-extender": "~1"
}
```

## Usage

Build up your markdown string and pass it to the `compile` function. Extensions are
handled using the syntax `[{extensionName:arg1,arg2,arg3}]` in your markdown.

If you need a new function then you can add it using
`extend($extensionName, $extensionFunction)`, where $extensionName is the string to use
inside the `[{}]` block and $extensionFunction is a closure that takes any number of
arguments and returns a string.

### Current Functions

* Youtube Embedding: `[{youtube:youtubeID}]` => `<iframe width="560" height="315" src="//www.youtube.com/embed/BgAdeuxkUyY" frameborder="0" allowfullscreen></iframe>`
* Vimeo Embedding: `[{vimeo:vimeoID}]` => `<iframe src="//player.vimeo.com/video/95810934" width="500" height="281" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>`
* Tweet Embedding: `[{twitter:tweetID}]` => `<blockquote class="twitter-tweet"><p>Search API will now always return &quot;real&quot; Twitter user IDs. The with_twitter_user_id parameter is no longer necessary. An era has ended. ^TS</p>&mdash; Twitter API (@twitterapi) <a href="https://twitter.com/twitterapi/statuses/133640144317198338">November 7, 2011</a></blockquote>\n<script async src="//platform.twitter.com/widgets.js" charset="utf-8"></script>`
* Description Lists: `[{description:item1|description1,item2|description2}]` => `<dl><dt>first</dt><dd>first text</dd><dt>second</dt><dd>second text</dd></dl>`

## Example

```php

$markdown = new PatrickRose\MarkdownExtender\MarkdownExtender;
$string = "I really like this youtube video!

[{youtube:BgAdeuxkUyY}]

And I added an extension:

[{extended:one,two,3}]";

$markdown->extend("extended", function($one,$two,$three) {
    return "You passed in <em>$one</em>, <em>$two</em> and <em>$three</em>.";
});

$markdown->compile($string)

/* returns:
"<p>I really like this youtube video!</p>

<p><iframe width=\"560\" height=\"315\" src=\"//www.youtube.com/embed/BgAdeuxkUyY\" frameborder=\"0\" allowfullscreen></iframe></p>

<p>And I added an extension:</p>

<p>You passed in <em>one</em>, <em>two</em> and <em>3</em>.</p>"
*/
```

## Laravel Users

Using Laravel? Then feel free to use the service provider and facade. First, add this
to your providers array:

```php

"providers" => [
    ...
    "PatrickRose\\MarkdownExtender\\MarkdownExtenderServiceProvider",
]
```

And add the facade to your aliases array

```php

"aliases" => [
    ...
    "MarkdownExtender" => "PatrickRose\\MarkdownExtender\\Facades\\MarkdownExtender"
]
```

Then you can use `MarkdownExtender::compile($markdown)` and
`MarkdownExtender::extend($extension)`.