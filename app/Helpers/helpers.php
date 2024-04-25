<?php

if (!function_exists('prettyPrintObject')) {
    function prettyPrintObject($object): string
    {
        return json_encode(
            $object,
            JSON_UNESCAPED_SLASHES |
            JSON_UNESCAPED_UNICODE |
            JSON_PRETTY_PRINT |
            JSON_PARTIAL_OUTPUT_ON_ERROR |
            JSON_INVALID_UTF8_SUBSTITUTE
        );
    }
}
if (!function_exists('prettyPrintObjectProperties')) {
    function prettyPrintObjectProperties(mixed $object = null, $level = 1): string | null
    {
        if (is_null($object))
        {
            return null;
        }
        elseif (is_array($object))
        {
            $msg = PHP_EOL . str_repeat("\t\t", $level-1) . '[';
            foreach ($object as $obj) {
                if (is_object($obj)) {
                    $objMsg = prettyPrintObjectProperties($obj, $level + 1);
                    $msg .= substr($objMsg, 0, -1) . ',';
                } else {
                    $msg .= $obj . ',' . PHP_EOL;
                }
            }
            $msg .= PHP_EOL . str_repeat("\t\t", $level-1) . ']';
        }
        else
        {
            if ($level > 1) {
                $msg = PHP_EOL . str_repeat("\t\t", $level-1); // add tabulation
            } else {
                $msg = PHP_EOL;
            }
            $msg .= '{' . PHP_EOL;
            foreach (get_object_vars($object) as $attribute => $value) {
                if ($value !== null) {
                    $msg .= str_repeat("\t\t", $level); // add tabulation
                    $msg .= '"' . $attribute . '":';
                    if (is_object($value) || is_array($value)) {
                        $msg .= prettyPrintObjectProperties($value, $level+1);
                    } elseif (is_bool($value)) {
                        $msg .= ' ' . var_export($value, true) . PHP_EOL;
                    } else {
                        $msg .= ' ' . $value . PHP_EOL;
                    }
                }
            }
            if ($level > 1) {
                $msg .= str_repeat("\t\t", $level-1) . '}' . PHP_EOL; // add tabulation
//                $msg .= str_repeat("\t\t", $level) . '}'; // add tabulation
            } else {
                $msg .= '}';
            }
        }
        return $msg;
    }
}
