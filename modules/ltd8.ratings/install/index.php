<?php

use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Ltd8\Ratings\MainTable;
use Ltd8\Ratings\DataTable;

Loc::loadMessages(__FILE__);

class ltd8_ratings extends CModule
{
    public function __construct()
    {
        $this->MODULE_ID = basename(dirname(__DIR__));

        $arModuleVersion = [];
        include __DIR__ . '/version.php';

        $this->MODULE_VERSION = $arModuleVersion['VERSION'];
        $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];

        $this->MODULE_GROUP_RIGHTS = 'N';

        $this->MODULE_NAME = 'Оценки обращений';
        $this->MODULE_DESCRIPTION = 'Оценки обращений';
        $this->PARTNER_NAME = 'Ltd8';

    }

    public function DoInstall(): void
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $this->installDB();
//        $this->installFiles();
    }

    public function DoUninstall(): void
    {
        $this->uninstallDB();
//        $this->uninstallFiles();
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }

    public function InstallDB(): void
    {
        if (!Loader::includeModule($this->MODULE_ID)) {
            return;
        }

        $connection = Application::getConnection();

        MainTable::getEntity()->createDbTable();
        $tableName = MainTable::getEntity()->getDBTableName();
        $connection->query("CREATE INDEX ix_ltd8_ratings_main_number ON $tableName (REQUEST_NUMBER)");

        DataTable::getEntity()->createDbTable();
        $tableName = DataTable::getEntity()->getDBTableName();
        $connection->query("CREATE INDEX ix_ltd8_ratings_data_main_id ON $tableName (MAIN_ID)");
    }

//    public function InstallFiles()
//    {
///home/bitrix/www/bitrix/css/mycomp.exchangerates/style.css
///home/bitrix/www/bitrix/js/mycomp.exchangerates/script.js
//        CopyDirFiles(
//            __DIR__.'/components/employee',
//            $_SERVER['DOCUMENT_ROOT'].'/local/components/employee',
//            true,
//            true
//        );
//    }

    public function UninstallDB(): void
    {
        if (!Loader::includeModule($this->MODULE_ID)) {
            return;
        }
        $connection = Application::getInstance()->getConnection();
        if ($connection->isTableExists(MainTable::getTableName())) {
            $connection->dropTable(MainTable::getTableName());
        }
    }

//    public function UninstallFiles()
//    {
//        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER['DOCUMENT_ROOT'].'/local/components/employee');
//    }
}