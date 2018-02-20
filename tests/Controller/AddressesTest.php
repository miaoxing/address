<?php

namespace MiaoxingTest\Address\Controller;

use Miaoxing\Plugin\Test\BaseControllerTestCase;

class AddressesTest extends BaseControllerTestCase
{
    public function testCreate()
    {
        $ret = wei()->tester()
            ->login(1)
            ->controller('addresses')
            ->action('create')
            ->request([
                'name' => '姓名',
                'contact' => '13800138000',
                'province' => '广东省',
                'city' => '深圳市',
                'area' => '南山区',
                'address' => '深南大道',
                'defaultAddress' => '1',
            ])
            ->json()
            ->response();
        $this->assertRetSuc($ret, null, '创建默认地址');

        $ret = wei()->tester()
            ->login(1)
            ->controller('addresses')
            ->json()
            ->response();
        $this->assertRetSuc($ret);
        $address = $ret['data'][0];
        $this->assertArraySubset([
            'name' => '姓名',
            'contact' => '13800138000',
            'province' => '广东省',
            'city' => '深圳市',
            'area' => '南山区',
            'address' => '深南大道',
            'defaultAddress' => '1',
        ], $address, true, '回到地址列表看到刚创建的地址');

        $ret = wei()->tester()
            ->login(1)
            ->controller('addresses')
            ->action('create')
            ->request([
                'name' => '姓名2',
                'contact' => '13800138001',
                'province' => '广东省2',
                'city' => '深圳市2',
                'area' => '南山区2',
                'address' => '深南大道2',
                'defaultAddress' => '1',
            ])
            ->json()
            ->response();
        $this->assertRetSuc($ret, null, '创建新的默认地址');

        $ret = wei()->tester()
            ->login(1)
            ->controller('addresses')
            ->json()
            ->response();
        $this->assertRetSuc($ret);
        $address = $ret['data'][0];
        $this->assertArraySubset([
            'name' => '姓名2',
            'defaultAddress' => '1',
        ], $address, true, '回到地址列表看到新创建的地址');
    }
}
