<?php

return [
    'page' => env('PAGE', 1),
    'limit' => env('PAGE_LIMIT', 10),
    'sort_column' => env('SORT_COLUMN', 'created_at'),
    'sort_direction' => env('SORT_DIRECTION', 'asc'),
];
