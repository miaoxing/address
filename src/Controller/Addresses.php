<?php

namespace Miaoxing\Address\Controller;

class Addresses extends \Miaoxing\Plugin\BaseController
{
    public const SOURCE_WECHAT = 2;

    public function indexAction($req)
    {
        $addresses = wei()->address()
            ->mine()
            ->enabled()
            ->desc('defaultAddress')
            ->desc('id')
            ->findAll();

        switch ($req['_format']) {
            case 'json':
                return $this->suc([
                    'data' => $addresses,
                ]);

            default:
                $this->page->setTitle('收货地址');
                $this->page->hideFooter();

                return get_defined_vars();
        }
    }

    public function newAction($req)
    {
        $address = wei()->address();

        $address->fromArray(wei()->lbs->getIpInfo());

        wei()->event->trigger('editAddress', [$address]);

        return $this->suc([
            'data' => $address->toArray(),
        ]);
    }

    public function createAction($req)
    {
        return $this->updateAction($req);
    }

    public function updateAction($req)
    {
        $validator = wei()->validate([
            'data' => $req,
            'rules' => [
                'name' => [
                ],
                'contact' => [
                    'mobileCn' => true,
                ],
                'idCard' => [
                    'required' => false,
                    'idCardCn' => true,
                ],
                'province' => [
                ],
                'city' => [
                ],
                'area' => [
                ],
                'address' => [
                ],
            ],
            'messages' => [
                'idCard' => [
                    'idCardCn' => '%name%格式不正确',
                ],
            ],
            'names' => [
                'name' => '姓名',
                'contact' => '手机号码',
                'idCard' => '身份证',
                'province' => '省份',
                'city' => '城市',
                'area' => '区域',
                'address' => '详细地址',
            ],
        ]);
        if (!$validator->isValid()) {
            return $this->err($validator->getFirstMessage());
        }

        $address = wei()->address()->mine()->findId($req['id'], $req);

        // 触发保存前回调
        $ret = wei()->event->until('preAddressSave', [$address]);
        if ($ret) {
            return $this->ret($ret);
        }

        // 设置其他地址未非默认
        if (1 == $req['defaultAddress']) {
            wei()->address->updateAddress(['defaultAddress' => 0], ['userId' => $this->curUser['id']]);
        }

        $address->save([
            'userId' => $this->curUser['id'],
            'enable' => true,
        ]);

        return $this->suc([
            'message' => '保存成功',
            'id' => $address['id'],
            'data' => $address->toArray(),
        ]);
    }

    public function destroyAction($req)
    {
        $address = wei()->address()->mine()->findOneById($req['id']);
        $address->save([
            'enable' => 0,
        ]);

        return $this->suc('删除成功');
    }
}
