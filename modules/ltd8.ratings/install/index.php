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
        $this->MODULE_ID = "ltd8.ratings";

        $arModuleVersion = [];
        include __DIR__ . "/version.php";

        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_NAME = "Оценки обращений";
        $this->MODULE_DESCRIPTION = "Оценки обращений";
        $this->PARTNER_NAME = "Ltd8";

        $this->MODULE_GROUP_RIGHTS = "Y";
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
        global $APPLICATION;

        $request = Application::getInstance()->getContext()->getRequest();

        if ($request->isPost() && check_bitrix_sessid()) {

            ModuleManager::registerModule($this->MODULE_ID);

            Loader::includeModule($this->MODULE_ID);

            $this->InstallDB();

            if ($request->get("ltd8_ratings_add_test_criterion") === "on") {
                $this->AddContentCriterion();
            }

            if ($request->get("ltd8_ratings_add_test_data") === "on") {
                $this->AddContent();
            }

            $this->InstallFiles();
        } else {

            $parentDir = $this->GetParentDir();

            if (!class_exists(MainTable::class)) {
                Loader::registerAutoLoadClasses(null, [
                    MainTable::class => "/$parentDir/modules/ltd8.ratings/lib/MainTable.php",
                ]);
            }
            $connection = Application::getInstance()->getConnection();
            $isMainTableExists = $connection->isTableExists(MainTable::getTableName());

            $issetComponent = file_exists($_SERVER["DOCUMENT_ROOT"] . "/$parentDir/components/ltd8/ratings");

            define("LTD8_RATINGS_INSTALL_STEP_1_IS_MAIN_TABLE_EXISTS", $isMainTableExists);
            define("LTD8_RATINGS_INSTALL_STEP_1_ISSET_COMPONENT", $issetComponent);
            $APPLICATION->IncludeAdminFile(
                "Установка модуля",
                __DIR__ . "/install_step1.php"
            );
        }
    }

    private function GetParentDir(): string
    {
        $dir = "bitrix";
        $isLocal = strpos(__DIR__, $_SERVER["DOCUMENT_ROOT"] . "/local/") === 0;
        if ($isLocal) {
            $dir = "local";
        }
        return $dir;
    }

    public function DoUninstall(): void
    {
        global $APPLICATION;

        $request = Application::getInstance()->getContext()->getRequest();

        Loader::includeModule($this->MODULE_ID);

        if ($request->isPost() && check_bitrix_sessid()) {
            if ($request->get("ltd8_ratings_delete_components") === "on") {
                $this->UninstallComponentFiles();
            }
            if ($request->get("ltd8_ratings_delete_db") === "on") {
                $this->UninstallDB();
            }
            $this->UninstallFiles();
            ModuleManager::unRegisterModule($this->MODULE_ID);
        } else {
            $APPLICATION->IncludeAdminFile(
                "Удаление модуля",
                __DIR__ . "/uninstall_step1.php"
            );
        }
    }

    private function AddContentCriterion(): void
    {
        CriterionTable::addMulti([
            ["NAME" => "Взаимодействие с оператором"],
            ["NAME" => "Вежливость"],
            ["NAME" => "Быстрота и правильность ответов"],
        ]);
    }

    private function AddContent(): void
    {
        $data = [];
        foreach (range(1, 10) as $idMain) {
            $data[] = ["REQUEST_NUMBER" => rand(99999, 999999)];
        }
        MainTable::addMulti($data);

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
        $connection = Application::getConnection();

        if ($connection->isTableExists(MainTable::getTableName())) {
            return;
        }

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
    }

    public function InstallFiles()
    {
        $dir = $this->GetParentDir();
        CopyDirFiles(
            __DIR__ . "/components/ltd8",
            $_SERVER["DOCUMENT_ROOT"] . "/$dir/components/ltd8",
            true,
            true
        );
        CopyDirFiles(
            __DIR__ . "/admin",
            $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin",
            true,
            true
        );
    }

    public function UninstallDB(): void
    {
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

    private function IsDirEmpty(string $dir): bool
    {
        if (!is_readable($dir)) {
            return false;
        }
        return count(scandir($dir)) === 2;
    }

    private function UninstallComponentFiles()
    {
        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/ltd8/ratings");

        if ($this->IsDirEmpty($_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/ltd8")) {
            rmdir($_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/ltd8");
        }

        \Bitrix\Main\IO\Directory::deleteDirectory($_SERVER["DOCUMENT_ROOT"] . "/local/components/ltd8/ratings");

        if ($this->IsDirEmpty($_SERVER["DOCUMENT_ROOT"] . "/local/components/ltd8")) {
            rmdir($_SERVER["DOCUMENT_ROOT"] . "/local/components/ltd8");
        }

        if ($this->IsDirEmpty($_SERVER["DOCUMENT_ROOT"] . "/local/components")) {
            rmdir($_SERVER["DOCUMENT_ROOT"] . "/local/components");
        }
    }

    public function UninstallFiles()
    {
        if (file_exists($_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/ltd8_ratings.php")) {
            unlink($_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/ltd8_ratings.php");
        }
    }
}