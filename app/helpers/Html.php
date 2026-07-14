<?php

namespace app\helpers;

class Html
{
    public static function a($text, $url, $attributes = [])
    {


        $attributesStr = self::buildAttributesString($attributes);

        $controllerAction = $url[0];

        unset($url[0]);
        $l = "";

        if (count($url) >= 1) {
            $l .= "?";
            foreach ($url as $key => $value) {
                $l .= "$key=$value&";
            }
        }

        $l = trim($l, "&");
        $confirmText = null;
        if (isset($attributes['data-confirm'])) {
            $confirmText = `data-confirm='$confirmText'`;
        }

//        return `<a href="${$controllerAction}${$l}" ${$attributes}>$text</a>`;
        return "<a $confirmText href=\"$controllerAction" . "$l\"$attributesStr>$text</a>";
    }

    public static function dropdown($name, $items = [], $options = [])
    {
        // Check for the selected item
        $selected = isset($options['selected']) ? $options['selected'] : null;
        unset($options['selected']); // Remove selected from options to avoid rendering it as an attribute

        // Start the select tag with name and any additional attributes
        $attributes = self::renderAttributes($options);
        $dropdown = "<select name=\"$name\"$attributes>";

        // Generate options
        foreach ($items as $value => $label) {
            $isSelected = $value == $selected ? ' selected' : '';
            $dropdown .= "<option value=\"$value\"$isSelected>$label</option>";
        }

        // Close the select tag
        $dropdown .= '</select>';

        return $dropdown;
    }

    public static function tag($tagName, $content = '', $options = [])
    {
        $html = "<$tagName";

        // Add attributes
        if (!empty($options)) {
            foreach ($options as $attribute => $value) {
                $html .= " $attribute=\"$value\"";
            }
        }

        // Self-closing tag or regular tag with content
        if (in_array($tagName, ['input', 'img', 'br', 'hr', 'meta', 'link'])) {
            $html .= " />";
        } else {
            $html .= ">$content</$tagName>";
        }

        return $html;
    }

    private static function renderAttributes($attributes)
    {
        $html = '';
        foreach ($attributes as $key => $value) {
            $html .= " $key=\"$value\"";
        }
        return $html;
    }

    private static function generateQueryStringLink($url, $text, $attributes)
    {
        $attributesStr = self::buildAttributesString($attributes);

    }

    private static function generateCleanUrlLink($url, $text, $attributes)
    {
        $attributesStr = self::buildAttributesString($attributes);
        return "<a href=\"$url\"$attributesStr>$text</a>";
    }

    private static function buildAttributesString($attributes)
    {
        $attributesStr = '';
        foreach ($attributes as $key => $value) {
            $attributesStr .= " $key=\"$value\"";
        }
        return $attributesStr;
    }

    public static function encode($content, $doubleEncode = true)
    {
        return htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8', $doubleEncode);
    }

    public static function decode($content)
    {
        return htmlspecialchars_decode($content, ENT_QUOTES);
    }

    public static function style($content, $options = [])
    {
        return static::tag('style', $content, $options);
    }

    public static function script($content, $options = [])
    {
        return static::tag('script', $content, $options);
    }

    public static function button($content = 'Button', $options = [])
    {
        if (!isset($options['type'])) {
            $options['type'] = 'button';
        }

        return static::tag('button', $content, $options);
    }

    public static function submitButton($content = 'Submit', $options = [])
    {
        $options['type'] = 'submit';
        return static::button($content, $options);
    }

    public static function input($type, $name = null, $value = null, $options = [])
    {
        if (!isset($options['type'])) {
            $options['type'] = $type;
        }
        $options['name'] = $name;
        $options['value'] = $value === null ? null : (string)$value;
        return static::tag('input', '', $options);
    }

    public static function textInput($name, $value = null, $options = [])
    {
        return static::input('text', $name, $value, $options);
    }

    public static function hiddenInput($name, $value = null, $options = [])
    {
        return static::input('hidden', $name, $value, $options);
    }

    public static function passwordInput($name, $value = null, $options = [])
    {
        return static::input('password', $name, $value, $options);
    }

    public static function fileInput($name, $value = null, $options = [])
    {
        return static::input('file', $name, $value, $options);
    }


}

?>
