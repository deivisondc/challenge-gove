<?php

namespace Tests;

class Utils {

    public static function mockPaginate($data, $links, $currentPage, $lastPage)
    {
        $template = [
            "current_page" => $currentPage ?? 1,
            "data" => $data ?? [],
            "links" => $links,
            "last_page" => $lastPage,
        ];

        return $template;
    }

}
