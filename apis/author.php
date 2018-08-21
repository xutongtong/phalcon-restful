<?php
/**
 * 孩宝小镇电商系统作者相关接口
 * ============================================================================
 * * 版权所有 2005-2016 深圳市凯迪克文化传播有限公司，并保留所有权利。
 * * 网站地址: www.baobaobooks.com；
 * ----------------------------------------------------------------------------
 * 这不是一个自由软件！您只能在不用于商业目的的前提下对程序代码进行修改和
 * 使用；不允许对程序代码以任何形式任何目的的再发布。
 * ============================================================================
 * $Author: xutt $
 * $Id: author.php 17217 2016-04-21 16:01:08Z xutt $
 */


/**
 * @api {get} /goods/:goods_id/authors 商品作者信息
 * @apiVersion 1.0.0
 * @apiName get/goods/:goods_id/authors
 * @apiGroup authorGroup
 * @apiPermission none
 *
 * @apiDescription 根据商品id获取商品的作者信息，包括作者头像，作者描述，作者代表作。
 *
 * @apiParam  {String}    goods_id    商品id
 *
 * @apiSuccess (200)    {Integer}   author_id       作者id
 * @apiSuccess (200)    {Integer}   author_name     作者名
 * @apiSuccess (200)    {Integer}   author_avatar   作者头像
 * @apiSuccess (200)    {Integer}   author_desc     作者描述
 * @apiSuccess (200)    {Array}   books           作者代表作
 * @apiSuccess (200)    {Integer}   books.goods_id        商品id
 * @apiSuccess (200)    {Integer}   books.goods_name      商品名称
 * @apiSuccess (200)    {Integer}   books.goods_thumb     商品缩略图
 *
 * @apiSuccessExample {json} 正确响应:
 *     HTTP/1.1 200 OK
 *     {
 *         "author_id": 1,
 *         "author_name": "Eric",
 *         "author_avatar": "https://oss.baobaobooks.net/images/goodsauthor/1461124361527630717.jpg",
 *         "author_desc": "大师...",
 *         "books":
 *         [
 *             {
 *                 "goods_id": 9876,
 *                 "goods_name": "美国进口国家地理少儿版 无所不知的奇趣百科之疯狂的大猫 National Geographic Kids Everything Big Cats【平装】",
 *                 "goods_thumb": "https://oss.baobaobooks.net/images/201604/goods_img/10064_P_1459899876673.jpg"
 *             },
 *             ...
 *         ]
 *     }
 *
 */
$app->get("/goods/{goods_id:[1-9]\d*}/authors", function ($goods_id) use ($app) {
    $goodsId = $goods_id;//商品id

    //获取商品的作者id
    $goodsService = new GoodsService();

    $authorId = $goodsService->getGoodsAuthorsId($goodsId);

    $authorService = new AuthorService();

    $storeId = isset($app->client->storeId) ? (int)$app->client->storeId : 0;

    $author = $authorService->getAuthorInfo($authorId, $goodsId, $storeId);

    $app->response->setStatusCode(200);
    $app->response->setJsonContent($author);

    return $app->response;
});

/**
 * @api {get} /goods/:goods_id/drawers 商品绘者信息
 * @apiVersion 1.0.0
 * @apiName get/goods/:goods_id/drawers
 * @apiGroup authorGroup
 * @apiPermission none
 *
 * @apiDescription 根据商品id获取商品的绘者信息，包括绘者头像，绘者描述，绘者代表作。
 *
 * @apiParam  {String}    goods_id    商品id
 *
 * @apiSuccess (200)    {Integer}   author_id       绘者id
 * @apiSuccess (200)    {Integer}   author_name     绘者名
 * @apiSuccess (200)    {Integer}   author_avatar   绘者头像
 * @apiSuccess (200)    {Integer}   author_desc     绘者描述
 *
 * @apiSuccess (200)    {Array}     books           作者代表作
 * @apiSuccess (200)    {Integer}   books.goods_id        商品id
 * @apiSuccess (200)    {Integer}   books.goods_name      商品名称
 * @apiSuccess (200)    {Integer}   books.goods_thumb     商品缩略图
 *
 * @apiSuccessExample {json} 正确响应:
 *     HTTP/1.1 200 OK
 *     {
 *         "author_id": 1,
 *         "author_name": "Eric",
 *         "author_avatar": "https://oss.baobaobooks.net/images/goodsauthor/1461124361527630717.jpg",
 *         "author_desc": "大师...",
 *         "books":
 *         [
 *             {
 *                 "goods_id": 9876,
 *                 "goods_name": "美国进口国家地理少儿版 无所不知的奇趣百科之疯狂的大猫 National Geographic Kids Everything Big Cats【平装】",
 *                 "goods_thumb": "https://oss.baobaobooks.net/images/201604/goods_img/10064_P_1459899876673.jpg"
 *             },
 *             ...
 *         ]
 *     }
 *
 */
$app->get("/goods/{goods_id:[1-9]\d*}/drawers", function ($goods_id) use ($app) {
    $goodsId = $goods_id;//商品id
    //获取商品的作者id
    $goodsService = new GoodsService();

    $drawerId = $goodsService->getGoodsDrawerId($goodsId);

    $authorService = new AuthorService();

    $storeId = isset($app->client->storeId) ? (int)$app->client->storeId : 0;

    $drawer = $authorService->getAuthorInfo($drawerId, $goodsId, $storeId);

    $app->response->setStatusCode(200);
    $app->response->setJsonContent($drawer);

    return $app->response;
});

/**
 * @api {get} /goods/:goods_id/authors_and_drawers  商品作者和绘者信息
 * @apiVersion 1.0.0
 * @apiName get/goods/:goods_id/authors_and_drawers
 * @apiGroup authorGroup
 * @apiPermission none
 *
 * @apiDescription 根据商品id获取商品的作者和绘者信息，包括作者和绘者头像，作者和绘者描述，作者和绘者代表作。
 *
 * @apiParam  {String}    goods_id    商品id
 *
 * @apiSuccess (200)    {Object}    author                 作者信息
 * @apiSuccess (200)    {Integer}   author.author_id       作者id
 * @apiSuccess (200)    {Integer}   author.author_name     作者名
 * @apiSuccess (200)    {Integer}   author.author_avatar   作者头像
 * @apiSuccess (200)    {Integer}   author.author_desc     作者描述
 *
 * @apiSuccess (200)    {Array}     author.books           作者代表作
 * @apiSuccess (200)    {Integer}   author.books.goods_id        商品id
 * @apiSuccess (200)    {Integer}   author.books.goods_name      商品名称
 * @apiSuccess (200)    {Integer}   author.books.goods_thumb     商品缩略图
 *
 * @apiSuccess (200)    {Object}    drawer                 绘者信息
 * @apiSuccess (200)    {Integer}   drawer.author_id       绘者id
 * @apiSuccess (200)    {Integer}   drawer.author_name     绘者名
 * @apiSuccess (200)    {Integer}   drawer.author_avatar   绘者头像
 * @apiSuccess (200)    {Integer}   drawer.author_desc     绘者描述
 *
 * @apiSuccess (200)    {Array}     drawer.books           绘者代表作
 * @apiSuccess (200)    {Integer}   drawer.books.goods_id        商品id
 * @apiSuccess (200)    {Integer}   drawer.books.goods_name      商品名称
 * @apiSuccess (200)    {Integer}   drawer.books.goods_thumb     商品缩略图
 *
 * @apiSuccessExample {json} 正确响应:
 *     HTTP/1.1 200 OK
 *	     {
 *	    "author": {
 *	        "author_name": "Brian Biggs ",
 *	        "author_avatar": "https://oss.baobaobooks.net/images/goodsauthor/1451448331415086118.jpg",
 *	        "author_desc": "布莱恩·比格斯—— Brian Biggs ,1968年，布莱恩出生于美国阿肯色州的一个小镇，1987年到纽约就读帕森设计学院，后来又到法国巴黎住了几年，现在住在宾州的费城。他喜欢一切会移动、有速度感的东西。\r\n他为很多童书画过插图，目前忙着创作让亨利上天下海的交通工具绘本，他自己创作、自己画图。",
 *	        "books": [
 *	            {
 *	                "goods_id": "9740",
 *	                "goods_name": "美国进口 初级章节书 Roscoe Riley Rules 1-7【平装】",
 *	                "goods_thumb": "https://oss.baobaobooks.net/images/201512/thumb_img/9740_thumb_G_1450315677810.jpg?w=320&h=320",
 *	                "book_url": "https://fxm5547.baobaobooks.net/goods/9740"
 *	            }
 *	        ]
 *	    },
 *	    "drawer": {
 *	        "author_name": "Brian Biggs ",
 *	        "author_avatar": "https://oss.baobaobooks.net/images/goodsauthor/1451448331415086118.jpg",
 *	        "author_desc": "布莱恩·比格斯—— Brian Biggs ,1968年，布莱恩出生于美国阿肯色州的一个小镇，1987年到纽约就读帕森设计学院，后来又到法国巴黎住了几年，现在住在宾州的费城。他喜欢一切会移动、有速度感的东西。\r\n他为很多童书画过插图，目前忙着创作让亨利上天下海的交通工具绘本，他自己创作、自己画图。",
 *	        "books": [
 *	            {
 *	                "goods_id": "9740",
 *	                "goods_name": "美国进口 初级章节书 Roscoe Riley Rules 1-7【平装】",
 *	                "goods_thumb": "https://oss.baobaobooks.net/images/201512/thumb_img/9740_thumb_G_1450315677810.jpg?w=320&h=320",
 *	                "book_url": "https://fxm5547.baobaobooks.net/goods/9740"
 *	            }
 *	        ]
 *	    }
 *	}
 *
 */
$app->get("/goods/{goods_id:[1-9]\d*}/authors_and_drawers", function ($goods_id) use ($app) {
    $goodsId = $goods_id;//商品id
    //获取商品的作者id
    $author = $drawer = [];
    $equal = 0;
    $goodsService = new GoodsService();

    $authorService = new AuthorService();

    $authorId = $goodsService->getGoodsAuthorsId($goodsId);

    $drawerId = $goodsService->getGoodsDrawerId($goodsId);

    $storeId = isset($app->client->storeId) ? (int)$app->client->storeId : 0;

    if ($authorId != 0) {
        $author = $authorService->getAuthorInfo($authorId, $goodsId, $storeId);

        if ($authorId != $drawerId) {
            $drawer = $authorService->getAuthorInfo($drawerId, $goodsId, $storeId);
            if ($drawer && empty($drawer['author_avatar']) && empty($drawer['author_desc']) && empty($drawer['books'])) {
                $drawer = [];
            }
        } else {
            $equal = 1;
        }

        //处理作者内容为空的情况
        if ($author && empty($author['author_avatar']) && empty($author['author_desc']) && empty($author['books'])) {
            $author = [];
        }

        if (empty($author) && empty($drawer)) {
            $equal = 0;
        }
    }
    $app->response->setStatusCode(200);
    $app->response->setJsonContent(['author' => $author, 'drawer' => $drawer, 'equal' => $equal]);
    return $app->response;
});