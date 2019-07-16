<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;
use craft\models\Section;
use craft\models\Section_SiteSettings;

/**
 * m190717_020038_add_parts_kit_section migration.
 */
class m190717_020038_add_parts_kit_section extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // add parts kit section
        if (is_null(Craft::$app->sections->getSectionByHandle("parts"))) {
            $section = new Section([
                "name" => "Parts Kit",
                "handle" => "parts",
                "type" => Section::TYPE_STRUCTURE,
                "enableVersioning" => true,
                "siteSettings" => [
                    new Section_SiteSettings([
                        "siteId" => Craft::$app->sites->getPrimarySite()->id,
                        "enabledByDefault" => false,
                        "hasUrls" => true,
                        "uriFormat" => "parts/{% if object.level == 1 %}{slug}{% else %}{parent.uri}/{slug}{% endif %}",
                        "template" => "parts/_entry"
                    ])
                ]
            ]);

            // save parts kit section
            $success = Craft::$app->sections->saveSection($section);

            // show any errors
            $errors = $section->getErrors();
            foreach ($errors as $error) {
              echo $error[0];
            }
        }

        return $success;
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        echo "m190717_020038_add_parts_kit_section cannot be reverted.\n";
        return false;
    }
}
