<?php

use Bitrix\Main\Application;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Ltd8\Ratings\CriterionTable;
use Ltd8\Ratings\DataTable;
use Ltd8\Ratings\MainTable;

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

    private function AddContent(): void
    {
        CriterionTable::addMulti([
            ["NAME" => "Взаимодействие с оператором"],
            ["NAME" => "Вежливость"],
            ["NAME" => "Быстрота и правильность ответов"],
        ]);
    }

    public function InstallDB(): void
    {
        if (!Loader::includeModule($this->MODULE_ID)) {
            return;
        }

        $connection = Application::getConnection();

        MainTable::getEntity()->createDbTable();
        $tableName = MainTable::getEntity()->getDBTableName();
        $connection->query(
            "CREATE UNIQUE INDEX ix_{$tableName}_request_number ON $tableName (REQUEST_NUMBER)");

        DataTable::getEntity()->createDbTable();
        $tableName = DataTable::getEntity()->getDBTableName();
        $connection->query(
            "CREATE INDEX ix_{$tableName}_main_id ON $tableName (MAIN_ID)");
        $connection->query(
            "CREATE INDEX ix_{$tableName}_criterion_id ON $tableName (CRITERION_ID)");
        $connection->query(
            "CREATE UNIQUE INDEX ix_{$tableName}_main_id_criterion_id ON $tableName (MAIN_ID, CRITERION_ID)");

        CriterionTable::getEntity()->createDbTable();

        $this->AddContent();
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
        if ($connection->isTableExists(DataTable::getTableName())) {
            $connection->dropTable(DataTable::getTableName());
        }
        if ($connection->isTableExists(CriterionTable::getTableName())) {
            $connection->dropTable(CriterionTable::getTableName());
        }
    }

//    public function UninstallFiles()
//    {
//        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER['DOCUMENT_ROOT'].'/local/components/employee');
//    }
}