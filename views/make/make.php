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
 * @var int $w page width
 * @var int $h page height
 * @var Item[] $staticVariables
 * @var Item[] $dynamicVariables
 */
?>
<div class="row print-page-generator">
    <div class="col-md-5">
        <?= Html::beginForm([
            'id' => 'variables-form'
        ])?>
            <div class="row">
                <div class="col-md-4">
                    <?= Html::label("Width")?>
                    <div class="input-group">
                        <?= Html::input('number', 'page[width]', $w, [
                            'id' => 'page-width',
                            'step' => 0.1,
                            'class' => 'form-control on-change-handle'
                        ]) ?>
                        <span class="input-group-addon">mm</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <?= Html::label("Height")?>
                    <div class="input-group">
                        <?= Html::input('number', 'page[height]', $h, [
                            'id' => 'page-height',
                            'step' => 0.1,
                            'class' => 'form-control on-change-handle'
                        ]) ?>
                        <span class="input-group-addon">mm</span>
                    </div>
                </div>
                <div class="col-md-6 safe-drop-zone"
                     id="safe-drop-zone"
                     ondrop="drop(event)"
                     ondragover="allowDrop(event)">
                    <br>
                    <?php foreach ($staticVariables as $key => $item) {
                        $staticVariable = new Item($item);
                        echo $staticVariable->render();
                        $staticVariables[$key]['id'] = $staticVariable->getId();
                    } ?>
                </div>
                <div class="col-md-6 safe-drop-zone"
                     id="safe-drop-zone"
                     ondrop="drop(event)"
                     ondragover="allowDrop(event)">
                    <br>
                    <?php foreach ($dynamicVariables as $item) {
                        echo (new Item($item))->render();
                    } ?>
                </div>
                <div class="col-md-12 text-center">
                    <br>
                    <?= Html::button('Saqlash', [
                        'class' => 'btn btn-sm btn-primary',
                        'onclick' => 'saveTemplate();',
                    ]) ?>
                </div>
        </div>
        <?= Html::endForm()?>
    </div>
    <div class="col-md-7">
        <div class="backdrop">
            <div class="page"
                 id="page"
                 ondrop="drop(event)"
                 ondragover="allowDrop(event)"></div>
        </div>
    </div>
</div>

<?php
$this->registerJsVar('staticVariables', \yii\helpers\ArrayHelper::map($staticVariables, 'id', function($m){return $m;}));
$this->registerJsVar('dynamicVariables', \yii\helpers\ArrayHelper::map($dynamicVariables, 'id', function($m){return $m;}));
$this->registerJsVar('urls', [
    'saveUrl' => $saveAction
]);
$js = <<<JS
    
let selected;
let page = document.getElementById('page');
let pageWidth = $('.print-page-generator #page-width');
let pageHeight = $('.print-page-generator #page-height');

$('.print-page-generator .on-change-handle').on('change', () => {
    page.style.width = parseFloat(pageWidth.val()) + 'mm'
    page.style.height = parseFloat(pageHeight.val()) + 'mm'
})
    
    
function drag(ev) {
    console.log();
    selected = ev.target.id;
    if (ev.target.parentElement.id !== 'safe-drop-zone') {
        let crt = ev.target.cloneNode(true);
        crt.style.display = "none";
        ev.dataTransfer.setDragImage(crt, 0, 0);
    }
}
function allowDrop(ev) {
    ev.preventDefault();
    page = document.getElementById(page.id);
    let rect = page.getBoundingClientRect();
    selected = document.getElementById(selected);
    selected.style.top = (ev.y - rect.y) + 'px';
    selected.style.left = (ev.x - rect.x) + 'px';
    selected = selected.id;
}
function drop(ev) {
    ev.preventDefault();
    page = document.getElementById(page.id);
    let rect = page.getBoundingClientRect();
    selected = document.getElementById(selected);
    switch (selected.getAttribute('type')) {
      case 'static':
        selected.id = selected.id ?? selected.getAttribute('id')
        staticVariables[selected.id].x = ev.x - rect.x;
        staticVariables[selected.id].y = ev.y - rect.y
        staticVariables[selected.id]['id'] = selected.id;
        break;
      case 'dynamic':
        dynamicVariables[selected.id].x = ev.x - rect.x;
        dynamicVariables[selected.id].y = ev.y - rect.y
        break;
    }
    selected.style.top = (ev.y - rect.y) + 'px';
    selected.style.left = (ev.x - rect.x) + 'px';
    $(ev.target).append(selected);
}
function saveTemplate(){
    $.ajax({
        url:urls.saveUrl,
        data: {
            w: document.getElementById("page-width").value,
            h: document.getElementById("page-height").value,
            staticVariables: staticVariables,
            dynamicVariables: dynamicVariables,
        },
        type: "POST",
        success: (response) => {
            if (response.status) {
                alert(response.message)
            } else {
                alert(response.message)
            }
            window.location.assign('print?id=' + response.data.id);
        },
        error: (xhr) => {
            console.log(xhr);
        }
    })
}
JS;

$this->registerJs($js, \yii\web\View::POS_END);
$css = <<<CSS
    .print-page-generator input[type="number"] {
        text-align: right;
    }
    .print-page-generator .input-group-addon {
        padding: 4px 10px;
        background: #f2f2f2;
    }
    .print-page-generator .backdrop {
        position: relative;
        display: flex;
        width: 100%;
        height: 80vh;
        background: #f2f2f2;
        flex-direction: row;
        align-content: space-around;
        justify-content: center;
        align-items: center;
        flex-wrap: nowrap;
    }
    .print-page-generator .page {
        position: relative;
        width: {$w}mm;
        height: {$h}mm;
        background: white;
        border: 1px solid black;
        border-radius: 5px;
    }
    .print-page-generator .safe-drop-zone {
        position: relative;
        height: calc(100vh - 130px);
        display: block;
        overflow-y: scroll;
    }
    .print-page-generator .page .drop-item {
        position: absolute!important;
        margin: 0;
    }
CSS;

$this->registerCss($css);