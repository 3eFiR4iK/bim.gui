<?php

namespace Bim\Gui\Action;

use Bim\Gui\MigrationManager;
use Bitrix\Main\Grid\Options;
use Bitrix\Main\UI\PageNavigation;

class ListAction extends AbstractAction
{
    public function __invoke()
    {
        $grid_options = new Options('migrations_list');

        $sort = $grid_options->GetSorting(['sort' => ['timestamp' => 'desc'], 'vars' => ['by' => 'by', 'order' => 'order']]);
        if ($_GET['by']) {
            $sort['sort'] = [$_GET['by'] => $_GET['order']];
        }

        $rows = MigrationManager::getInstance()->getListMigrations($sort['sort']);

        $nav_params = $grid_options->GetNavParams();
        $nav = new PageNavigation('report_list');
        $nav->allowAllRecords(true)
            ->setPageSize($nav_params['nPageSize'])
            ->initFromUri();

        $GLOBALS['APPLICATION']->IncludeComponent(
            'bitrix:main.ui.grid',
            '',
            [
                'GRID_ID' => 'migrations_list',
                'COLUMNS' => [
                    ['id' => 'NAME', 'name' => 'Название', 'default' => true],
                    ['id' => 'DATE', 'name' => 'Дата', 'sort' => 'timestamp', 'sort_state' => $sort['sort']['timestamp'], 'default' => true],
                    ['id' => 'AUTHOR', 'name' => 'Автор', 'default' => true],
                    ['id' => 'DESCRIPTION', 'name' => 'Описание', 'default' => true],
                    ['id' => 'STATUS', 'name' => 'Статус', 'sort' => 'status', 'sort_state' => $sort['sort']['status'], 'default' => true],
                ],
                'ROWS' => $rows,
                'SHOW_ROW_CHECKBOXES' => true,
                'AJAX_OPTION_JUMP'          => 'N',
                'SHOW_CHECK_ALL_CHECKBOXES' => true,
                'SHOW_ROW_ACTIONS_MENU'     => true,
                'SHOW_GRID_SETTINGS_MENU'   => true,
                'SHOW_NAVIGATION_PANEL'     => true,
                'SHOW_PAGINATION'           => true,
                'SHOW_SELECTED_COUNTER'     => true,
                'SHOW_TOTAL_COUNTER'        => true,
                'SHOW_PAGESIZE'             => true,
                'SHOW_ACTION_PANEL'         => true,
                'ACTION_PANEL'              => [
                    'GROUPS' => [
                        'TYPE' => [
                            'ITEMS' => [
                                [
                                    'ID'    => 'set-type',
                                    'TYPE'  => 'DROPDOWN',
                                    'ITEMS' => [
                                        ['VALUE' => '', 'NAME' => '- Выбрать -'],
                                        ['VALUE' => 'up', 'NAME' => 'UP'],
                                        ['VALUE' => 'down', 'NAME' => 'DOWN']
                                    ]
                                ],
                            ],
                        ]
                    ],
                ],
                'ALLOW_COLUMNS_SORT'        => true,
                'ALLOW_COLUMNS_RESIZE'      => true,
                'ALLOW_HORIZONTAL_SCROLL'   => true,
                'ALLOW_SORT'                => true,
                'ALLOW_PIN_HEADER'          => true,
                'TOTAL_ROWS_COUNT'          => count($rows),
                'AJAX_OPTION_HISTORY'       => 'N',
                'AJAX_MODE' => 'Y',
                'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', '')
            ]
        );
    }
}