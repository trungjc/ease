<?php
/**
 * Description of Flash
 *
 * @author dmitry
 */

class Flash {
    private static $type = 'warning';
    private static $cssClass = 'alert';

    public static function setFlash($messages, $type = null) {
        if (!is_null($type)) self::$type = $type;
        switch(self::$type) {
            case 'info':
                self::$cssClass .= ' alert-info';
                break;
            case 'success':
                self::$cssClass .= ' alert-success';
                break;
            case 'error':
                self::$cssClass .= ' alert-error';
                break;
        }
        echo '<div class="' . self::$cssClass . '" id="flash">';
        echo '<b id="type">' . self::$type .'!</b><br>';
        if (is_array($messages)) {
            foreach ($messages as $message) {
                echo '<span>' . $message . '</span><br>';
            }
        } else {
            echo '<span>' . $messages . '</span>';
        }
        echo '</div>';
    }
}