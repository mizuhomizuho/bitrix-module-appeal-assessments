<?php

namespace Ltd8\Ratings\Admin;

use Bitrix\Main\Application;
use Bitrix\Main\Text\HtmlFilter;

class Edit
{
    private bool $isEdit;

    public function __construct(private string $tableClass)
    {
        $tableClass = $this->tableClass;
        $tableName = $tableClass::getTableName();
        $request = Application::getInstance()->getContext()->getRequest();
        $this->isEdit = $request->get($tableName . "_id") !== null;
    }

    private function change()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $isEdit = $this->isEdit;
        $tableClass = $this->tableClass;
        $tableName = $tableClass::getTableName();

        if ($request->get($tableName . "_name") !== null) {
            $editParams = [
                "NAME" => (string)$request->get($tableName . "_name"),
            ];
            if ($isEdit) {
                $tableClass::update((int)$request->get($tableName . "_id"), $editParams);
                \CAdminMessage::ShowNote("Успешно изменено");
            } else {
                $tableClass::add($editParams);
                \CAdminMessage::ShowNote("Успешно добавлено");
            }
        }
    }

    public function echo()
    {
        $request = Application::getInstance()->getContext()->getRequest();
        $tableClass = $this->tableClass;
        $tableName = $tableClass::getTableName();
        $isEdit = $this->isEdit;

        $this->change();

        $currentValue = "";
        if ($isEdit) {
            $result = $tableClass::getById((int)$request->get($tableName . "_id"));
            $row = $result->fetch();
            $currentValue = $row["NAME"];
        }

        $tabText = "Добавить";
        if ($isEdit) {
            $tabText = "Изменить";
        }

        $aTabs = [
            ["DIV" => $tableName . "_tab", "TAB" => $tabText, "TITLE" => $tabText],
        ];

        $tabControl = new \CAdminTabControl($tableName . "_tab_control", $aTabs);

        ?>

        <form method="post">

            <?= bitrix_sessid_post() ?>

            <?php $tabControl->Begin() ?>

            <?php $tabControl->BeginNextTab() ?>

            <tr>
                <td>
                    Критерий
                </td>
                <td>
                    <input type="text" style="width: 100%;" name="<?= $tableName ?>_name"
                           value="<?= HtmlFilter::encode($currentValue) ?>"/>
                </td>
            </tr>

            <?php $tabControl->Buttons() ?>

            <input type="submit" name="save" value="Сохранить" class="adm-btn-save"/>

            <?php $tabControl->End() ?>

        </form>
        <?php
    }
}