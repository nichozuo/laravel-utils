<?php


namespace Nichozuo\LaravelUtils\Exceptions;


class Err extends BaseException
{
    const DBOperateFailed = [20000, '数据操作失败'];
    const DBRecordExist = [20001, '数据操作失败'];

    const AuthUserNotLogin = [10000, '认证失败', '用户未登陆'];
    const AuthUserNotFound = [10001, '认证失败', '无此用户'];
    const AuthWrongPassword = [10002, '认证失败', '密码错误'];
    const AuthPasswordNotSame = [10003, '注册失败', '两次输入密码不一致'];

    const UploadFileNotFound = [10010, '上传文件失败', '未接收到上传的文件'];

    const SmsSendFailed = [20000, '发送失败', '短信发送失败，请联系管理员'];
    const SmsValidateFailed = [20001, '验证失败', '验证码不正确，或者已经过期'];

    const ApiReturnError = [40000, 'api调用失败'];
    const ValidateError = [50001, '数据校验失败'];
    const HaventTask = [50002, '没有任务了'];
    const GetProxyError = [50003, '获取代理出错'];
    const NotFoundPlayer = [50004, '没有足够用户，该创建的创建了'];
    const FileTypeNotAllow = [50005, '文件类型判断失败'];
    const NotEnoughMoney = [50006, '取回金额大于用户游戏金额'];
    const StepCanNotZero = [50000, '步骤金额不能为0'];

    const NoCompanyHasThisAgent = [60000, '没有机构绑定过您的手机号'];

    /**
     * @param $arr
     * @param string $description
     * @return static
     */
    public static function New($arr, $description = ''): Err
    {
        if ($description == '' && count($arr) == 3)
            $description = $arr[2];

        return new static((int)$arr[0], $arr[1], $description);
    }

    /**
     * @param $description
     * @return static
     */
    public static function NewText($description): Err
    {
        return new static(999, '发生错误', $description);
    }
}
