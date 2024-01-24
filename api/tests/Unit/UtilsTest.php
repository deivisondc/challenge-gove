<?php

namespace Tests\Unit;

use App\Http\Utils as HttpUtils;
use Tests\TestCase;
use Tests\Utils;

class UtilsTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_should_return_same_amount_if_less_then_five(): void
    {
        $links = [];

        for ($i=1; $i <= 5; $i++) {
            $links[] = [ "label" => $i ];
            $templatePaginate = Utils::mockPaginate([], $links, 1, 1);
            $this->assertCount($i, $templatePaginate['links']);
        }
    }

    public function test_should_return_the_first_three_links_and_the_last_if_selected_until_three(): void
    {
        $links = [[]];
        for ($i=1; $i <= 9; $i++) {
            $links[] = [ "label" => $i ];
        }
        $links[] = [];

        for ($i=1; $i <= 2; $i++) {
            $templatePaginate = Utils::mockPaginate([], $links, $i, 9);
            $resultLinks = HttpUtils::formatPaginationLinks($templatePaginate);

            $firstThreeLinks = 3;
            $emptyLinks = 1;
            $lastLink = 1;

            $totalLinks = $firstThreeLinks + $emptyLinks + $lastLink;
            $this->assertCount($totalLinks, $resultLinks);
        }
    }

    public function test_should_return_the_first_four_links_and_the_last_if_selected_is_three(): void
    {
        $links = [[]];
        for ($i=1; $i <= 9; $i++) {
            $links[] = [ "label" => $i ];
        }
        $links[] = [];

        $templatePaginate = Utils::mockPaginate([], $links, 3, 9);
        $resultLinks = HttpUtils::formatPaginationLinks($templatePaginate);

        $firstFourLinks = 4;
        $emptyLinks = 1;
        $lastLink = 1;

        $totalLinks = $firstFourLinks + $emptyLinks + $lastLink;
        $this->assertCount($totalLinks, $resultLinks);
    }

    public function test_should_return_the_last_three_links_and_the_last_if_selected_last_three(): void
    {
        $links = [[]];
        for ($i=1; $i <= 9; $i++) {
            $links[] = [ "label" => $i ];
        }
        $links[] = [];

        for ($i=8; $i <= 9; $i++) {
            $templatePaginate = Utils::mockPaginate([], $links, $i, 9);
            $resultLinks = HttpUtils::formatPaginationLinks($templatePaginate);

            $lastThreeLinks = 3;
            $emptyLinks = 1;
            $firstLink = 1;

            $totalLinks = $lastThreeLinks + $emptyLinks + $firstLink;

            $this->assertCount($totalLinks, $resultLinks);
        }
    }

    public function test_should_return_the_last_four_links_and_the_last_if_selected_is_three_before_last(): void
    {
        $links = [[]];
        for ($i=1; $i <= 9; $i++) {
            $links[] = [ "label" => $i ];
        }
        $links[] = [];

        $templatePaginate = Utils::mockPaginate([], $links, 7, 9);
        $resultLinks = HttpUtils::formatPaginationLinks($templatePaginate);


        $lastFourLinks = 4;
        $emptyLinks = 1;
        $firstLink = 1;

        $totalLinks = $lastFourLinks + $emptyLinks + $firstLink;

        $this->assertCount($totalLinks, $resultLinks);
    }

    public function test_should_return_first_and_last_and_one_neighbour_from_selected(): void
    {
        $links = [[]];
        for ($i=1; $i <= 9; $i++) {
            $links[] = [ "label" => $i ];
        }
        $links[] = [];

        $templatePaginate = Utils::mockPaginate([], $links, 5, 9);
        $resultLinks = HttpUtils::formatPaginationLinks($templatePaginate);


        $current = 1;
        $neighbours = 2;
        $emptyLinks = 2;
        $firstLink = 1;
        $lastLink = 1;

        $totalLinks = $current + $neighbours + $emptyLinks + $firstLink + $lastLink;

        $this->assertCount($totalLinks, $resultLinks);
    }
}
