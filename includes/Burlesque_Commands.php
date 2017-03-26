<?php
/**
 * Summary[cursor]
 * @param Post $post - The post object being modified.
 * @return Post
 */
function Burlesque_Commands($post)
{    
    if(!substr($post->message, 0, 1) == "/")
        return $post;
    
    $post_message = $post->message;
    
    $action = strtolower(trim(strtok($post_message, ' '), '/'));
    $post->prefix = ucfirst($action);
    switch($action)
    {
        case "demo": // /demo /command...
            $post->prefix_color     = "#008000";
            $post->message          = strtok("\n");
            break;
        case "gm":  // /gm message
            $post->prefix           = "GM"; //Wanted all caps not "Gm".
            $post->prefix_color     = "#0088aa";
            $post->sender           = "";
            $post->message          = strtok("\n");
            break;
        case "nar": // /nar message
            $post->prefix_color     = "#00aa88";
            $post->sender           = "";
            $post->message          = strtok("\n");
            break;
        case "chat": // /chat message
            $post->prefix_color     = "#FFFFFF";
            $post->sender           = "";
            $post->message          = strtok("\n");
            break;
        case "char": // /char name:message
            $post->prefix_color     = "#c0c0c0";
            $post->sender           = strtok(":");
            $post->message          = strtok("\n");
            break;
        case "me":  // /me message
        case "act": // /me message
        case "do":  // /me message
            $post->prefix_color     = $post->color;
            $post->message          = strtok("\n");
            break;
        case "pref": // /pref prefix:message
            $post->prefix           = ucfirst(strtolower((strtok(":"))));
            if(strlen($post->prefix) > 12)
                $post->prefix = substr($post->prefix, 0, 11);
            $post->prefix_color     = $post->color;
            $post->message          = strtok("\n");
            break;
        case "color":
            $post->prefix_color     = "#c0c0c0";
            $post->message          = "has chosen a new color.";
            //$this->user_color = strtok("\n");
            break;
        case "font":
            $post->prefix_color     = "#c0c0c0";
            $post->message          = "has chosen a new font.";
            //$this->user_font = strtok("\n");
            break;
        case "roll": // /roll [num]d[sides]{e[+/- each]}{e[+/- total]}
            $post->prefix_color     = "#804000";
            $post->message          = die_roller(trim(strtok("\n")));
            break;
        case "fate": // /fate {message}
            $post->prefix_color     = "#800040";
            $post->message          = strtok("\n") . fate_roller();
            break;
        default:
            $post->prefix           = "";
            $post->prefix_color     = "#000000";
    }
    return $post;
}

/**
 * Summary[cursor]
 * @param String $parameters - The die-roll instruction string in the format
 * [num]d[sides]{e[+/-each]}{t[+/-tot]}{l[low]][h[high]}
 * 3d6e+1t-3
 * @return String
 */
function die_roller($parameters)
{
    //get die-roll arguments: [num]d[sides]{e[+/-each]}{t[+/-tot]}{l[low]][h[high]}
    $filter = '/(?P<number>\d+)d(?P<sides>\d+)(?:e(?P<each>[-+]?\d+))?(?:t(?P<total>[-+]?\d+))?/';
    preg_match($filter, $parameters, $matches);
    
    //Set sane limits on passed in data
    $number = 1;
    if(isset($matches['number']) && is_numeric($matches['number']))
        $number = min(max($matches['number'], 1), 100);
        
    $sides = 6;
    if(isset($matches['sides']) && is_numeric($matches['sides']))
        $sides  = min(max($matches['sides'], 2), 1000);
    
    $each = 0;
    if(isset($matches['each']) && is_numeric($matches['each']))
        $each   = min(max($matches['each'], -100), 100);
    
    $total = 0;
    if(isset($matches['total']) && is_numeric($matches['total']))
        $total  = min(max($matches['total'], -100), 100);
    
    //Prepare results message: "has rolled [num] [sides]-sided dice, [each] to each, [total] to total, with results[...]"
    $message = "has rolled $number ${sides}-sided dice ";
    if(is_int($each) && $each != 0)
        $message .=",$each to each ";
    if(is_int($total) && $total != 0)
        $message .=",$total to total ";
    $message .="with results: [";
    
    $roll_min = 1100; //Max is 1000e100 for 1100 per roll
    $roll_max = 0;
    $roll_sum = 0;
    //Roll dice and collect metrics
    for($d = 0; $d < $number; $d++)
    {
        //Roll die"
        $roll = rand(1, $sides) + $each;
        //Add result to message"
        $message .= "$roll";
        if($d < $number -1)
            $message .=", ";
        //Accumulate totals and track min/max
        $roll_sum += $roll;
        $roll_min = min($roll_min, $roll);
        $roll_max = max($roll_max, $roll);
    }
    $roll_avg = round(($roll_sum+$total)/$number);
    
    if($total != 0)
         $roll_sum = $roll_sum . $total . "(".$roll_sum+$total.")";
         
    //Complete results message: "[all resuls]{Total: [total], Average: [average], Low: [lowest], High:[highest]}"
    $message .="] {Total: $roll_sum; Average: $roll_avg; Low: $roll_min; High: $roll_max}";
    
    return $message;
}

function fate_roller()
{
    $results = " [";
    $total = 0;
    for($f = 0; $f < 4; $f++)
    {
        $roll = rand(1,3)-2;
        $total += $roll;
        if($roll == -1)
            $results .= "-";
        elseif($roll == 1)
            $results .="+";
        else
            $results .="o";
    }
    return $results." ($total)]";
    
}
?>