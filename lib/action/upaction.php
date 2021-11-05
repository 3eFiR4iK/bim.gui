<?php
namespace Bim\GUI\Action;


use Bim\Gui\MigrationManager;
use Bim\Util\Config;
use ConsoleKit\Console;

class UpAction extends AbstractAction
{
    public function __invoke()
    {
        if (!$_GET['name']) {
            $this->redirect('list', 'Не правильное название миграции');
        }

        try {
            $conf = new Config("commands");
            $console = new Console($conf->get("commands"));

            $console->run(['init']);
            $console->run(['up', $_GET['name']]);

            if (MigrationManager::getInstance()->checkInDb($_GET['name'])) {
                $this->redirect('list', 'Миграция ' . $_GET['name'] . ' применена', 'success');
            } else {
                $this->redirect('list', 'Миграция ' . $_GET['name'] . 'не применена', 'error');
            }

        } catch (\Exception $e) {
            $this->redirect('list', 'Что-то пошло не так', 'error');
        }
    }
}
