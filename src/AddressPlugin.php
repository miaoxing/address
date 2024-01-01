<?php

namespace Miaoxing\Address;

use Miaoxing\Plugin\BasePlugin;

class AddressPlugin extends BasePlugin
{
    protected $name = '用户地址';

    protected $description = '';

    public function onLinkToGetLinks(&$links, &$types)
    {
        $links[] = [
            'typeId' => 'mall',
            'name' => '地址列表',
            'url' => 'addresses',
        ];
    }
}
