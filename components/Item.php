<?php

namespace mrmuminov\printpagegenerator\components;

use yii\base\Component;
use yii\helpers\Html;

/**
 * @package @mrmuminov\printpagegenerator
 * @author Bahriddin Mo'minov
 * @email darkshadeuz@gmail.com
 * @site https://bahriddin.uz
 */
class Item extends Component
{
    const ALIGN_START = 'start';
    const ALIGN_LEFT = 'left';
    const ALIGN_CENTER = 'center';
    const ALIGN_JUSTIFY = 'justify';
    const ALIGN_RIGHT = 'right';
    const ALIGN_END = 'end';

    const TYPE_STATIC = 'static';
    const TYPE_DYNAMIC = 'dynamic';

    public $id = null;

    public $label;
    public $type;
    public $align = self::ALIGN_LEFT;
    public $fontSize = '14px';
    public $bold = false;
    public $italic = false;
    public $underline = false;
    public $width = '130px';
    public $height = '20px';
    public $x = '0px';
    public $y = '0px';

    /**
     * @return string
     */
    public function render($data = null)
    {
        if (!$this->type) {
            $this->type = $this->id ? self::TYPE_DYNAMIC : self::TYPE_STATIC;
        }

        $this->id = $this->id ?? $this->generateUniqueId();
        $label = $this->label;
        if ($this->type == self::TYPE_DYNAMIC && !is_null($data)) {
            $label = $data[$this->id];
        }
        return Html::tag('div', $label, [
            'id' => $this->id,
            'name' => $this->id,
            'class' => 'drop-item',
            'type' => $this->type,
            'style' => [
                'display' => 'inline-block',
                'text-align' => $this->align,
                'font-size' => $this->fontSize,
                'font-weight' => $this->bold ? 'bold' : 'normal',
                'font-style' => $this->italic ? 'italic' : 'normal',
                'text-decoration' => $this->underline ? 'underline' : 'none',
                'width' => is_numeric($this->width) ? $this->width . 'mm' : $this->width,
                'height' => is_numeric($this->height) ? $this->height . 'mm' : $this->height,
                'border' => $data ? 'none' : '1px solid #00000033',
                'border-radius' => '1px',
                'top' => $this->y,
                'left' => $this->x,
                'white-space' => 'nowrap',
                'background' => ($this->type==self::TYPE_DYNAMIC && is_null($data) ? "#4caf5022" : 'transparent'),
            ],
            'draggable' => $data ? 'false' : 'true',
            'ondragstart' => "drag(event)",
        ]);
    }

    /**
     * @return null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param null $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param mixed $label
     */
    public function setLabel($label): void
    {
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getAlign(): string
    {
        return $this->align;
    }

    /**
     * @param string $align
     */
    public function setAlign(string $align): void
    {
        $this->align = $align;
    }

    /**
     * @return string
     */
    public function getFontSize(): string
    {
        return $this->fontSize;
    }

    /**
     * @param string $fontSize
     */
    public function setFontSize(string $fontSize): void
    {
        $this->fontSize = $fontSize;
    }

    /**
     * @return bool
     */
    public function isBold(): bool
    {
        return $this->bold;
    }

    /**
     * @param bool $bold
     */
    public function setBold(bool $bold): void
    {
        $this->bold = $bold;
    }

    /**
     * @return bool
     */
    public function isItalic(): bool
    {
        return $this->italic;
    }

    /**
     * @param bool $italic
     */
    public function setItalic(bool $italic): void
    {
        $this->italic = $italic;
    }

    /**
     * @return bool
     */
    public function isUnderline(): bool
    {
        return $this->underline;
    }

    /**
     * @param bool $underline
     */
    public function setUnderline(bool $underline): void
    {
        $this->underline = $underline;
    }

    /**
     * @return string
     */
    public function getWidth(): string
    {
        return $this->width;
    }

    /**
     * @param string $width
     */
    public function setWidth(string $width): void
    {
        $this->width = $width;
    }

    /**
     * @return string
     */
    public function getHeight(): string
    {
        return $this->height;
    }

    /**
     * @param string $height
     */
    public function setHeight(string $height): void
    {
        $this->height = $height;
    }

    /**
     * @return string
     */
    private function generateUniqueId(){
        return uniqid('static_');
    }
}