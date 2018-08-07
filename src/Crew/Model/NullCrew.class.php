<?php
namespace Crew\Model;

use Marmot\Core;
use System\Interfaces\INull;

class NullCrew extends Crew implements INull
{
    private static $instance;

    public static function &getInstance()
    {
        if (!self::$instance instanceof self) {
            self::$instance = new self();
        }
        return self::$instance;
    }
}
