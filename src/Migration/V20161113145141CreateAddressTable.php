<?php

namespace Miaoxing\Address\Migration;

use Miaoxing\Plugin\BaseMigration;

class V20161113145141CreateAddressTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->scheme->table('address')
            ->id()
            ->int('userId')
            ->int('areaId')->comment('对应区域的编号,如深圳是440300')
            ->string('name', 32)->comment('姓名')
            ->string('contact', 32)->comment('联系方式')
            ->string('province', 32)->comment('省份')
            ->string('city', 32) ->comment('城市')
            ->string('area', 32)->comment('县或区的名称')
            ->string('street', 32)->comment('街道')
            ->string('address', 255)->comment('地址')
            ->string('zipcode', 8)->comment('邮政编码')
            ->char('idCard', 18)->comment('身份证')
            ->bool('defaultAddress')->comment('是否默认地址')
            ->bool('enable')->comment('是否启用')
            ->tinyInt('source', 1)->comment('来源')
            ->timestamps()
            ->index(['userId', 'enable'])
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->scheme->dropIfExists('address');
    }
}
