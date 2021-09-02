<?php

/**
 * @package @mrmuminov\printpagegenerator
 * @author Bahriddin Mo'minov
 * @email darkshadeuz@gmail.com
 * @site https://bahriddin.uz
 */

use \yii\helpers\Html;
use mrmuminov\printpagegenerator\components\Item;

/**
 * @var \yii\web\View $this
 * @var Model $model page width
 * @var array[] $items page height
 */
?>

<?php foreach ($data as $row) { ?>
    <div class="parent">
        <?php
        foreach ($items as $item) {
            if ($item['x'] == '0' && $item['x'] == '0') {
                continue;
            }
            $itemModel = new Item();
            $itemModel->id = $item['attribute_id'];
            $itemModel->type = $item['type'];
            if (!empty($item['label'])) {
                $itemModel->label = $item['label'];
            }
            if (!empty($item['align'])) {
                $itemModel->align = $item['align'];
            }
            if (!empty($item['fontSize'])) {
                $itemModel->fontSize = $item['fontSize'];
            }
            if (!empty($item['bold'])) {
                $itemModel->bold = $item['bold'];
            }
            if (!empty($item['italic'])) {
                $itemModel->italic = $item['italic'];
            }
            if (!empty($item['underline'])) {
                $itemModel->underline = $item['underline'];
            }
            if (!empty($item['width'])) {
                $itemModel->width = $item['width'];
            }
            if (!empty($item['height'])) {
                $itemModel->height = $item['height'];
            }
            if (!empty($item['x'])) {
                $itemModel->x = is_numeric($item['x']) ? $item['x'] . "px" : $item['x'];
            }
            if (!empty($item['y'])) {
                $itemModel->y = is_numeric($item['y']) ? $item['y'] . "px" : $item['y'];
            }
            echo $itemModel->render($row);
        }
        ?>
    </div>
<?php } ?>

<style>
    .parent {
        position: relative;
        box-sizing: border-box;
        border: 1px dashed #00000033;
        width: <?= is_numeric($model['w']) ? $model['w'].'mm' : $model['w']?>;
        height: <?= is_numeric($model['h']) ? $model['h'].'mm' : $model['h']?>;
    }

    .drop-item {
        position: absolute;
    }
</style>
