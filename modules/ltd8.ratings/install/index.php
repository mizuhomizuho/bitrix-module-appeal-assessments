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
    public $MODULE_ID;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $MODULE_GROUP_RIGHTS;

    public function __construct()
    {
        $this->MODULE_ID = basename(dirname(__DIR__));

        $arModuleVersion = [];
        include __DIR__ . "/version.php";

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_GROUP_RIGHTS = "Y";

        $this->MODULE_NAME = "Оценки обращений";
        $this->MODULE_DESCRIPTION = "Оценки обращений";
        $this->PARTNER_NAME = "Ltd8";
    }

    public function GetModuleRightList()
    {
        return [
            "reference_id" => ["D", "R", "W"],
            "reference" => [
                "[D] Доступ запрещен",
                "[R] Чтение",
                "[W] Полный доступ"
            ],
        ];
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
        $data = [];
        foreach (range(1, 10) as $idMain) {
            $data[] = ["REQUEST_NUMBER" => rand(99999, 999999)];
        }
        MainTable::addMulti($data);

        CriterionTable::addMulti([
            ["NAME" => "Взаимодействие с оператором"],
            ["NAME" => "Вежливость"],
            ["NAME" => "Быстрота и правильность ответов"],
        ]);

        $data = [];
        foreach (range(1, 10) as $idMain) {
            foreach (range(1, 3) as $idCriterion) {
                $data[] = [
                    "MAIN_ID" => $idMain,
                    "CRITERION_ID" => $idCriterion,
                    "STARS" => rand(1, 5),
                ];
            }
        }
        DataTable::addMulti($data);
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
//            __DIR__."/components/employee",
//            $_SERVER["DOCUMENT_ROOT"]."/local/components/employee",
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
//        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"]."/local/components/employee");
//    }
}