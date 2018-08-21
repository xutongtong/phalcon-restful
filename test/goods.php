<?php
/**
 * Created by PhpStorm.
 * User: tong
 * Date: 2018/8/21
 * Time: 19:57
 */
$app->post('/test/goods/newest', function () use ($app) {
    // 前天6点时间戳
    $date = date("Ymd", strtotime('today') - 2 * 24 * 3600);
    $sql = 'SELECT g.goods_id, g.goods_name, g.goods_thumb, g.shop_price, g.group_price, g.sort_order ,' .
            ' g.is_group,g.audio,g.audio_type,goods_number, g.is_group  FROM goods AS g' .
            ' WHERE is_delete = 0 AND is_on_sale = 1 AND g.sort_order >= "' . $date . '" AND g.sort_order < "' . date("Ymd", strtotime('tomorrow')) . '"' .
            ' ORDER BY goods_number=0, sort_order DESC';

    $result_set = $app->db->query($sql);

    $result_set->setFetchMode(Phalcon\Db::FETCH_ASSOC);
    $recommendedGoods = $result_set->fetchAll($result_set);

    return Utils::responsJSONContent($recommendedGoods);

    $arrToday = []; //今天上架商品
    $arrYesterday = []; //昨天上级商品
    $arrPreYesterday = []; //前天上架商品
    $today = date("Ymd", strtotime('today'));
    $yesterday = date("Ymd", strtotime('yesterday'));

    foreach ($recommendedGoods as $goods) {
        $tmp = [];
        $tmp['goods_id'] = $goods->goods_id;
        $tmp['goods_name'] = $goods->goods_name;
        // $tmp['goods_brief'] = $goods->goods_brief;
        $tmp['goods_thumb'] = $goods->goods_thumb;
        $tmp['audio'] = $goods->audio;
        $tmp['audio_type'] = $goods->audio_type;
        $tmp['group_status'] = -1;
        $tmp['goods_stock'] = $goods->goods_number > 99 ? "99+" : $goods->goods_number;
        // $tmp['goods_price'] = $tmp['group_status'] == -1 ? $goodsService->getGoodsPrice($goods->shop_price,$goods->goods_id) : $goods->group_price;
        $tmp['goods_price'] = $goods->shop_price;
        $tmp['goods_price_formated'] = Utils::formatPrice($tmp['goods_price']);

        $tmp['goods_thumb'] = Utils::formatWebp($tmp['goods_thumb']);
        if (empty($tmp['goods_thumb'])) {
            $tmp['goods_thumb'] = Utils::loadDefaultGoodsImage(); //商品图不存在的时候加载默认图
        }

        $sortOrder = $goods->sort_order;
        unset($goods->sort_order);

        if (strcmp($sortOrder, $today) >= 0) {
            $arrToday[] = $tmp;
        } elseif (strcmp($sortOrder, $yesterday) >= 0) {
            $arrYesterday[] = $tmp;
        } else {
            $arrPreYesterday[] = $tmp;
        }
    }
    $result = !empty($arrToday) ? $arrToday : (!empty($arrYesterday) ? $arrYesterday : $arrPreYesterday);

    var_dump($result);exit();

    $key = 'goods:newest';
    // 保存到redis中
    foreach ($result as $key => $val) {
        $app->redis->rPush($key, json_encode($val));
    }

    Utils::responsJSONContent($app->redis->lRange($key, 0, -1));
});