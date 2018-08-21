<?php
/**
 * 孩宝小镇电商系统分类相关接口
 * ============================================================================
 * * 版权所有 2005-2016 深圳市凯迪克文化传播有限公司，并保留所有权利。
 * * 网站地址: www.baobaobooks.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: xutt $
 * $Id: category.php 17217 2016-04-09 17:57:08Z xutt $
 */

/**
 * @api {get} /categories 全部分类
 * @apiVersion 1.0.0
 * @apiName get/categories
 * @apiGroup categoryGroup
 * @apiPermission none
 *
 * @apiDescription 获取商品所有分类
 *
 * @apiSuccess (200)    {Integer}   id            分类id
 * @apiSuccess (200)    {String}    name        分类名称
 * @apiSuccess (200)    {Array}     children    子分类
 * @apiSuccess (200)    {Integer}   id            子分类id
 * @apiSuccess (200)    {String}    name        子分类名称
 *
 * @apiSuccessExample {json} 正确响应:
 *     HTTP/1.1 200 OK
 *     [
 *         {
 *             "id": 1,
 *             "name": "0-2岁",
 *             "children": [
 *                 {
 *                     "id": 2,
 *                     "name": "纸板书"
 *                 }
 *             ]
 *         },
 *         ...
 *     ]
 *
 * @apiSampleRequest /categories
 */
$app->get('/categories', function () use ($app) {
    $categoryService = new CategoryService();

    $categories = $categoryService->getAllCategories();

    $app->response->setStatusCode(200);
    $app->response->setJsonContent($categories);

    return $app->response;
});

/**
 * @api {get} /categories/:id/goods 分类的全部商品
 * @apiVersion 1.0.0
 * @apiName get/categories/:id/goods
 * @apiGroup categoryGroup
 * @apiPermission none
 *
 * @apiDescription 获取某个分类的全部商品，分页展示
 *
 * @apiParam {Integer} page 第几页
 * @apiParam {Integer} per_page 每页显示的数目
 *
 * @apiSuccess (200)    {Integer}   category_id        分类id
 * @apiSuccess (200)    {Integer}   total_count        总记录数
 * @apiSuccess (200)    {Integer}   total_pages        总页数
 * @apiSuccess (200)    {Integer}   current_page    当前页
 * @apiSuccess (200)    {Integer}   next_page        下一页
 *
 * @apiSuccess (200)    {Array}     goods_list        商品列表
 * @apiSuccess (200)    {Integer}   goods_list.goods_id        商品id
 * @apiSuccess (200)    {String}    goods_list.goods_name        商品名称
 * @apiSuccess (200)    {String}    goods_list.goods_img        商品图
 * @apiSuccess (200)    {String}    goods_list.goods_thumb        商品缩略图
 * @apiSuccess (200)    {Float}     goods_list.goods_price        商品价格(未登录是销售价，登录是会员价，团购商品是团购价)
 * @apiSuccess (200)    {Integer}   goods_list.group_status    商品团购状态
 * @apiSuccess (200)    {Integer}   goods_list.group_price    团购价
 * @apiSuccess (200)    {Integer}   goods_list.is_on_sale        商品上架状态
 * @apiSuccess (200)    {Integer}   goods_list.is_sold_out        商品售罄状态
 * @apiSuccess (200)    {Integer}   goods_list.is_group       是否是团购商品 0-否 1-是
 * @apiSuccess (200)    {Integer}   goods_list.audio       是否是有声资源0-否 1-是
 * @apiSuccess (200)    {Integer}   goods_list.audio_type  有声资源类型 1-美音 1-英音
 * @apiSuccess (200)    {Integer}   goods_list.goods_price_formated  格式化商品售价
 * @apiSuccess (200)    {String}   goods_list.tag  活动标签，如满减、z选x、特价等
 * @apiSuccess (200)    {Object}    share 分享活动
 * @apiSuccess (200)    {String}    share.title 分享的title
 * @apiSuccess (200)    {String}    share.content 分享的内容
 * @apiSuccess (200)    {String}    share.url 分享的URL
 * @apiSuccess (200)    {String}    share.image 分享的图片的URL
 * @apiSuccess (200)    {Object}    [wechatSign] 微信签名（只有在微信内访问时才有）
 * @apiSuccess (200)    {String}   wechatSign.appId              微信ID
 * @apiSuccess (200)    {String}   wechatSign.timestamp          微信时间戳
 * @apiSuccess (200)    {String}   wechatSign.nonceStr           微信字符串
 * @apiSuccess (200)    {String}   wechatSign.signature          微信验证
 * @apiSuccess (200)    {String}   wechatSign.url          签名连接
 *
 * @apiSuccessExample {json} 正确响应:
 *     HTTP/1.1 200 OK
 *      {
 *         "category_id": "10",
 *         "total_count": 175,
 *         "total_pages": 18,
 *         "current_page": 1,
 *         "next_page": 2,
 *         "goods_list": [
 *             {
 *                 "goods_name": "美国进口 最经典婴幼香味玩具书 Pat the Bunny【盒装】",
 *                 "goods_img": "https://oss.baobaobooks.net/images/201510/goods_img/9623_G_1444329935424.jpg",
 *                 "goods_thumb": "https://oss.baobaobooks.net/images/201510/*      goods_img/9623_G_1444329935424.jpg@1e_1c_0o_1l_160h_160w_98q.webp",
 *                 "group_price": "26.00",
 *                 "is_on_sale": "1",
 *                 "is_group": "0",
 *                 "audio": "1",
 *                 "audio_type": "2",
 *                 "goods_id": "9623",
 *                 "group_status": -1,
 *                 "goods_price": 33,
 *                 "goods_price_formated": "￥33.00",
 *                 "is_sold_out": 0
 *             }
 *         ],
 *         "share": {
 *             "title": "0-2岁-纸板书-shopdev",
 *             "content": "请输入童书馆简介：请输入童书馆简介：请输入童书馆简介：请输入童书馆简介：请输入童书馆简介：",
 *             "url": "https://shopdev.baobaobooks.net/categories/10",
 *             "image": "https://osstest.baobaobooks.net/haibao/applyimages/201707/149941608265755.png"
 *         },
 *         "wechatSign": {
 *             "appId": "wxf4e35bba3d0682cb",
 *             "nonceStr": "2QIwsYdyNrivoIra",
 *             "timestamp": 1501907537,
 *             "url": "https://shopdev.baobaobooks.net/categories/10",
 *             "signature": "aaf6eb0ab14ecbdccd866b973ad317b238ce497d"
 *             
 *             }
 *         }
 * 
 * @apiSampleRequest /categories
 */
$app->get('/categories/{id:\d+}/goods', function ($id) use ($app) {
    $page = (int)$app->request->getQuery("page", "int", 1);
    $size = (int)$app->request->getQuery("per_page", "int", 10);

    if ($id > 0) {
        $subCategories = PcIndexReadTravel::find([
            "conditions" => "parent_id = :id: AND is_show = 1",
            "columns" => "id",
            "bind" => [
                "id" => $id
            ]
        ]);
        $ids = [$id];
        foreach ($subCategories as $c) {
            $ids[] = $c->id;
        }
        $readConditions = "scg.read_travel_id IN (" . implode(',', $ids) . ") AND ";
    } else {
        $readConditions = '';
    }

    $domain = isset($app->client->domain) ? $app->client->domain : '';
    $conditions = Utils::getGoodsBrandConditionsByDomain($domain);

    //获取商品总数
    $phql = 'SELECT COUNT(DISTINCT(g.goods_id)) AS count ' .
        ' FROM GoodsReadTravel AS scg ' .
        ' LEFT JOIN Goods AS g ON scg.goods_id = g.goods_id ' .
        ' WHERE ' . $readConditions . 'g.is_delete = 0 AND g.is_on_sale = 1' . $conditions;

    $row = $app->modelsManager->executeQuery($phql)->getFirst();

    $count = (int)$row['count'];

    $totalPage = ceil($count / $size);

    $page = max($page, 1);
    $offset = ($page - 1) * $size;
    $nextPage = $page + 1;

    //获取商品
    $phql = "SELECT DISTINCT(g.goods_id) AS goodsid, g.goods_name, g.goods_img,g.goods_thumb, " .
        " g.shop_price, g.group_price, g.goods_number, g.is_on_sale, g.is_group, g.audio, g.audio_type " .
        " FROM GoodsReadTravel AS scg " .
        " LEFT JOIN Goods AS g ON scg.goods_id = g.goods_id " .
        " WHERE {$readConditions} g.is_delete = 0 AND g.is_on_sale = 1 " . $conditions .
        " ORDER BY g.goods_number = 0, g.sort_order DESC, g.goods_id DESC" .
        " LIMIT {$offset}, {$size}";

    $goodsList = $app->modelsManager->executeQuery($phql);

    $result = [
        'category_id' => $id,
        'total_count' => $count,
        'total_pages' => $totalPage,
        'current_page' => $page,
        'next_page' => $nextPage > $totalPage ? -1 : $nextPage,
        'goods_list' => []
    ];

    $goodsService = new GoodsService();
    $groupService = new GroupService();
    $goodsTagService = new \Tag\TagService();

    foreach ($goodsList as $goods) {
        $tmp = $goods->toArray();
        $tmp['goods_id'] = $tmp['goodsid'];

        if ($tmp['is_group']) {
            $group = $groupService->getGroupGoodsInfo($tmp['goods_id']);
            $tmp['group_status'] = $group['status'];
            if ($group['status'] == -1) {
                $tmp['is_group'] = 0;
            } else {
                $tmp['goods_number'] = $group['goods_stock'];
            }
        } else {
            $tmp['group_status'] = -1;
        }

        // $tmp['goods_price'] = $tmp['group_status'] == -1 ? $goodsService->getGoodsPrice($goods->shop_price, $tmp['goods_id']) : $goods->group_price;
        $tmp['goods_price'] = $goodsService->getGoodsPrice($goods->shop_price, $tmp['goods_id']);
        $tmp['goods_price_formated'] = Utils::formatPrice($tmp['goods_price']);
        $tmp['is_sold_out'] = $tmp['goods_number'] == 0 ? 1 : 0;

        if (!empty($tmp['goods_thumb'])) {
            $tmp['goods_thumb'] = Utils::autoGenOssImg(IMAGE_160W_160H, $tmp['goods_img']);
        } else {
            $tmp['goods_thumb'] = Utils::loadDefaultGoodsImage();//商品图不存在的时候加载默认图
        }
        $tmp['tag'] = $goodsTagService->getTag((int) $tmp['goods_id']);
        if ($tmp['tag'] == '领基金满减') {
            $tmp['tag'] = '';
        }

        unset($tmp['goodsid'], $tmp['shop_price'], $tmp['goods_number']);

        $result['goods_list'][] = $tmp;
    }

    $storeService = new StoreService();
    $pageUrl = $storeService->getStorePath().sprintf($app->config->shareUrl->categories, $id);
    $storeInfo = $storeService->getStoreByStoreId($app->client->storeId);

    /*获取微信分享标题start*/
    $categorie = PcIndexReadTravel::findFirst([
        "conditions" => "id = :id: AND is_show = 1",
        "columns" => "id,name,parent_id",
        "bind" => [
            "id" => $id
        ],
    ]);
    $prefix = '';
    $categorie && $prefix = $categorie->name;

    if ($categorie && $categorie->parent_id) {
        $parent = PcIndexReadTravel::findFirst([
            "conditions" => "id = :id: AND is_show = 1",
            "columns" => "id,name",
            "bind" => [
                "id" => $categorie->parent_id
            ],
        ]);
        $parent && $prefix = $parent->name . '-' . $prefix;
    }
    /*获取微信分享标题end*/
    if($prefix) {
        $shareTitle = $prefix . "-" . $storeInfo['name'];
    } else {
        $shareTitle = $storeInfo['name'];
    }

    $result['share'] = [
        'title' => $shareTitle,
        'content' => isset($storeInfo['store_desc']) ? Utils::formatDesc($storeInfo['store_desc']) : '',
        'url' => $pageUrl,
        'image' => empty($storeInfo['logo']) ? $app->config->defaultStoreInfo->logo : $storeInfo['logo'],
    ];
    
    if (Utils::isWechat()) {
        $result['wechatSign'] = $app->wxjssdk->GetSignPackage(true,$app->request->getHTTPReferer() ?: $pageUrl);
        unset($result['wechatSign']['rawString']);
    }

    $app->response->setStatusCode(200);
    $app->response->setJsonContent($result);

    return $app->response;
});