<?php
namespace app\helpers;

class DetailView
{
    protected static $dataProviderView;
    public static function widget(array $config): string
    {
        if (!isset($config['model'])) {
            throw new \InvalidArgumentException('DetailView: model is required');
        }

        $model = $config['model'];
        $attributes = $config['attributes'] ?? [];
        $rows = '';

        $labels = [];
        if ($model && method_exists($model, 'attributeLabels')) {
            $labels = (array)$model->attributeLabels();
        }

        foreach ($attributes as $attr) {

            // simple: 'username'
            if (is_string($attr)) {
                $attribute = $attr;
                $label = $labels[$attribute] ?? ucfirst($attribute);
                $value = self::getValue($model, $attr);
            }

            // extended config
            elseif (is_array($attr)) {
                $attribute = $attr['attribute'];
                $label = $labels[$attribute] ?? ucfirst($attribute);

                if (isset($attr['value']) && is_callable($attr['value'])) {
                    $value = call_user_func($attr['value'], $model);
                } else {
                    $value = self::getValue($model, $attribute);
                }
            } else {
                continue;
            }

            $rows .= "<tr>
                <th>{$label}</th>
                <td>{$value}</td>
            </tr>";
        }



        return "<table class='table table-striped table-bordered detail-view' border='1' cellpadding='5'>{$rows}</table>";
    }

    protected static function getValue($model, string $attr): string
    {
        if (is_array($model)) {
            $value = $model[$attr] ?? '';
        } else {
            $value = $model->$attr ?? '';
        }

        return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
    }
}
