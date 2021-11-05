<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bendersay\Logging\Manager\ManagerEmail;

class bim_gui extends CModule
{
    /** @var string Для загрузки в маркет */
    var $MODULE_ID = 'bim.gui';

    function __construct()
    {
        $this->MODULE_ID = 'bim.gui';
        $this->setVersionData();

        $this->MODULE_NAME = "BIM GUI";
        $this->MODULE_DESCRIPTION = "BIM GUI";

        $this->PARTNER_NAME = "SOMETHING";
        $this->PARTNER_URI = "SOMETHING";

        $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = 'Y';
        $this->MODULE_GROUP_RIGHTS = 'Y';
    }

    private function setVersionData()
    {
        $arModuleVersion = [];
        include(__DIR__ . "/version.php");

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
    }

    function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);

        $this->installFiles();
        $this->registerEvents();
        return true;
    }

    function DoUninstall()
    {
        $this->unregisterEvents();
        $this->unInstallFiles();

        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    protected function registerEvents()
    {
        RegisterModuleDependences(
            "main",
            "OnBuildGlobalMenu",
            $this->MODULE_ID,
            "\\Bim\\Gui\\Events\\Menu",
            "OnBuildGlobalMenu",
            100
        );
    }

    protected function unregisterEvents()
    {
        UnRegisterModuleDependences(
            "main",
            "OnBuildGlobalMenu",
            $this->MODULE_ID,
            "\\Bim\\Gui\\Events\\Menu",
            "OnBuildGlobalMenu"
        );
    }

    public function installFiles()
    {
        CopyDirFiles(
            __DIR__ . '/bitrix/admin',
            $_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/',
            true,
            true
        );
    }

    public function unInstallFiles()
    {
        unlink($_SERVER['DOCUMENT_ROOT'] . '/bitrix/admin/bim_migrations.php');
    }
}
