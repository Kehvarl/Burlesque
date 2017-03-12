<?php
function Burlesque_BBCode($post_message)
{
    // Patterns
    $pat = array();
    $pat[] = '/\[url\](.*?)\[\/url\]/isU';         // URL Type 1
    $pat[] = '/\[url=(.*?)\](.*?)\[\/url\]/isU';   // URL Type 2
    $pat[] = '/\[b\](.*?)\[\/b\]/isU';             // bold
    $pat[] = '/\[i\](.*?)\[\/i\]/isU';             // italic
    $pat[] = '/\[u\](.*?)\[\/u\]/isU';             // underline
    $pat[] = '/\[s\](.*?)\[\/s\]/isU';             // striike
    $pat[] = '/\[spoil\](.*?)\[\/spoil\]/isU';     // spoiler
    $pat[] = '/\[color=(.*?)\](.*?)\[\/color\]/isU'; // color
    $pat[] = '/\[font=(.*?)\](.*?)\[\/font\]/isU';   // font
    $pat[] = '/\[rainbow\](.*?)\[\/rainbow\]/isU';    // Rainbow effect
    
    // Replacements
    $rep = array();
    $rep[] = '<a href="$1">$1</a>';             // URL Type 1
    $rep[] = '<a href="$1">$2</a>';             // URL Type 2
    $rep[] = '<b> $1 </b>';                     // Bold
    $rep[] = '<i> $1 </i>';                     // Italic
    $rep[] = '<u> $1 </u>';                     // Underline
    $rep[] = '<span style="text-decoration: line-through;">$1</span>'; // Strike
    $rep[] = '<span class="spoiler">$1</span>'; //Spoler
    $rep[] = '<span style="font-color: $1;">$2</span>';  //Color
    $rep[] = '<span style="font-family: $1, Verdana, sans-serif;">$2</span>';  //Font
    $rep[] = '<span class="rainbow">$1</span>'; //Rainbow
    
    return preg_replace($pat, $rep, $post_message);
}
?>