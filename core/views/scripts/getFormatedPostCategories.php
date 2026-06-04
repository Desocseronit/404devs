<?php
require_once(__DIR__.'/../../Application.php');
use core\{Application , Database};

$allCategories = Database::instance()->selectRecord('categories', '*')->items();
$GC = [];
$usedIDs = [];
foreach ($allCategories as $item) {
    $category = $item->getValue();
    $globalCatId = $category->global_cat_id;
    if (!array_key_exists($globalCatId, $usedIDs)) {
        $usedIDs[$globalCatId] = Database::instance()->getOne('global_categories', $globalCatId)->name;
        $GC[$usedIDs[$globalCatId]] = [];
    }
    unset($category->attributes);
    unset($category->global_cat_id);
    $GC[$usedIDs[$globalCatId]][] = $category;
}

echo json_encode($GC);