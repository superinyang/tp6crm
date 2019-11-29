<?php


namespace app\middleware;


use think\Response;
use app\common\controller\Common;

class AllowOriginMiddleware extends Common
{
    public function handle($request, \Closure $next)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Headers: Authori-zation,Authorization, Content-Type, If-Match, If-Modified-Since, If-None-Match, If-Unmodified-Since, X-Requested-With');
        header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE');
        header('Access-Control-Max-Age: 1728000');

        if ($request->isOptions()) {
            $response = new Response('ok');
        } else {
            $response = $next($request);
        }

        return $response;
    }
}