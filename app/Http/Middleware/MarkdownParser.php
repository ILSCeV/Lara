<?php

namespace Lara\Http\Middleware;

use Illuminate\Support\HtmlString;

/**
 * This trait contains basic functions to convert a String which contains Markdown to HTML
 */
trait MarkdownParser
{
    /**
     * Takes a String and parses markdown content. The Output is HTML as String.
     *
     * @param String $text text with markdown content
     * @return HtmlString String as html
     */
    function mdToHtml(string $text)
    {
        return new HtmlString(
            app(\Parsedown::class)->setSafeMode(false)->text($text)
        );
    }

    /**
     * Takes a String with markdown content, if its already parsed it returns the cached value
     *
     * @param String $text text with markdown content
     * @return HtmlString String as html
     */
    function mdToHtmlCached(string $text)
    {
        $defaultTimeToLife = 86400;
        // Hash the text with the lowest computational hasher available.
        $key = 'Markdown|' . hash('sha512', $text);
        return \Cache::remember($key, $defaultTimeToLife, function () use ($text) {
            return $this->mdToHtml($text);
        });
    }
}
