<?php


namespace Nichozuo\LaravelUtils\Traits;


use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Nichozuo\LaravelUtils\Exceptions\Err;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

trait ExceptionsRenderTrait
{
    /**
     * json打印错误信息
     *
     * @param Exception $e
     * @param Request $request
     * @return array
     */
    protected function renderExceptionsJson(Exception $e, Request $request): array
    {
        $class = get_class($e);
        $isDebug = config('app.debug');
        $debugInfo = [];
        $code = 0;
        $status = 200;
        $message = '';
        $description = '';

        if ($isDebug) {
            $debugInfo = [
                'exception' => [
                    'class' => $class,
                    'trace' => $this->getTrace($e)
                ],
                'client' => $request->getClientIps(),
                'request' => [
                    'method' => $request->getMethod(),
                    'uri' => $request->getUri(),
                    'params' => $request->all(),
                    'header' => $request->header()
                ],
            ];
        }

        switch ($class) {
            case Err::class:
                $code = $e->getCode();
                $message = $e->getMessage();
                $description = $e->getDescription();
                break;
            case AuthenticationException::class:
                $arr = Err::AuthUserNotLogin;
                $code = $arr[0];
                $message = $arr[1];
                $description = $arr[2];
                break;
            case ValidationException::class:
                $code = 9;
                $message = "数据验证失败";
                $description = "【{$this->getValidationErrors($e->errors())}】字段验证失败";
                break;
            case NotFoundHttpException::class:
                $code = 9;
                $message = "请求的资源未找到";
                $description = $e->getMessage();
                break;
            case MethodNotAllowedHttpException::class:
                $code = 9;
                $message = "请求方式不正确";
                $description = $e->getMessage();
                break;
            default:
                $code = 9;
                $message = '系统错误';
                $description = $e->getMessage();
                break;
        }

        $return = [
            'code' => $code,
            'status' => $status,
            'message' => $message,
            'description' => $description,
            'debug' => $debugInfo
        ];
        Log::error($message, $return);
        return $return;
    }

    /**
     * 获取trace数组
     *
     * @param Exception $e
     * @return array
     */
    private function getTrace(Exception $e): array
    {
        $arr = $e->getTrace();
        $file = array_column($arr, 'file');
        $line = array_column($arr, 'line');
        $trace = [];
        for ($i = 0; $i < count($file); $i++) {
            $trace[] = $file[$i] . '(' . $line[$i] . ')';
        }
        return $trace;
    }

    /**
     * 获取validate错误描述
     *
     * @param array $errors
     * @return string
     */
    private function getValidationErrors(array $errors): string
    {
        $err = [];
        foreach ($errors as $key => $value) {
            foreach ($value as $item) {
                $err[] = $key;
            }
        }
        return implode(',', $err);
    }
}
