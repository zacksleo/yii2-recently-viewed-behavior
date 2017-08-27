<?php

namespace zacksleo\yii2\behaviors;

use yii;
use yii\base\Behavior;

/**
 * ERecentlyViewedBehavior is a behavior for managing recently viewed model items.
 */
class RecentlyViewedBehavior extends Behavior
{
    /**
     * @param integer the limit to the number of items in the recently viewed list.
     */
    public $limit = 8; // 0 = no limit.

    /**
     * Adds an item id to a 'recently viewed items' session object.
     * @var string the modelClass of the item to store
     * @param integer the id of the item to store
     */

    public function setRecentlyViewed($modelClass, $id)
    {
        // Create the session index
        $index = $modelClass . '_recently_viewed';
        // Check if the session index exists
        if (!isset(Yii::$app->session[$index])) {
            $recentlyViewed = [];
        }
        $recentlyViewed = Yii::$app->session[$index];
        // Remove the id if it is already in the list
        if (($key = array_search($id, $recentlyViewed)) !== false) {
            unset($recentlyViewed[$key]);
        }
        // If a limit is set, and the list is at (or over) the limit, remove oldest item(s)
        if ($this->limit > 0 && count($recentlyViewed) >= $this->limit) {
            $count = count($recentlyViewed) - $this->limit;
            $recentlyViewed = array_slice($recentlyViewed, $count);
        }
        // Add the current item id to the end of the array
        array_push($recentlyViewed, $id);
        // Update the session
        if (Yii instanceof yii\web\Application) {
            Yii::$app->getSession()->set($index, $recentlyViewed);
        }
    }

    /**
     * Retrieves model records from a 'recently viewed items' session object.
     * @param string $modelClass the modelClass of the items to retrieve
     * @return array|null|static[]
     */
    public function getRecentlyViewed($modelClass)
    {
        // Create the session index
        $index = $modelClass . '_recently_viewed';
        $models = array();
        // Check if the session index exists
        if (isset(Yii::$app->session[$index])) {
            /* @var $recentlyViewed \zacksleo\yii2\behaviors\Dictionary */
            $recentlyViewed = Yii::$app->session[$index];
            // Check if a limit is set, and if the list is at (or over) the limit
            if ($this->limit > 0 && count($recentlyViewed) >= $this->limit) {
                $count = count($recentlyViewed) - $this->limit;
                // Remove the oldest item(s) (always an index of 0 after each removal)
                $recentlyViewed = array_slice($recentlyViewed, $count);
            }
            // Reverse the array so the most recently added item is first
            $recentlyViewed = array_reverse($recentlyViewed);
            // Create a comma separated list for the db order property
            $commaSeparated = implode(',', $recentlyViewed);
            // Find all of the models with the array of ids recently viewed
            // and order the results in the same order as the array
            $models = $modelClass::find()->where(['id' => $recentlyViewed])->orderBy([new yii\db\Expression("FIELD (id, $commaSeparated)")])->all();
        }
        return $models;
    }

    public function clearRecentlyViewed($modelClass)
    {
        // Create the session index
        $index = $modelClass . '_recently_viewed';
        // Check if the session index exists
        if (isset(Yii::$app->session[$index])) {
            unset(Yii::$app->session[$index]);
        }
        echo json_encode([
            'success' => true
        ]);
    }
}
