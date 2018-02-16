<?php

namespace alexeevdv\console\widgets\grid;

use Closure;
use yii\base\BaseObject;
use yii\helpers\Console;

/**
 * Class Column
 * @package alexeevdv\console\widgets\grid
 */
class Column extends BaseObject
{
    /**
     * @var GridView the grid view object that owns this column.
     */
    public $grid;

    /**
     * @var string the header cell content.
     */
    public $header;

    /**
     * @var string the footer cell content.
     */
    public $footer;

    /**
     * @var callable This is a callable that will be used to generate the content of each cell.
     * The signature of the function should be the following: `function ($model, $key, $index, $column)`.
     * Where `$model`, `$key`, and `$index` refer to the model, key and index of the row currently being rendered
     * and `$column` is a reference to the [[Column]] object.
     */
    public $content;

    /**
     * @var bool whether this column is visible. Defaults to true.
     */
    public $visible = true;

    /**
     * @var array the ANSI format values for the header cell.
     * @see \yii\helpers\Console::ansiFormat() for details on how header are being rendered.
     */
    public $headerFormat = [];

    /**
     * @var array|\Closure the ANSI format for the data cell. This can either be an array or an
     * anonymous function ([[Closure]]) that returns such an array.
     * The signature of the function should be the following: `function ($model, $key, $index, $column)`.
     * Where `$model`, `$key`, and `$index` refer to the model, key and index of the row currently being rendered
     * and `$column` is a reference to the [[Column]] object.
     * A function may be used to assign different ANSI formats to different rows based on the data in that row.
     *
     * @see \yii\helpers\Console::ansiFormat() for details on how content are being rendered.
     */
    public $contentFormat = [];

    /**
     * @var array the ANSI format for the footer cell.
     * @see \yii\helpers\Console::ansiFormat() for details on how footer are being rendered.
     */
    public $footerFormat = [];

    /**
     * Renders the header cell.
     */
    public function renderHeaderCell()
    {
        $content = $this->renderHeaderCellContent();
        if ($this->headerFormat) {
            $content = Console::ansiFormat($content, $this->headerFormat);
        }
        return $content;
    }

    /**
     * Renders the footer cell.
     */
    public function renderFooterCell()
    {
        $content = $this->renderFooterCellContent();
        if ($this->footerFormat) {
            $content = Console::ansiFormat($content, $this->footerFormat);
        }
        return $content;
    }

    /**
     * Renders a data cell.
     * @param mixed $model the data model being rendered
     * @param mixed $key the key associated with the data model
     * @param int $index the zero-based index of the data item among the item array returned by [[GridView::dataProvider]].
     * @return string the rendering result
     */
    public function renderDataCell($model, $key, $index)
    {
        if ($this->contentFormat instanceof Closure) {
            $format = call_user_func($this->contentFormat, $model, $key, $index, $this);
        } else {
            $format = $this->contentFormat;
        }

        $content = $this->renderDataCellContent($model, $key, $index);
        if ($format) {
            $content = Console::ansiFormat($content, $format);
        }
        return $content;
    }

    /**
     * Renders the header cell content.
     * The default implementation simply renders [[header]].
     * This method may be overridden to customize the rendering of the header cell.
     * @return string the rendering result
     */
    protected function renderHeaderCellContent()
    {
        return trim($this->header) !== '' ? $this->header : $this->getHeaderCellLabel();
    }

    /**
     * Returns header cell label.
     * This method may be overridden to customize the label of the header cell.
     * @return string label
     * @since 2.0.8
     */
    protected function getHeaderCellLabel()
    {
        return $this->grid->emptyCell;
    }

    /**
     * Renders the footer cell content.
     * The default implementation simply renders [[footer]].
     * This method may be overridden to customize the rendering of the footer cell.
     * @return string the rendering result
     */
    protected function renderFooterCellContent()
    {
        return trim($this->footer) !== '' ? $this->footer : $this->grid->emptyCell;
    }

    /**
     * Renders the data cell content.
     * @param mixed $model the data model
     * @param mixed $key the key associated with the data model
     * @param int $index the zero-based index of the data model among the models array returned by [[GridView::dataProvider]].
     * @return string the rendering result
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if ($this->content !== null) {
            return call_user_func($this->content, $model, $key, $index, $this);
        }

        return $this->grid->emptyCell;
    }
}
