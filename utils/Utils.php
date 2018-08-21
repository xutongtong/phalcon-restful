<?php
/**
 * 常用的函数类库
 * ============================================================================
 * * 版权所有 2005-2015 深圳市凯迪克文化传播有限公司，并保留所有权利。
 * * 网站地址: www.baobaobooks.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: xutt $
 * $Id: Utils.php 17217 2015-08-11 19:31:08Z xutt $
 */
class Utils
{
    /**
     * 检查输入是否完整及合法
     *
     * @param array $input 待检查的输入
     * @param array $needed 输入中需要包含的字段数组
     *
     * @return void
     */
    public static function checkInput(&$input, $needed)
    {
        if (empty($needed) || !is_array($needed)) {
            return;
        }
        if (empty($input) || !is_array($input)) {
            self::response('ValidationError', '参数不得为空：' . implode(',', $needed))->send();
            exit();
        }
        foreach ($needed as $oneNeed) {
            if (!array_key_exists($oneNeed, $input) || '' === $input[$oneNeed]) {
                self::response('ValidationError', '参数不得为空：' . $oneNeed)->send();
                exit();
            }
            if (is_string($input[$oneNeed])) {
                //去除前后空格
                $input[$oneNeed] = trim($input[$oneNeed]);
            }
        }
    }

    /**
     * 检查签名  校验不通过会直接退出程序
     *
     * @param string $code 错误码
     * @param string $message 错误说明
     * @param string $body 返回体 主要用于返回相应的数据给前端
     *
     * @return Phalcon\Http\Response
     */
    public static function response($code, $message = '', $body = '')
    {
        $di = \Phalcon\Di::getDefault();
        $codeArr = [
            'InvalidToken' => ['未登录或授权过期，请登录', 401],
            'ValidationError' => ['输入字段验证出错', 422],
            'NotFound' => ['请求的资源不存在', 404],
            'InternalError' => ['服务异常，请稍后再试', 500],
            'DatabaseError' => ['数据库服务异常，请稍后再试', 500],
        ];

        $response = $di->get('response');
        $response->setContentType('application/json');

        $statusCode = 0;
        if (array_key_exists($code, $codeArr)) {
            $statusCode = $codeArr[$code][1];
            $message = empty($message) ? $codeArr[$code][0] : $message;
        } else {
            $statusCode = 500;
            $code = 'UnknowError';
            $message = '未知错误，请稍后再试';
        }
        /*记录日志*/
        if ((int) $statusCode >= 500) {
            $method = $_SERVER['REQUEST_METHOD'];
            $url = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
            if ($code == 'SMSServiceProviderError') {
                self::appLog("PHP Error: status:$statusCode; code:$code; message:$message; method:$method; url:$url");
            } else {
                error_log("PHP Error: status:$statusCode; code:$code; message:$message; method:$method; url:$url");
            }
            if (!APP_DEBUG) {
                $message = '网络异常，请稍后再试';
            }
        }
        $response->setStatusCode($statusCode);
        if (empty($body)) {
            $res = ['code' => $code, 'message' => $message];
        } else {
            $res = ['code' => $code, 'message' => $message, 'body' => $body];
        }
        $response->setJsonContent($res);
        return $response;
    }

    /**
     * 返回JSON数据，状态码是200
     * @param $json
     * @return mixed
     */
    public static function responsJSONContent($json)
    {
        $di = \Phalcon\Di::getDefault();
        $response = $di->get('response');
        $response->setContentType('application/json');

        $response->setStatusCode(200);
        $response->setJsonContent($json);

        return $response;
    }

    /**
     * 检查key是否正确
     * @param string $key
     * @param string $signSecret
     * @return string
     */
    public static function genKey($key, $signSecret)
    {
        return md5($signSecret . $key . $signSecret);
    }

    /**
     * 将请求参数字符串替换成数组形式返回
     *
     * @param string $data 请求参数字符串
     *
     * @return array    以请求参数键值对的形式返回
     */
    public static function parseQueryString($data)
    {
        $data = preg_replace_callback('/(?:^|(?<=&))[^=[]+/', function ($match) {
            return bin2hex(urldecode($match[0]));
        }, $data);
        parse_str($data, $values);
        return array_combine(array_map('hex2bin', array_keys($values)), $values);
    }

    /**
     * 格式化金钱
     * @param float $price
     * @param string $format
     * @param string $type
     * @return string
     */
    public static function formatPrice($price, $format = "2", $type = 'zh')
    {
        $moneyCode = "￥";

        switch ($type) {
            case "zh":
                $moneyCode = "￥";
                break;
            case "us":
                $moneyCode = "$";
                break;
            case "en":
                $moneyCode = "￡";
                break;
            default:
                $moneyCode = "￥";
                break;
        }

        return $moneyCode . sprintf("%.{$format}f", (float) $price);
    }

    /**
     * 输出日志到PHP日志文件
     * @param mixed $message 错误说明 数组或字符串
     * @return bool
     */
    public static function errorLog($message = '', $topic = '')
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
        $url = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : '';
        if (is_array($message)) {
            $message['method'] = $method;
            $message['url'] = $url;
        } else {
            $message = [
                'error_info' => $message,
                'method' => $method,
                'url' => $url,
            ];
        }
        $di = \Phalcon\DI::getDefault();
        $client = $di->get('applog');
        $logItem = new Aliyun_Log_Models_LogItem();
        $logItem->setTime(time());
        $logItem->setContents($message);
        $logitems = [$logItem];
        $project = $di->get('config')->oss->logProject; // 创建的项目名称
        $logstore = 'server-errors-log'; // 创建的日志库名称
        $client->putLogs(new Aliyun_Log_Models_PutLogsRequest($project, $logstore, $topic, null, $logitems));
        return true;
    }

    /**
     * 记录错误日志
     * @param string $topic 日志类型
     * @param mixed $message 错误说明 数组或字符串
     * @param string $logstore 创建的日志库名称
     *
     * @return bool
     */
    public static function appLog($message = '', $topic = '', $logstore = 'server-app-log')
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : '';
        $url = isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'] : '';
        if (is_array($message)) {
            $message['method'] = $method;
            $message['url'] = $url;
        } else {
            $message = [
                'app_info' => $message,
                'method' => $method,
                'url' => $url,
            ];
        }
        $di = \Phalcon\DI::getDefault();
        $client = $di->get('applog');
        $logItem = new Aliyun_Log_Models_LogItem();
        $logItem->setTime(time());
        $logItem->setContents($message);
        $logitems = [$logItem];
        $project = $di->get('config')->oss->logProject; // 创建的项目名称
        $client->putLogs(new Aliyun_Log_Models_PutLogsRequest($project, $logstore, $topic, null, $logitems));
        return true;
    }

    /**
     * 替换html内容中的img标签实现懒加载
     *
     * @param   string $htmlConent html内容
     * @return    string    添加了懒加载后的html内容
     */
    public static function addImgLazyload(&$htmlConent)
    {
        $di = \Phalcon\DI::getDefault();
        $config = $di->get('config');
        $placeholder = $config->lazyloadImg->rectangle;
        //正则表达式,非贪婪模式
        $patterns = '/<img(.*)src\s*="(.*)"(.*)\/>/U';
        return preg_replace_callback($patterns, function ($matches) use ($placeholder) {
            return '<img class="lazy" data-original="' . $matches[2] . '" src="' . $placeholder . '"' . $matches[1] . $matches[3] . '/>';
        }, $htmlConent);
    }

    //截取描述
    public static function formatDesc($desc)
    {
        $desc = str_replace(["\r\n", "\r", "\n"], '', $desc);
        $desc = str_replace('\s+', '', $desc);
        $desc = mb_substr($desc, 0, 80);

        return $desc;
    }

    //截取商品描述
    public static function formatGoodsDesc($desc, $goodsDesc = '')
    {
        if (empty($desc)) {
            $goodsDesc = preg_replace('/<script.*?>.*?<\/script.*?>/si', '', $goodsDesc); // 过滤script标签
            $desc = strip_tags($goodsDesc);
        }
        $desc = str_replace(["\r\n", "\r", "\n"], '', $desc);
        $desc = str_replace('\s+', '', $desc);
        $desc = mb_substr($desc, 0, 80);
        return $desc;
    }
}
