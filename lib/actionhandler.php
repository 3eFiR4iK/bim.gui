<?php

namespace Bim\Gui;

use Korus\Main\SingletonTrait;

class ActionHandler
{
    use SingletonTrait;

    /**
     * Смотрит если экшн валидный, то запускает его
     */
    public function handle()
    {
        define(MODULE_MIGRATIONS, false);
        $action = '\\Bim\\Gui\\Action\\' . $_GET['action'] . 'Action';
        if (
            !$_GET['action']
            || $_GET['action'] == ''
            || !class_exists($action)
        ) {
            ShowError('Экшен не найден');
            return;
        }

        $this->checkMessage();

        $actionObj = new $action();
        $actionObj();
    }

    /**
     * Проверят тип и текст сообщения и выводит его
     */
    private function checkMessage()
    {
        if ($_GET['messageType'] == 'success') {
            $class = 'green';
            $title = 'Успех!';
        } else {
            $class = 'red';
            $title = 'Ошибка';
        }

        if ($_GET['message']) {
            echo '<div class="adm-info-message-wrap adm-info-message-'. $class .'">
				<div class="adm-info-message">
					<div class="adm-info-message-title">'. $title .'</div>
					'. $_GET['message'] .'
					<div class="adm-info-message-icon"></div>
				</div>
			</div>';
        }
    }
}
