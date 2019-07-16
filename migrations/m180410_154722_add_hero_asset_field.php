<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;

/**
 * m180410_154722_add_hero_asset_field migration.
 */
class m180410_154722_add_hero_asset_field extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        /////////////////////////////////////////////
        ///////////////  FIELD GROUP  ///////////////
        /////////////////////////////////////////////
        /**
         * Group: Common
         */

        $assetsGroup = null;
        $groups = Craft::$app->getFields()->getAllGroups();
        foreach($groups as $group) {
          if ($group->name == "Common") {
            $assetsGroup = $group;
          }
        }

        if (is_null($assetsGroup)) {
          $assetsGroup = new \craft\models\FieldGroup();
          $assetsGroup->name = "Common";
          Craft::$app->getFields()->saveGroup($assetsGroup);
        }

        $placeholdersVolume = Craft::$app->volumes->getVolumeByHandle("placeholders");
        $placeholdersFolderTree = Craft::$app->assets->getFolderTreeByVolumeIds([$placeholdersVolume->id]);
        $placeholdersFolder = $placeholdersFolderTree[0]->id;

        /////////////////////////////////////////////
        //////////////  ASSETS FIELDS  //////////////
        /////////////////////////////////////////////
        /**
         * Fields: Hero
         *
         * Options:
         *    useSingleFolder: 1
         *    sources: *, [folder:1]
         *    defaultUploadLocationSource: folder:1
         *    defaultUploadLocationSubpath: "path/to/subfolder"
         *    singleUploadLocationSource: folder:1
         *    singleUploadLocationSubpath: "path/to/subfolder"
         *    restrictFiles: 1
         *    allowedKinds: [access, audio, compressed, excel, flash, html, illustrator, image, javascript, json, pdf, photoshop, php, powerpoint, text, video, word, xml]
         *    viewMode: list, large
         */

        if (is_null(Craft::$app->getFields()->getFieldByHandle("hero"))) {
            $heroField = Craft::$app->getFields()->createField([
                "type" => "craft\\fields\\Assets",
                "groupId" => $assetsGroup->id,
                "name" => "Hero",
                "handle" => "hero",
                "instructions" => "",
                "translationMethod" => "none",
                "translationKeyFormat" => NULL,
                "settings" => [
                    "useSingleFolder" => "",
                    "singleUploadLocationSource" => "folder:".$placeholdersFolder,
                    "singleUploadLocationSubpath" => "",
                    "restrictFiles" => "1",
                    "allowedKinds" => ["image"],
                    "limit" => "1",
                    "viewMode" => "large",
                    "selectionLabel" => ""
                ]
            ]);
            Craft::$app->getFields()->saveField($heroField);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $hero = Craft::$app->getFields()->getFieldByHandle("hero");
        if (!is_null($hero)) {
          Craft::$app->getFields()->deleteFieldById($hero->id);
        }

        $groups = Craft::$app->getFields()->getAllGroups();
        foreach($groups as $group) {
          if (count(Craft::$app->getFields()->getFieldsByGroupId($group->id)) == 0 && $group->name == "Common") {
            Craft::$app->getFields()->deleteGroupById($group->id);
          }
        }

        return true;
    }
}
