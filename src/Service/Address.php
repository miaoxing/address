<?php

namespace Miaoxing\Address\Service;

use Miaoxing\Plugin\BaseModel;
use Miaoxing\Plugin\Model\ModelTrait;

/**
 * @property string|null $id
 * @property string $appId
 * @property string $userId
 * @property string $name 姓名
 * @property string $mobile 手机号码
 * @property string $province 省份
 * @property string $city 城市
 * @property string $district 县或区的名称
 * @property string $street 街道
 * @property string $detail 详细地址
 * @property string $postcode 邮政编码
 * @property string $idCard 身份证
 * @property bool $isDefault 是否默认地址
 * @property int $source 来源
 * @property string|null $createdAt
 * @property string|null $updatedAt
 */
class Address extends BaseModel
{
    use ModelTrait;

    /**
     * Repo
     *
     * @param $id
     * @return $this|null
     */
    public function getMy($id)
    {
        $address = $this()->findOneById($id);
        if ($address['userId'] != wei()->curUser['id']) {
            $address = null;
        }

        return $address;
    }

    /**
     * 获取收货地址列表
     *
     * @param string $userId
     * @return array
     */
    public function getDefaultAddress($userId)
    {
        $default = wei()->db($this->table)
            ->where('userId=?', $userId)
            ->andWhere('enable=?', 1)
            ->andWhere('defaultAddress=?', 1)
            ->fetch();
        if ($default) {
            return $default;
        }

        $list = wei()->db($this->table)
            ->where('userId=?', $userId)
            ->andWhere('enable=?', 1)
            ->orderBy('id', 'asc')
            ->fetchAll();
        if ($list) {
            return $list[0];
        }

        return [];
    }

    /**
     * 更新地址
     *
     * @param array $data
     * @param array $conditions
     * @return array
     */
    public function updateAddress($data, $conditions)
    {
        return wei()->db->update($this->table, $data, $conditions);
    }

    public function getFullAddress()
    {
        return $this['province'] . $this['city'] . $this['area'] . $this['street'] . $this['address'];
    }

    /**
     * 获取城市对应的区域编号或名称,用于运费计算
     *
     * NOTE: 如果areaId成熟,可以删除该方法
     *
     * @return string
     */
    public function getCityIdOrName()
    {
        if ($this['areaId']) {
            return substr($this['areaId'], 0, 4) . '00';
        } else {
            return $this['city'];
        }
    }

    public function afterSave()
    {
        parent::afterSave();
        $this->clearTagCacheByUser();
    }

    public function afterDestroy()
    {
        parent::afterDestroy();
        $this->clearTagCacheByUser();
    }
}
