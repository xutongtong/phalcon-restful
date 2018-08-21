<?php
/**
 * 孩宝小镇电商系统商品相关相关接口
 * ============================================================================
 * * 版权所有 2005-2016 深圳市凯迪克文化传播有限公司，并保留所有权利。
 * * 网站地址: www.baobaobooks.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: xutt $
 * $Id: goods.php 17217 2018-08-21 12:00:08Z xutt $
 */

// 新书推荐
/**
 * @api {get} /goods/newest 新书推荐
 * @apiVersion 1.0.0
 * @apiName get/goods/newest
 * @apiGroup getGoodsInfoGroup
 * @apiPermission none
 *
 * @apiDescription 获取今日上架的书，当今日没有上架书时，取最近两天上架的商品
 *
 * @apiSuccess (200)    {Integer}   goods_id        商品id
 * @apiSuccess (200)    {String}    goods_name      商品名称
 * @apiSuccess (200)    {String}    goods_thumb     商品缩略图
 * @apiSuccess (200)    {String}    goods_brief    商品简介
 * @apiSuccess (200)    {String}    goods_price    购买价
 * @apiSuccess (200)    {String}    goods_price_formated 购买价 (未登录是商品销售价，已登录是会员价，团购商品团购价)
 * @apiSuccess (200)    {Integer}    audio 是否是有声资源 1 是 0 否
 * @apiSuccess (200)    {Integer}    audoi_type 有声资源类型 1.美音，2.英音
 * @apiSuccess (200)    {Integer}    group_status 团购状态 -1 不是团购 商品 0 等待开团 1 正在团购
 * @apiSuccess (200)    {String}    goods_stock 商品库存 超过99时，返回99+
 *
 * @apiSuccessExample {json} 正确响应:
 *     HTTP/1.1 200 OK
 *      [
 *          {
 *              "goods_id": "11411",
 *              "goods_name": "英国进口 Child's Play  独家授权  6册 低幼精装玩具指偶书【精装】吴敏兰廖彩杏书单",
 *              "goods_brief": "孩子把小手指套进去装扮成故事的主角，随着每页故事的变化，小手配合做出各种相应的表情，同时也可以发出不同的语调，不仅锻炼了孩子的动手能力也强化了他的情感表达能力。\r\n\r\n家长也可以利用这些手偶与孩子进行互动。书里粘着布制作的玩具材料，读前可以让孩子摸一摸，体验不同触感。然后把手指伸进小手套进去装扮成小动物，随着故事每页的变化，小手配合做出各种动作。这样做不仅可以促进宝宝感触觉的发展，还可以促进手指精细动作发展和手眼协调。",
 *              "goods_price": "￥236.00",
 *              "goods_price_formated": "￥0.00",
 *              "goods_thumb": "https://oss.baobaobooks.net/shop/mobile/static/img_manual/img_none.png"
 *              "audio":1 ,
 *              "audio_type":1,
 *              "group_status": -1,
 *              "goods_stock" :99
 *          }
 *      ]
 *
 */
$app->get('/goods/newest', function () use ($app) {
    $key = 'goods:newest';
    $goods = $app->redis->lRange($key, 0, -1);

    if (empty($goods)) {
        return Utils::response(500, '数据获取异常');
    }

    $app->response->setStatusCode(200);
    $app->response->setContent($goods);
});

$app->get('/hello', function () use ($app) {
    echo "hi world!!!!";exit();
});
