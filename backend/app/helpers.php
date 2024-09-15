<?php


if (!function_exists('json_true')) {
    function json_true($data = [], $meta = [], $status = 200)
    {
        if (count($meta) > 0) {
            return response()->json([
                'status' => $status,
                'data' => $data,
                'meta' => $meta,
            ], $status);
        } else {
            return response()->json([
                'status' => true,
                'data' => $data,
            ], $status);
        }
    }
}

if (!function_exists('json_false')) {
    function json_false($data = [], $status = 200)
    {
        return response()->json([
            'status' => false,
            'data' => $data,
        ], $status);
    }
}

if (!function_exists('resource_index')) {
    function resource_index($resource)
    {
        $array['meta'] = $resource->resource->toArray();
        $array['data'] = $resource->collection->toArray();
        unset($array['meta']['data']);

        return $array;
    }
}


