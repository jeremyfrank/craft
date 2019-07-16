<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;
use craft\models\Section;
use craft\models\Section_SiteSettings;

/**
 * m190717_014639_add_page_section migration.
 */
class m190717_014639_add_page_section extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // add page section
        if (is_null(Craft::$app->sections->getSectionByHandle("page"))) {
            $section = new Section([
                "name" => "Page",
                "handle" => "page",
                "type" => Section::TYPE_STRUCTURE,
                "enableVersioning" => true,
                "siteSettings" => [
                    new Section_SiteSettings([
                        "siteId" => Craft::$app->sites->getPrimarySite()->id,
                        "enabledByDefault" => false,
                        "hasUrls" => true,
                        "uriFormat" => "{% if object.level == 1 %}{slug}{% else %}{parent.uri}/{slug}{% endif %}",
                        "template" => "page/_entry"
                    ])
                ]
            ]);

            // save page section
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
        echo "m190717_014639_add_page_section cannot be reverted.\n";
        return false;
    }
}
