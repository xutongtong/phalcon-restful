<?php

class Goods extends ModelBase
{

    /**
     *
     * @var integer
     * @Primary
     * @Identity
     * @Column(type="integer", length=64, nullable=false)
     */
    public $goods_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=64, nullable=false)
     */
    public $cat_id;

    /**
     *
     * @var string
     * @Column(type="string", length=60, nullable=false)
     */
    public $goods_sn;

    /**
     *
     * @var string
     * @Column(type="string", length=120, nullable=false)
     */
    public $goods_name;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $click_count;

    /**
     *
     * @var integer
     * @Column(type="integer", length=64, nullable=false)
     */
    public $brand_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=8, nullable=false)
     */
    public $goods_number;

    /**
     *
     * @var integer
     * @Column(type="integer", length=8, nullable=false)
     */
    public $safe_stock;

    /**
     *
     * @var integer
     * @Column(type="integer", length=8, nullable=false)
     */
    public $purchase_number;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=false)
     */
    public $goods_weight;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=false)
     */
    public $market_price;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=false)
     */
    public $shop_price;

    /**
     *
     * @var double
     * @Column(type="double", length=11, nullable=false)
     */
    public $group_price;

    /**
     *
     * @var double
     * @Column(type="double", length=10, nullable=false)
     */
    public $promote_price;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $promote_start_date;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $promote_end_date;

    /**
     *
     * @var integer
     * @Column(type="integer", length=3, nullable=false)
     */
    public $warn_number;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $keywords;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $goods_brief;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $goods_desc;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $goods_mobile_desc;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $goods_thumb;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $goods_img;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $original_img;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $is_on_sale;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $integral;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $add_time;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $sort_order;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $is_delete;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $is_group;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $is_new;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $is_hot;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $is_promote;

    /**
     *
     * @var integer
     */
    public $is_discount;

    /**
     *
     * @var integer
     * @Column(type="integer", length=3, nullable=false)
     */
    public $bonus_type_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $last_update;

    /**
     *
     * @var integer
     * @Column(type="integer", length=5, nullable=false)
     */
    public $goods_type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $give_integral;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $rank_integral;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $notice;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $selled_num;

    /**
     *
     * @var integer
     * @Column(type="integer", length=64, nullable=false)
     */
    public $subject_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $subject_award;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $subject_year;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $audio;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $audio_type;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $video;

    /**
     *
     * @var integer
     * @Column(type="integer", length=64, nullable=false)
     */
    public $author_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=64, nullable=false)
     */
    public $drawer_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=11, nullable=false)
     */
    public $limit_buy_number;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $low_stock_time;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $ad_image;

    /**
     *
     * @var string
     * @Column(type="string", length=255, nullable=false)
     */
    public $ad_thumb_image;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $book_image;

    /**
     *
     * @var string
     * @Column(type="string", length=500, nullable=false)
     */
    public $book_image_thumb;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $default_store_group_goods;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $default_store_hot_goods;

    /**
     *
     * @var integer
     * @Column(type="integer", length=64, nullable=false)
     */
    public $recommend_store_id;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $on_sale_at;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $last_on_sale_at;

    /**
     *
     * @var integer
     * @Column(type="integer", length=10, nullable=false)
     */
    public $auto_on_sale_at;

    /**
     *
     * @var integer
     * @Column(type="integer", length=1, nullable=false)
     */
    public $goods_tag;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $created_at;

    /**
     *
     * @var string
     * @Column(type="string", nullable=false)
     */
    public $updated_at;

    /**
     * Allows to query a set of records that match the specified conditions
     *
     * @param mixed $parameters
     * @return EcsGoods[]|EcsGoods
     */
    public static function find($parameters = null)
    {
        return parent::find($parameters);
    }

    /**
     * Allows to query the first record that match the specified conditions
     *
     * @param mixed $parameters
     * @return EcsGoods
     */
    public static function findFirst($parameters = null)
    {
        return parent::findFirst($parameters);
    }

    /**
     * Initialize method for model.
     */
    public function initialize()
    {
        $this->setSchema("goods");
    }

    /**
     * Returns table name mapped in the model.
     *
     * @return string
     */
    public function getSource()
    {
        return 'goods';
    }
}
