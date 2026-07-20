<?php

namespace app\helpers;

class GridView
{
    protected static $dataProviderView;
    protected static $moreDelete;

    public static function widget(array $config = []): string
    {
        if (!isset($config['dataProvider'])) {
            throw new \InvalidArgumentException('GridView: dataProvider is required');
        }

        $attributes   = $config['columns'] ?? [];
        $dataProvider = $config['dataProvider'];
        $options      = $config['options'] ?? ['class' => 'table table-bordered'];
        $pagination   = $config['pagination'] ?? null;
        $moreDelete   = $config['selectableDelete'] ?? false;

        // Имя первичного ключа для чекбоксов (по умолчанию 'id')
        $primaryKey   = $config['primaryKey'] ?? 'id';

        self::$dataProviderView = $dataProvider;
        self::$moreDelete = $moreDelete;

        $optionsString = self::renderOptions($options);

        $html = '';

        // Панель с кнопкой удаления
        if ($moreDelete) {
            $deleteUrl = $config['deleteUrl'] ?? '/admin/delete-multiple'; // Укажи свой URL
            $html .= '<div class="mb-2">';
            $html .= '<button type="button" class="btn btn-danger btn-sm js-delete-selected" data-url="' . htmlspecialchars($deleteUrl) . '">Удалить выбранные</button>';
            $html .= '</div>';
        }

        $html .= "<table{$optionsString}>";
        $html .= self::renderHeader($attributes, $moreDelete);
        $html .= self::renderBody($attributes, $dataProvider, $moreDelete, $primaryKey);
        $html .= "</table>";

        if ($pagination) {
            $html .= LinkPager::widget(['pagination' => $config['pagination']]);
        }

        return $html;
    }

    protected static function renderHeader(array $attributes, bool $moreDelete = false): string
    {
        $html = "<thead><tr>";

        // Чекбокс "Выбрать все"
        if ($moreDelete) {
            $html .= '<th style="width: 40px; text-align: center;"><input type="checkbox" class="js-select-all"></th>';
        }

        $labels = [];
        $labelModel = self::$dataProviderView[0] ?? null;
        if ($labelModel && is_object($labelModel) && method_exists($labelModel, 'attributeLabels')) {
            $labels = (array)$labelModel->attributeLabels();
        }

        foreach ($attributes as $attr) {
            if (is_string($attr)) {
                $attribute = $attr;
                $label = $labels[$attribute] ?? ucfirst($attribute);
            } else {
                $attribute = $attr['attribute'] ?? '';
                $label = $attr['label'] ?? ($labels[$attribute] ?? ucfirst($attribute));
            }

            $html .= "<th>{$label}</th>";
        }

        $html .= "</tr></thead>";
        return $html;
    }

    protected static function renderBody(array $attributes, array $dataProvider, bool $moreDelete = false, string $primaryKey = 'id'): string
    {
        $html = "<tbody>";

        if (empty($dataProvider)) {
            $colspan = count($attributes) + ($moreDelete ? 1 : 0);
            $html .= "<tr><td colspan=\"{$colspan}\" class=\"text-center text-muted\">Ничего не найдено</td></tr>";
            $html .= "</tbody>";
            return $html;
        }

        foreach ($dataProvider as $model) {
            $html .= "<tr>";

            // Чекбокс для строки
            if ($moreDelete) {
                $pkValue = is_array($model) ? ($model[$primaryKey] ?? '') : ($model->$primaryKey ?? '');
                $html .= '<td style="text-align: center;"><input type="checkbox" class="js-checkbox-row" value="' . htmlspecialchars($pkValue) . '"></td>';
            }

            foreach ($attributes as $attr) {
                $value = '';

                if (is_string($attr)) {
                    $value = self::getValue($model, $attr);
                } elseif (is_array($attr)) {
                    if (isset($attr['value']) && is_callable($attr['value'])) {
                        $value = call_user_func($attr['value'], $model);
                    } else {
                        $value = self::getValue($model, $attr['attribute'] ?? '');
                    }
                }

                $html .= "<td>{$value}</td>";
            }

            $html .= "</tr>";
        }

        $html .= "</tbody>";
        return $html;
    }

    protected static function getValue($model, string $attribute): string
    {
        if (is_array($model)) {
            $value = $model[$attribute] ?? '';
        } else {
            $value = $model->$attribute ?? '';
        }

        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }

    protected static function renderOptions(array $options): string
    {
        $result = '';

        foreach ($options as $key => $value) {
            $result .= ' ' . $key . '="' . htmlspecialchars($value) . '"';
        }

        return $result;
    }
}