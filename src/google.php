<?php

namespace mkdesignn\googlesearch;

class google
{

    public static function find($keyword){

        // get the content from the google website
        $subject = file_get_contents("http://www.google.com/search?q=".urlencode($keyword));

        // get any things that start with h3 tag and end with h3 tag
        $href_pattern = '/<h3 class="r">(.*?)<\/h3>/s';
        preg_match_all($href_pattern, $subject, $matches);

        foreach ($matches[0] as $key => $match) {

            // get any charachter that souranded between the href attribute
            $replacment_pattern = '/href="([^"]+)"/';
            preg_match($replacment_pattern, $match, $found);

            // get the first part of the whole url and break it
            // by using the & charachter
            $found = explode("&", $found[1]);

            // replace the url?q= with nothing
            $found[0] = str_replace("/url?q=", "", $found[0]);

            // decode the url if it was encoded
            $found[0] = urldecode($found[0]);

            // replace the found into the match using replacment_pattern
            $replace_url = preg_replace($replacment_pattern, "href='".$found[0]."'", $match);
            settype($replace_url, "string");

            // replace the new url to the old one
            if( gettype($replace_url) != "boolean" )
                $matches[0][$key] = $replace_url;
            else
                unset($matches[0][$key]);

            if( json_encode($matches[0][$key]) == false )
                unset($matches[0][$key]);
        }


        return $matches[0];
    }
}