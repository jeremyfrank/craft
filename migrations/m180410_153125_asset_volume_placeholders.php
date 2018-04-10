<?php

namespace craft\contentmigrations;

use Craft;
use craft\db\Migration;

/**
 * m180410_153125_asset_volume_placeholders migration.
 */
class m180410_153125_asset_volume_placeholders extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        /////////////////////////////////////////////
        //////////////  ASSET VOLUMES  //////////////
        /////////////////////////////////////////////
        /**
         * Volumes: Placeholders
         *
         * Options:
         *    craft\volumes\Local:
         *      path: '/path/to/folder'
         */

        if (is_null(Craft::$app->volumes->getVolumeByHandle("placeholders"))) {
            $volume = new \craft\volumes\Local([
                "name" => "Placeholders",
                "handle" => "placeholders",
                "hasUrls" => true,
                "url" => "@web/placeholders",
                "path" => "@webroot/placeholders"
            ]);

            Craft::$app->volumes->saveVolume($volume);
        }
    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $placeholders = Craft::$app->volumes->getVolumeByHandle("placeholders");
        if (!is_null($placeholders)) {
            Craft::$app->volumes->deleteVolumeById($placeholders->id);
            echo "m180330_201159_asset_volume_placeholders has been reverted.\n";
        }

        return true;
    }
}
