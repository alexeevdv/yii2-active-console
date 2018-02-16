<?php

namespace tests\unit\widgets\grid;
use alexeevdv\console\widgets\grid\Column;
use alexeevdv\console\widgets\grid\GridView;
use Codeception\Stub;
use tests\UnitTester;
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
}
