<?php

function pagination($resource, $data)
{
    return [
        'current_page' => $data->currentPage(),
        'last_page' => $data->lastPage(),
        'per_page' => $data->perPage(),
        'total' => $data->total(),
        'data' => $resource::collection($data),
        'next_page_url' => $data->nextPageUrl(),
        'prev_page_url' => $data->previousPageUrl(),
        'first_page_url' => $data->url(1),
        'last_page_url' => $data->url($data->lastPage()),
    ];
}
