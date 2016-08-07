<?php

namespace miaoxing\address\services;

use miaoxing\plugin\BaseModel;

class Address extends BaseModel
{
    protected $table = 'address';

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
        $default = wei()->db($this->table)->where('userId=?', $userId)->andWhere('enable=?', 1)->andWhere('defaultAddress=?', 1)->fetch();
        if ($default) {
            return $default;
        }
        $list = wei()->db($this->table)->where('userId=?', $userId)->andWhere('enable=?', 1)->orderBy('id', 'asc')->fetchAll();
        if ($list) {
            return $list[0];
        }
        return array();
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
