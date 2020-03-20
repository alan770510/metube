<?php


class ButtonProvider
{
    public static function createButton($text, $imgSrc = null, $action, $class, $icon =null){
        $imgSrc = ($imgSrc == null) ? "" : "<img src='$imgSrc'>";
        $icon = ($icon == null) ? "" : "<i class='iconfont icon-$icon'></i>";
        return "
                <button class='$class' onclick='$action'>
                    $imgSrc
                    $icon
                    <span class='text'>
                        $text
                    </span>
                </button>
        ";
    }
}