<?php

/*
 * (c) Mantas Varatiejus <var.mantas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MVar\FilteredListBundle\Tests\Filter\Condition;

use MVar\FilteredListBundle\Filter\Condition\MatchFilter;
use Symfony\Component\HttpFoundation\Request;

class MatchFilterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Test for initializeData().
     */
    public function testInitializeData()
    {
        $config = [
            'request_parameter' => 'foo',
        ];

        $filter = new MatchFilter('match', $config);
        $filterData = $filter->initializeData(new Request(['foo' => 'bar']));

        $this->assertInstanceOf('\MVar\FilteredListBundle\Filter\Data\FilterData', $filterData);
        $this->assertEquals('bar', $filterData->getValue());
        $this->assertTrue($filterData->isActive());
    }

    /**
     * Test for getWhereSnippet().
     */
    public function testGetWhereSnippet()
    {
        $config = [
            'request_parameter' => 'foo',
            'field' => 'a.baz',
        ];

        $filter = new MatchFilter('match', $config);
        $filterData = $filter->initializeData(new Request(['foo' => 'bar']));
        $snippet = $filter->getWhereSnippet($filterData);

        $this->assertEquals('a.baz = :A_Baz', $snippet['snippet']);
        $this->assertArrayHasKey('A_Baz', $snippet['parameters']);
        $this->assertEquals('bar', $snippet['parameters']['A_Baz']);
    }
}
