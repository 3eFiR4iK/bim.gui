<?php

namespace Bim\Gui\Entity;

use Bitrix\Main\Entity\DataManager;
use Bitrix\Main\ORM\Fields\StringField;

class BimTable extends DataManager
{
    public static function getTableName()
    {
        return 'bim_migrations';
    }

    public static function getMap()
    {
        return [
            new StringField('id', [
                'primary' => true,
                'autocomplete' => false,
            ]),
        ];
    }
}
