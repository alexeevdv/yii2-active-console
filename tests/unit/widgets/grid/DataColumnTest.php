<?php

namespace tests\unit\widgets\grid;

use alexeevdv\console\widgets\grid\DataColumn;
use alexeevdv\console\widgets\grid\GridView;
use Codeception\Stub;
use tests\models\Product;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\i18n\Formatter;

/**
 * Class DataColumnTest
 * @package tests\unit\widgets\grid
 */
class DataColumnTest extends \Codeception\Test\Unit
{
    /**
     * @var \tests\UnitTester
     */
    public $tester;

    /**
     * @test
     */
    public function testRenderHeaderCellContent()
    {
        $this->tester->wantTo('See same header as specified in attribute');
        $column = new DataColumn;
        $column->header = 'Header';
        $this->tester->assertEquals('Header', $column->renderHeaderCell());

        $this->tester->wantTo('See same label as specified in attribute');
        $column = new DataColumn;
        $column->grid = Stub::make(GridView::class);
        $column->label = 'Label';
        $this->tester->assertEquals('Label', $column->renderHeaderCell());

        $this->tester->wantTo('See model attribute label for active data provider');
        $column = new DataColumn;
        $column->attribute = 'price';
        $column->grid = Stub::make(GridView::class, [
            'dataProvider' => new ActiveDataProvider([
                'query' => Product::find(),
            ]),
        ]);

        $this->tester->assertEquals('Price', $column->renderHeaderCell());
        $this->tester->wantTo('See model attribute label for array data provider');
        $column = new DataColumn;
        $column->attribute = 'price';
        $column->grid = Stub::make(GridView::class, [
            'dataProvider' => new ArrayDataProvider([
                'modelClass' => Product::class,
            ]),
        ]);
        $this->tester->assertEquals('Price', $column->renderHeaderCell());

        $this->tester->wantTo('See model attribute label for custom data provider');
        $column = new DataColumn;
        $column->attribute = 'price';
        $column->grid = Stub::make(GridView::class, [
            'dataProvider' => Stub::make(ArrayDataProvider::class, [
                'getModels' => function () {
                    return [new Product];
                },
            ]),
        ]);
        $this->tester->assertEquals('Price', $column->renderHeaderCell());
    }

    /**
     * @test
     */
    public function testRenderDataCell()
    {
        $this->tester->wantTo('See specified content');
        $column = new DataColumn;
        $column->content = function ($model, $key, $index) {
            return $model;
        };
        $this->tester->assertEquals('model', $column->renderDataCell('model', 1, 0));

        $this->tester->wantTo('See default value for unspecified content');
        $column = new DataColumn;
        $column->grid = Stub::make(GridView::class, [
            'formatter' => new Formatter([
                'timeZone' => 'Europe/Moscow',
                'locale' => 'en-US',
            ]),
        ]);
        $this->tester->assertEquals('<span class="not-set">(not set)</span>', $column->renderDataCell('model', 1, 0));

        $this->tester->wantTo('See specified value');
        $column = new DataColumn;
        $column->value = 'price';
        $column->grid = Stub::make(GridView::class, [
            'formatter' => new Formatter([
                'timeZone' => 'Europe/Moscow',
                'locale' => 'en-US',
            ]),
        ]);
        $this->tester->assertEquals(300, $column->renderDataCell(['price' => 300], 1, 0));

        $this->tester->wantTo('See value specified as callback');
        $column = new DataColumn;
        $column->value = function ($model) {
            return 400;
        };
        $column->grid = Stub::make(GridView::class, [
            'formatter' => new Formatter([
                'timeZone' => 'Europe/Moscow',
                'locale' => 'en-US',
            ]),
        ]);
        $this->tester->assertEquals(400, $column->renderDataCell('model', 1, 0));

        $this->tester->wantTo('See value for specified attribute');
        $column = new DataColumn;
        $column->attribute = 'price';
        $column->grid = Stub::make(GridView::class, [
            'formatter' => new Formatter([
                'timeZone' => 'Europe/Moscow',
                'locale' => 'en-US',
            ]),
        ]);
        $this->tester->assertEquals(500, $column->renderDataCell(['price' => 500], 1, 0));

    }
}
