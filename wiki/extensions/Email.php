<?php

# Email.php
# Scott Bronson  bronson@rinspin.com
# 13 Apr 2006
#
# This is an email obscuration extension for MediaWiki.
# Simply enclose your email address in <email>addr@host.tld</email>
# tags and the address will be converted into very hard-to-bot
# JavaScript.
#
# To install: copy this file into the extensions directory, then add
#		require("extensions/Email.php");
# to LocalSettings.conf.
#
# Copyright (C) 2006 Scott Bronson
# This file is released under the GNU GPL V2.
#
# The obfuscation algorithm is based on
# mosantispamemail.php by Sebastian Unterberg.


$wgExtensionFunctions[] = "EmailExtension";

function EmailExtension()
{
    global $wgParser;

    # the first parameter is the name of the new tag.
    # the second parameter is the callback function for
    # processing the text between the tags
    $wgParser->setHook("Email", "RenderEmail");
}

function RenderEmail($input, $argv, $parser=null)
{
    # $argv is an array containing any arguments passed to the
    # extension like <example argument="foo" bar>..
    if(!$parser) $parser =& $_GLOBALS['wgParser'];

    $jsvars = array();
    $replacement = "<script language='JavaScript'>\n<!--\n";
    $replacement .= "var antispam_mt = 'ma' + 'il' + 'to';\n";
    for($x=0;$x<strlen($input);$x++){
	    array_push($jsvars,"antispam".$x);
	    $replacement .= "var antispam".$x." = '".$input[$x]."';\n";
    }
    return $replacement."document.write('<a href=\''+antispam_mt+':'+".implode("+",$jsvars)."+'\'>'+".implode("+",$jsvars)."+'</a>');\n//-->\n</script>";
}

?>
