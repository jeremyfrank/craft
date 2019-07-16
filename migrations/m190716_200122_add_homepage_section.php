<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;
use craft\models\Section;
use craft\models\Section_SiteSettings;

/**
 * m190716_200122_add_homepage_section migration.
 */
class m190716_200122_add_homepage_section extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        // add homepage section
        if (is_null(Craft::$app->sections->getSectionByHandle("homepage"))) {
            $section = new Section([
                "name" => "Homepage",
                "handle" => "homepage",
                "type" => Section::TYPE_SINGLE,
                "siteSettings" => [
                    new Section_SiteSettings([
                        "siteId" => Craft::$app->sites->getPrimarySite()->id,
                        "enabledByDefault" => true,
                        "hasUrls" => true,
                        "uriFormat" => "__home__",
                        "template" => "index"
                    ])
                ]
            ]);

            // save homepage section
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
        echo "m190716_200122_add_homepage_section cannot be reverted.\n";
        return false;
    }
}
