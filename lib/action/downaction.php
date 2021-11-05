<?php
namespace Bim\GUI\Action;


use Bim\Gui\MigrationManager;
use Bim\Util\Config;
use ConsoleKit\Console;

class DownAction extends AbstractAction
{
    public function __invoke()
    {
        if (!$_GET['name']) {
            $this->redirect('list', 'Не правильное название миграции', 'error');
        }

        try {
            $conf = new Config("commands");
            $console = new Console($conf->get("commands"));

            $console->run(['init']);
            $console->run(['down', $_GET['name']]);

            if (!MigrationManager::getInstance()->checkInDb($_GET['name'])) {
                $this->redirect('list', 'Миграция ' . $_GET['name'] . ' успешно откачена', 'success');
            } else {
                $this->redirect('list', 'Миграция ' . $_GET['name'] . 'не откатилась', 'error');
            }

        } catch (\Exception $e) {
            $this->redirect('list', 'Что-то пошло не так', 'error');
        }
    }
}
