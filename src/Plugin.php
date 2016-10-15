<?php

namespace Miaoxing\Address;

use miaoxing\plugin\BasePlugin;

class Plugin extends BasePlugin
{
    protected $name = '用户地址';

    protected $description = '';

    public function onLinkToGetLinks(&$links, &$types)
    {
        $links[] = [
            'typeId' => 'mall',
            'name' => '地址列表',
            'url' => 'addresses'
        ];
    }
}
