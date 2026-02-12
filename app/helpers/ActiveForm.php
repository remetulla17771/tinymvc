<?php

namespace app\helpers;
class ActiveForm
{
    public static function begin($method = 'post', $options = [])
    {
        $optionsStr = '';

        if(isset($options['action'])){
            $optionsStr .= "action=\"". $options['action']."\"";
        }

        foreach ($options as $key => $value) {
            $optionsStr .= "$key=\"$value\" ";
        }



        echo "<form method=\"$method\" $optionsStr>";
    }

    public static function end()
    {
        echo "</form>";
    }

    public static function field($model, $attribute, $options = [])
    {
        $label = isset($options['label']) ? $options['label'] : ucwords(str_replace("_", " ", $attribute));
        $inputType = isset($options['type']) ? $options['type'] : 'text';
        $value = $model->$attribute;

        $optionsStr = '';
        foreach ($options as $key => $value) {
            $optionsStr .= "$key=\"$value\" ";
        }

        return "
            <div class=\"form-group\" $optionsStr>
                <label for=\"$attribute\">$label</label>
                <input type=\"$inputType\" id=\"$attribute\" name=\"$attribute\" value=\"$value\" class=\"form-control\">
            </div>
        ";
    }

    public static function submitButton($label = 'Submit', $options = [])
    {
        $optionsStr = '';
        foreach ($options as $key => $value) {
            $optionsStr .= "$key=\"$value\" ";
        }
        return "<button type=\"submit\" $optionsStr>$label</button>";
    }

    public static function dropdown($model, $attribute, $items = [], $options = [])
    {
        $label = isset($options['label']) ? $options['label'] : ucfirst($attribute);
        $selected = isset($model->$attribute) ? $model->$attribute : null;
        $attributes = self::renderAttributes($options);

        $dropdown = "<div class=\"form-group\">";
        $dropdown .= "<label for=\"$attribute\">$label</label>";
        $dropdown .= "<select id=\"$attribute\" name=\"$attribute\" $attributes class=\"form-control\">";

        foreach ($items as $value => $text) {
            $isSelected = $value == $selected ? 'selected' : '';
            $dropdown .= "<option value=\"$value\" $isSelected>$text</option>";
        }

        $dropdown .= "</select></div>";

        return $dropdown;
    }

    private static function renderAttributes($attributes)
    {
        $html = '';
        foreach ($attributes as $key => $value) {
            $html .= " $key=\"$value\"";
        }
        return $html;
    }
}
