<?php

namespace App\Http;

class Utils
{
    public static function formatPaginationLinks($collection)
    {
        $links = $collection['links'];
        $lastPage = $collection['last_page'];
        $currentPage = $collection['current_page'];

        $withoutPreviousAndNext = array_slice($links, 1, -1);
        $emptyLink = [
            "url" => null,
            "label" => "...",
            "active" => false
        ];

        $links = [];

        if ($lastPage <= 5)  {
            return $withoutPreviousAndNext;
        } else {
            if ($currentPage <= 3) {
                for ($i=1; $i <= 3; $i++) {
                    $links[] = [
                        "label" => $i,
                        "active"=> $currentPage == $i
                    ];
                }

                if ($currentPage == 3) {
                    $links[] = [
                        "label" => 4,
                        "active"=> $currentPage == 4
                    ];
                }

                $links[] = $emptyLink;
                $links[] = [
                    "label" => $lastPage,
                    "active"=> $currentPage == $lastPage
                ];
            } else if ($currentPage >= ($lastPage - 2)) {
                $links[] = [
                    "label" => 1,
                    "active"=> $currentPage == 1
                ];
                $links[] = $emptyLink;

                if ($currentPage == ($lastPage - 2)) {
                    $links[] = [
                        "label" => ($lastPage - 3),
                        "active"=> $currentPage == ($lastPage - 3)
                    ];
                }

                for ($i=($lastPage - 2); $i <= $lastPage; $i++) {
                    $links[] = [
                        "label" => $i,
                        "active"=> $currentPage == $i
                    ];
                }
            } else {
                $links[] = [
                    "label" => 1,
                    "active"=> $currentPage == 1
                ];
                $links[] = $emptyLink;

                for ($i=($currentPage - 1); $i <= $currentPage + 1; $i++) {
                    $links[] = [
                        "label" => $i,
                        "active"=> $currentPage == $i
                    ];
                }

                $links[] = $emptyLink;
                $links[] = [
                    "label" => $lastPage,
                    "active"=> $currentPage == $lastPage
                ];
            }
        }

        return $links;
    }
}
