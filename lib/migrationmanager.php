<?php
namespace Bim\Gui;

use BaseCommand;
use Bim\Gui\Entity\BimTable;
use Bitrix\Main\Type\DateTime;
use Korus\Main\SingletonTrait;

class MigrationManager extends BaseCommand
{
    use SingletonTrait;

    public function execute(array $args, array $options = array())
    {
        return [];
    }

    /**
     * @param array $sort
     * @return array
     * @throws \Bim\Exception\BimException
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getListMigrations(array $sort = []): array
    {
        define(MODULE_MIGRATIONS, false);

        $migrations = $this->getDirectoryTree($this->getMigrationPath(), 'php');
        $appliedMigrations = $this->getAppliedMigrations();

        $formatMigrations = [];
        foreach ($migrations as $migration) {
            $status = $appliedMigrations[$migration['name']] ? 'green' : 'red';
            $formatMigrations[] = [
                'data' => [
                    'NAME' => '<span style="color: '. $status .'">' . $migration['name'] .'</status>',
                    'DATE' => DateTime::createFromTimestamp($migration['date'])->format('Y.m.d G:i'),
                    'AUTHOR' => $migration['author'],
                    'DESCRIPTION' => $migration['description'],
                    'STATUS' => '<span class="adm-lamp adm-lamp-in-list adm-lamp-'. $status .'"></span>',
                    'timestamp' => $migration['date'],
                    'status' => $status == 'green' ? 1 : 0,
                ],
                'actions' => [
                    $status == 'green'
                        ? [
                        'text'    => 'DOWN',
                        'onclick' => 'document.location.href="/bitrix/admin/bim_migrations.php?action=down&name='. $migration['name'] .'"'
                    ]
                        : [
                        'text'    => 'UP',
                        'onclick' => 'document.location.href="/bitrix/admin/bim_migrations.php?action=up&name='. $migration['name'] .'"'
                    ]
                ]
            ];
        }

        if (count($sort) > 0) {
            return $this->sortItems($formatMigrations, $sort);
        }

        return $formatMigrations;
    }

    /**
     * Сортировка элементов по полю
     *
     * @param array $items
     * @param array $sort
     * @return array
     */
    private function sortItems(array $items, array $sort)
    {
        foreach ($sort as $key => $order) {
            usort($items, function ($a, $b) use ($key, $order) {
                if ($order == 'desc') {
                    return $a['data'][$key] > $b['data'][$key];
                }

                return $a['data'][$key] < $b['data'][$key];
            });
        }

        return $items;
    }

    /**
     * Возвращает список примененных миграций
     *
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    public function getAppliedMigrations(): array
    {
        $res = [];
        $query = BimTable::getList();

        while ($m = $query->fetch()) {
            $res[$m['id']] = $m;
        }

        return $res;
    }

}