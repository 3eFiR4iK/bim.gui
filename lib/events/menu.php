<?php

namespace Bim\Gui\Events;

class Menu
{
    /**
     * Добавляет в главное меню пункт "Миграции"
     *
     * @param $aGlobalMenu
     * @param $aModuleMenu
     */
    public static function OnBuildGlobalMenu(&$aGlobalMenu, &$aModuleMenu)
    {
        $aGlobalMenu[] = array(
            'menu_id' => 'migrations',
            'text' => 'Миграции',
            'title' => 'Миграции',
            'url' => 'bim_migrations.php?lang=ru',
            'sort' => 500,
            'items_id' => 'global_menu_migrations',
            'help_section' => 'migrations',
            "items" => array(
                array(
                    "text" => 'Список миграций',
                    "url" => "/bitrix/admin/bim_migrations.php?action=list",
                ),
                array(
                    "text" => 'Создать миграцию',
                    "url" => "/bitrix/admin/bim_migrations.php?action=showCreateForm",
                ),
            )
        );
    }
}