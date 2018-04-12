<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;

/**
 * m180410_202001_page_blocks_matrix_field migration.
 */
class m180410_202001_page_blocks_matrix_field extends Migration
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

        $commonGroup = null;
        $groups = Craft::$app->getFields()->getAllGroups();
        foreach($groups as $group) {
          if ($group->name == "Common") {
            $commonGroup = $group;
          }
        }

        if (is_null($commonGroup)) {
          $commonGroup = new \craft\models\FieldGroup();
          $commonGroup->name = "Common";
          Craft::$app->getFields()->saveGroup($commonGroup);
        }

        $placeholdersVolume = Craft::$app->volumes->getVolumeByHandle("placeholders");
        $placeholdersFolderTree = Craft::$app->assets->getFolderTreeByVolumeIds([$placeholdersVolume->id]);
        $placeholdersFolder = $placeholdersFolderTree[0]->id;

        /////////////////////////////////////////////
        ///////////////  MATRIX FIELD  //////////////
        /////////////////////////////////////////////
        /**
         * Field: Page Blocks
         *
         * Blocks:
         *    Text: Redactor field
         *    Image: Asset field with display options
         *    Video: Url field with display options
         *    Quote: Redactor field with attribution and display options
         *    Two Column Text: Two redactor fields
         *    Two Column Image & Caption: Super Table field with asset field and plain text field
         *
         * Error Checking:
         *    Craft::$app->matrix->validateFieldSettings($matrixField);
         *    echo "<pre>";
         *    print_r($matrixField);
         *    exit;
         */

        if (is_null(Craft::$app->getFields()->getFieldByHandle("pageBlocks"))) {
            $pageBlocks = new \craft\fields\Matrix([
                "groupId" => $commonGroup->id,
                "name" => "Page Blocks",
                "handle" => "pageBlocks",
                "instructions" => "",
                "translationMethod" => "none",
                "translationKeyFormat" => NULL,
                "minBlocks" => "",
                "maxBlocks" => "",
                "blockTypes" => [
                    new \craft\models\MatrixBlockType([
                        "name" => "Text",
                        "handle" => "text",
                        "fields" => [
                            new \craft\redactor\Field([
                                "name" => "Text",
                                "handle" => "text",
                                "instructions" => "",
                                "required" => true,
                                "redactorConfig" => "",
                                "availableVolumes" => "*",
                                "cleanupHtml" => 1,
                                "purifyHtml" => 1,
                                "purifierConfig" => "",
                                "columnType" => "text"
                            ])
                        ]
                    ]),
                    new \craft\models\MatrixBlockType([
                        "name" => "Image",
                        "handle" => "image",
                        "fields" => [
                            new \craft\fields\Assets([
                                "name" => "Image",
                                "handle" => "image",
                                "instructions" => "",
                                "required" => true,
                                "useSingleFolder" => "",
                                "singleUploadLocationSource" => "folder:".$placeholdersFolder,
                                "singleUploadLocationSubpath" => "",
                                "restrictFiles" => "",
                                "allowedKinds" => ["image"],
                                "limit" => 1,
                                "viewMode" => "list",
                                "selectionLabel" => ""
                            ]),
                            new \rias\positionfieldtype\fields\Position([
                                "name" => "Display",
                                "handle" => "display",
                                "instructions" => "",
                                "required" => false,
                                "options" => [
                                    "left" => "1",
                                    "center" => "1",
                                    "right" => "1",
                                    "full" => "1",
                                    "drop-left" => "",
                                    "drop-right" => ""
                                ],
                                "default" => "center"
                            ])
                        ]
                    ]),
                    new \craft\models\MatrixBlockType([
                        "name" => "Video",
                        "handle" => "video",
                        "fields" => [
                            new \craft\fields\Url([
                                "name" => "Video URL",
                                "handle" => "videoUrl",
                                "instructions" => "",
                                "required" => true
                            ]),
                            new \rias\positionfieldtype\fields\Position([
                                "name" => "Display",
                                "handle" => "display",
                                "instructions" => "",
                                "required" => false,
                                "options" => [
                                    "left" => "1",
                                    "center" => "1",
                                    "right" => "1",
                                    "full" => "",
                                    "drop-left" => "",
                                    "drop-right" => ""
                                ],
                                "default" => "center"
                            ])
                        ]
                    ]),
                    new \craft\models\MatrixBlockType([
                        "name" => "Quote",
                        "handle" => "quote",
                        "fields" => [
                            new \craft\redactor\Field([
                                "name" => "Quote",
                                "handle" => "quote",
                                "instructions" => "",
                                "required" => true,
                                "redactorConfig" => "Simple.json",
                                "availableVolumes" => "*",
                                "cleanupHtml" => 1,
                                "purifyHtml" => 1,
                                "purifierConfig" => "",
                                "columnType" => "text"
                            ]),
                            new \craft\fields\PlainText([
                                "name" => "Attribution",
                                "handle" => "attribution",
                                "instructions" => "",
                                "required" => false,
                                "placeholder" => "",
                                "charLimit" => "",
                                "multiline" => "",
                                "initialRows" => "4",
                                "columnType" => "string"
                            ]),
                            new \rias\positionfieldtype\fields\Position([
                                "name" => "Display",
                                "handle" => "display",
                                "instructions" => "",
                                "required" => false,
                                "options" => [
                                    "left" => "1",
                                    "center" => "1",
                                    "right" => "1",
                                    "full" => "",
                                    "drop-left" => "",
                                    "drop-right" => ""
                                ],
                                "default" => "center"
                            ])
                        ]
                    ]),
                    new \craft\models\MatrixBlockType([
                        "name" => "Two Column Text",
                        "handle" => "twoColumnText",
                        "fields" => [
                            new \craft\redactor\Field([
                                "name" => "Column 1",
                                "handle" => "column1",
                                "instructions" => "",
                                "required" => true,
                                "redactorConfig" => "",
                                "availableVolumes" => "*",
                                "cleanupHtml" => 1,
                                "purifyHtml" => 1,
                                "purifierConfig" => "",
                                "columnType" => "text"
                            ]),
                            new \craft\redactor\Field([
                                "name" => "Column 2",
                                "handle" => "column2",
                                "instructions" => "",
                                "required" => true,
                                "redactorConfig" => "",
                                "availableVolumes" => "*",
                                "cleanupHtml" => 1,
                                "purifyHtml" => 1,
                                "purifierConfig" => "",
                                "columnType" => "text"
                            ])
                        ]
                    ]),
                    new \craft\models\MatrixBlockType([
                        "name" => "Two Column Image & Caption",
                        "handle" => "twoColumnImageCaption",
                        "fields" => [
                            new \verbb\supertable\fields\SuperTableField([
                                "name" => "Columns",
                                "handle" => "columns",
                                "instructions" => "",
                                "required" => false,
                                "minRows" => "2",
                                "maxRows" => "2",
                                "localizeBlocks" => false,
                                "staticField" => "",
                                "fieldLayout" => "row",
                                "selectionLabel" => "",
                                "blockTypes" => [
                                    new \verbb\supertable\models\SuperTableBlockTypeModel([
                                        "fields" => [
                                            new \craft\fields\Assets([
                                                "name" => "Image",
                                                "handle" => "image",
                                                "instructions" => "",
                                                "required" => true,
                                                "useSingleFolder" => "",
                                                "singleUploadLocationSource" => "folder:".$placeholdersFolder,
                                                "singleUploadLocationSubpath" => "",
                                                "restrictFiles" => "",
                                                "allowedKinds" => ["image"],
                                                "limit" => 1,
                                                "viewMode" => "list",
                                                "selectionLabel" => ""
                                            ]),
                                            new \craft\fields\PlainText([
                                                "name" => "Caption",
                                                "handle" => "caption",
                                                "instructions" => "",
                                                "required" => false,
                                                "placeholder" => "",
                                                "charLimit" => "",
                                                "multiline" => "",
                                                "initialRows" => "4",
                                                "columnType" => "string"
                                            ])
                                        ]
                                    ])
                                ]
                            ])
                        ]
                    ])
                ]
            ]);

            Craft::$app->getFields()->saveField($pageBlocks);
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $pageBlocks = Craft::$app->getFields()->getFieldByHandle("pageBlocks");
        if (!is_null($pageBlocks)) {
          Craft::$app->getFields()->deleteFieldById($pageBlocks->id);
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
