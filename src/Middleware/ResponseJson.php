<?php


namespace Nichozuo\LaravelUtils\Middleware;


use Closure;
use Illuminate\Http\JsonResponse;
use Nichozuo\LaravelUtils\Traits\ExceptionsRenderTrait;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResponseJson
{
    use ExceptionsRenderTrait;

    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $base = [
            'code' => 0,
            'message' => 'ok',
        ];

        if ($response instanceof JsonResponse) {

            $data = $response->getData();
            $type = gettype($data);

            if ($type == 'object') {
                // 如果是version
                if(strpos($request->getPathInfo(), '/auth/version'))
                    return $response->setData($data);

                // 如果是exception
                if (property_exists($data, 'code') && property_exists($data, 'message') && $data->code !== 0)
                    return $response->setData($data);

                // 处理一些统计数据
                if (property_exists($data, 'data') && property_exists($data, 'statistics'))
                    $base['statistics'] = $data->statistics;

                // 处理标准分页
                if (property_exists($data, 'data') && property_exists($data, 'current_page')) {
                    $base['data'] = $data->data;
                    $base['meta'] = [
                        'total' => $data->total ?? 0,
                        'per_page' => (int)$data->per_page ?? 0,
                        'current_page' => $data->current_page ?? 0,
                        'last_page' => $data->last_page ?? 0
                    ];
                } else {
                    $base['data'] = $data;
                }
            } else {
                if ($data != '' && $data != null) {
                    $base['data'] = $data;
                }
            }
            return $response->setData($base);
        } elseif ($response instanceof BinaryFileResponse) {
            return $response;
        } elseif ($response->getContent() == "") {
            return response()->json($base, 200);
        } else {
            return $response;
        }
    }
}
