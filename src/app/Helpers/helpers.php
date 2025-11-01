<?php

if (!function_exists('conditionOptions')) {
    function conditionOptions(): array
    {
        return [
            1 => '良好',
            2 => '目立った傷や汚れなし',
            3 => 'やや傷や汚れあり',
            4 => '状態が悪い',
        ];
    }
}

if (!function_exists('getConditionText')) {
    function getConditionText(?int $value): string
    {
        return conditionOptions()[$value] ?? '不明';
    }
}