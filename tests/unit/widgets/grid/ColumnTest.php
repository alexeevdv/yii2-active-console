<?php

namespace tests\unit\widgets\grid;
use alexeevdv\console\widgets\grid\Column;
use alexeevdv\console\widgets\grid\GridView;
use Codeception\Stub;
use yii\helpers\Console;

/**
 * Class ColumnTest
 * @package tests\unit\widgets\grid
 */
class ColumnTest extends \Codeception\Test\Unit
{
    /**
     * @var \tests\UnitTester
     */
    public $tester;

    /**
     * @test
     */
    public function testRenderHeaderCell()
    {
        $this->tester->wantTo('See same header as specified in attribute');
        $column = new Column;
        $column->header = 'Plain header';
        $this->tester->assertEquals('Plain header', $column->renderHeaderCell());

        $this->tester->wantTo('See header wrapped by ANSI');
        $column = new Column;
        $column->header = 'Plain header';
        $column->headerFormat = [Console::FG_GREEN];
        $this->tester->assertEquals(
            Console::ansiFormat('Plain header', [Console::FG_GREEN]),
            $column->renderHeaderCell()
        );

        $this->tester->wantTo('See default value for unspecified header');
        $column = new Column;
        $column->grid = Stub::make(GridView::class, ['emptyCell' => '-']);
        $this->tester->assertEquals('-', $column->renderHeaderCell());
    }

    /**
     * @test
     */
    public function testRenderFooterCell()
    {
        $this->tester->wantTo('See same footer as specified in attribute');
        $column = new Column;
        $column->footer = 'Plain footer';
        $this->tester->assertEquals('Plain footer', $column->renderFooterCell());

        $this->tester->wantTo('See footer wrapped by ANSI');
        $column = new Column;
        $column->footer = 'Plain footer';
        $column->footerFormat = [Console::FG_GREEN];
        $this->tester->assertEquals(
            Console::ansiFormat('Plain footer', [Console::FG_GREEN]),
            $column->renderFooterCell()
        );

        $this->tester->wantTo('See default value for unspecified footer');
        $column = new Column;
        $column->grid = Stub::make(GridView::class, ['emptyCell' => '-']);
        $this->tester->assertEquals('-', $column->renderFooterCell());
    }

    /**
     * @test
     */
    public function testRenderDataCell()
    {
        $this->tester->wantTo('See default value for unspecified content');
        $column = new Column;
        $column->grid = Stub::make(GridView::class, ['emptyCell' => '-']);
        $this->tester->assertEquals('-', $column->renderDataCell('model', 1, 0));

        $this->tester->wantTo('See same data as specified in content callback');
        $column = new Column;
        $column->content = function ($model, $key, $index) {
            return $model;
        };
        $this->tester->assertEquals('model', $column->renderDataCell('model', 1, 0));

        $this->tester->wantTo('See same data as specified in content callback but formatted');
        $column = new Column;
        $column->contentFormat = [Console::FG_GREEN];
        $column->content = function ($model, $key, $index) {
            return $model;
        };
        $this->tester->assertEquals(
            Console::ansiFormat('model', [Console::FG_GREEN]),
            $column->renderDataCell('model', 1, 0)
        );

        $this->tester->wantTo('See same data as specified in content callback but formatted with callback');
        $column = new Column;
        $column->contentFormat = function ($model, $key, $index, $column) {
            return [Console::FG_GREEN];
        };
        $column->content = function ($model, $key, $index) {
            return $model;
        };
        $this->tester->assertEquals(
            Console::ansiFormat('model', [Console::FG_GREEN]),
            $column->renderDataCell('model', 1, 0)
        );
    }
}
