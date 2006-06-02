<?php

$wgExtensionFunctions[] = "TimestampNowExtension";

function TimestampNowExtension()
{
    global $wgParser;

    # the first parameter is the name of the new tag.
    # the second parameter is the callback function for
    # processing the text between the tags
    $wgParser->setHook("TimestampNow", "RenderTimestampNow");
}

function RenderTimestampNow($input, $argv, $parser=null)
{
    # $argv is an array containing any arguments passed to the
    # extension like <example argument="foo" bar>..
    if(!$parser) $parser =& $_GLOBALS['wgParser'];

    $parser->disableCache();
    return wfTimestampNow();
}

?>
