<?php

function getAuthorColor($lastname, $name, $alpha = 0.7)
{
    // Create a simple hash of the author's name
    $hash = md5($lastname . $name);

    // Get the first 6 characters of the hash and use them as a color code
    $color = substr($hash, 0, 6);

    // Convert the color code to an RGBA value with the specified transparency
    $rgba = 'rgba(' . hexdec(substr($color, 0, 2)) . ', ' . hexdec(substr($color, 2, 2)) . ', ' . hexdec(substr($color, 4, 2)) . ', ' . $alpha . ')';

    return $rgba;
}

