<?php
/**
 * 孩宝小镇电商系统收藏相关接口
 * ============================================================================
 * * 版权所有 2005-2016 深圳市凯迪克文化传播有限公司，并保留所有权利。
 * * 网站地址: www.baobaobooks.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: xutt $
 * $Id: collection.php 17217 2016-04-11 09:56:08Z xutt $
 */

/**
 * @api {post} /collections/goods 批量收藏童书
 * @apiVersion 1.0.0
 * @apiName post/collections/goods
 * @apiGroup collectionsGroup
 * @apiPermission token
 *
 * @apiParam {array} goods_ids    童书ID
 * @apiParamExample {json} 请求示例:
 * {
 *     "goods_ids": [1, 2, 3, 4],
 * }
 * @apiDescription 批量收藏童书
 *
 * @apiSuccess (200)  {string}  goods_id  童书ID
 * @apiSuccess (200)  {string}  user_id   用户ID
 *
 * @apiSuccessExample {json} 正确响应:
 *     HTTP/1.1 201 OK
 *    {
 *       "user_id": "797779",
 *       "goods_id": [
 *           "10893"
 *       ]
 *   }
 *
 */
$app->post("/collections/goods", function () use ($app) {
    //校验是否登录
    AuthUtil::verifyAuth($app);

    $json = $app->request->getJsonRawBody(true);
    $goodsIds = isset($json['goods_ids']) && is_array($json['goods_ids']) ? $json['goods_ids'] : [];
    if (empty($goodsIds)) {
        return Utils::response('ValidationError', '请选择要收藏的童书');
    }

    $ids = [];
    foreach ($goodsIds as $id) {
        $id = (int)$id;
        if ($id > 0) {
            $ids[] = $id;
        }
    }

    if (empty($ids)) {
        return Utils::response('ValidationError', '请选择要收藏的童书');
    }

    $userId = $app->session->get('user_id');

    //过滤有效商品ID
    $goods = Goods::find(['goods_id IN (' . implode(',', $ids) . ')', 'conditions' => 'goods_id']);
    if (empty($goods)) {
        return Utils::response('ValidationError', '无效童书ID');
    } else {
        $ids = array_column($goods->toArray(), 'goods_id');
    }

    //过滤已收藏的
    $collected = UsersCollections::find([
        'user_id = ' . $userId . ' AND collection_type=2 AND collection_type_id IN (' . implode(',', $ids) . ')',
        'conditions' => 'collection_type_id'
    ]);
    if ($collected) {
        $collect_ids = array_column($collected->toArray(), 'collection_type_id');
        $ids = array_diff($ids, $collect_ids);
    }

    if ($ids) {
        $phsq = 'INSERT INTO ecs_users_collections (user_id,collection_type,collection_type_id) VALUES ';
        $values = '';
        foreach ($ids as $goodsId) {
            $values .= '(' . $userId . ', 2, ' . $goodsId . '),';
        }
        $phsq .= rtrim($values, ',');

        $app->db->execute($phsq);
    }

    return $app->response->setStatusCode(201)->setJsonContent(['user_id' => $userId, 'goods_id' => $ids]);
});


/**
 * @api {delete} /collections/goods 批量删除收藏的商品
 * @apiVersion 1.0.0
 * @apiName delete/collections/goods
 * @apiGroup collectionsGroup
 * @apiPermission token
 * @apiDescription 批量删除用户收藏的商品
 *
 * @apiParam {Array}    goods_ids  删除指定的收藏商品列表
 * @apiParam {Integer}  is_clear  是否全部删除收藏商品 1-是 0-否
 *
 * @apiParamExample {json} 请求示例:
 * {
 *     "goods_ids": [1, 2, 3, 4],
 *     "is_clear":0
 * }
 *
 * @apiSuccessExample {json} 正确响应:
 *  HTTP/1.1 204 No Content
 *
 * @apiError (4xx) ValidationError 参数校验失败
 * @apiErrorExample {json} ValidationError:
 *     HTTP/1.1 422 Bad Request
 *     {
 *       "code": "ValidationError",
 *       "message": "请选择要删除的童书"
 *     }
 * @apiSampleRequest off
 */
$app->delete("/collections/goods", function () use ($app) {
    AuthUtil::verifyAuth($app);

    $json = $app->request->getJsonRawBody(true);
    $checkParam = ['goods_ids', 'is_clear'];

    foreach ($json as $key => $value) {
        if(!in_array($key,$checkParam)) {
            unset($json[$key]);
        }       
    }
    if(empty($json)) {
        Utils::response('ValidationError', '请选择要删除的童书')->send();
    }

    $is_clear = isset($json['is_clear']) && ($json['is_clear'] ? 1:0);
    $json['goods_ids'] = $json['goods_ids'] ?? [];

    $ids = [];
    if(!$is_clear) {
        foreach ($json['goods_ids'] as $id) {
            $id = (int)$id;
            if ($id > 0) {
                $ids[] = $id;
            }
        }
    }
    

    if (empty($ids) && !$is_clear) {
        return Utils::response('ValidationError', '请选择要删除的童书');
    }

    $userId = $app->session->get('user_id');

    if($is_clear) {
        $app->modelsManager->executeQuery('DELETE FROM UsersCollections WHERE user_id=' . $userId . ' AND collection_type=2');
    } else {
        $app->modelsManager->executeQuery('DELETE FROM UsersCollections WHERE user_id=' . $userId . ' AND collection_type=2 AND collection_type_id IN (' . implode(',',
            $ids) . ')');
    }

    
    return $app->response->setStatusCode(204);
});

/**
 * @api {get} /collections/goods 获取收藏商品列表
 * @apiVersion 1.0.0
 * @apiName get/collections/goods
 * @apiGroup collectionsGroup
 * @apiPermission token
 *
 * @apiDescription 获取收藏商品列表
 *

 * @apiSuccess (200)    {Integer}   total_count   总记录数
 * @apiSuccess (200)    {Integer}   total_pages   总页数
 * @apiSuccess (200)    {Integer}   current_page  当前页
 * @apiSuccess (200)    {Integer}   next_page     下一页
 *
 * @apiSuccess (200)    {Array}     goods_list 商品列表
 * @apiSuccess (200)    {Integer}   goods_list.goods_id       商品id
 * @apiSuccess (200)    {String}    goods_list.goods_name       商品名称
 * @apiSuccess (200)    {String}    goods_list.goods_thumb       商品缩略图
 * @apiSuccess (200)    {Float}     goods_list.shop_price    商品零售格
 * @apiSuccess (200)    {Integer}   goods_list.is_on_sale     商品是否在售
 * @apiSuccess (200)    {Integer}   goods_list.is_delete     是否删除 1-是，0-不是
 * @apiSuccess (200)   {Integer}    goods_list.audio     是否是有声资源 1-是 0-否
 * @apiSuccess (200)    {Integer}   goods_list.audio_type 有声资源类型 1-美音，0-英音
 * @apiSuccess (200)    {Integer}   goods_list.has_stock 是否有库存 0-否 1-有
 * @apiSuccess (200)    {Integer}   goods_list.group_status   团购状态 -1-不是团购商品或团购已结束 0-即将开团 1-正在团
 *
 * @apiSuccessExample {json} 正确响应:
 *     HTTP/1.1 200 OK
 *        {
 *           "total_count": 1,
 *           "total_pages": 1,
 *           "current_page": 1,
 *           "next_page": 2,
 *           "goods_list": [
 *               {
 *                   "goods_id": "10893",
 *                   "goods_name": "美国进口 Bedtime Monsters 睡前故事 克服内心恐惧 【精装】",
 *                   "goods_thumb": "https://oss.baobaobooks.net/images/201611/thumb_img/10893_thumb_G_1477954265498.jpg",
 *                   "shop_price": "￥32.00",
 *                   "is_on_sale": "1",
 *                   "is_delete": "0",
 *                   "audio": "0",
 *                   "audio_type": "1",
 *                   "has_stock": 1
 *               }
 *           ]
 *       }
 *
 */
$app->get("/collections/goods", function () use ($app) {
    AuthUtil::verifyAuth($app);

    $page = (int)$app->request->get('page', 'int', 1);
    $size = (int)$app->request->get('per_page', 'int', 15);
    $page = max($page, 1);

    $offset = ($page - 1) * $size;
    $limit = $size;

    $userId = $app->session->get('user_id');

    $goodsService = new GoodsService();

    $totalCount = $goodsService->getCollectGoodsCount($userId);
    $totalPages = ceil($totalCount / $size);

    $goods = $goodsService->getCollectGoods($userId, $offset, $limit);

    $result = [
        'total_count' => $totalCount,
        'total_pages' => $totalPages,
        'current_page' => $page,
        'next_page' => $page + 1 > $totalPages ? -1:$page + 1 ,
        'goods_list' => $goods
    ];

    $app->response->setStatusCode(200);
    $app->response->setJsonContent($result);

    return $app->response;
});


/**
 * @api {get} /collections/goods/:id/collect_status 是否已经收藏过某商品
 * @apiVersion 1.0.0
 * @apiName get/collections/goods/:id/collect_status
 * @apiGroup collectionsGroup
 * @apiPermission token
 *
 * @apiDescription 获取是否已经收藏过某商品
 *
 * @apiSuccess (200)  {Integer}   collect_status  0-未收藏 1-已收藏
 * @apiSuccessExample {json} 正确响应:
 *     HTTP/1.1 200 OK
 *     {
 *      "collect_status": 1
 *     }
 *
 */
$app->get("/collections/goods/{id:[1-9]\d*}/collect_status", function ($id) use ($app) {
    AuthUtil::verifyAuth($app);

    $userId = $app->session->get("user_id");

    $count = UsersCollections::count([
        'conditions' => 'user_id = :userId: AND collection_type=2 AND collection_type_id = :goodsId:',
        'bind' => [
            'userId' => $userId,
            'goodsId' => $id
        ]
    ]);

    $result = [
        'collect_status' => $count > 0 ? 1 : 0
    ];

    $app->response->setStatusCode(200);
    $app->response->setJsonContent($result);

    return $app->response;
});

