<?php

namespace Miaoxing\Address\Migration;

use Wei\Migration\BaseMigration;

class V20161113145141CreateAddressesTable extends BaseMigration
{
    /**
     * {@inheritdoc}
     */
    public function up()
    {
        $this->schema->table('addresses')
            ->bigId()
            ->uBigInt('app_id')
            ->bigInt('user_id')
            ->string('name', 32)->comment('姓名')
            ->string('mobile', 16)->comment('手机号码')
            ->string('province', 32)->comment('省份')
            ->string('city', 32)->comment('城市')
            ->string('district', 32)->comment('县或区的名称')
            ->string('street', 32)->comment('街道')
            ->string('detail')->comment('详细地址')
            ->string('postcode', 8)->comment('邮政编码')
            ->char('id_card', 18)->comment('身份证')
            ->bool('is_default')->comment('是否默认地址')
            ->tinyInt('source')->comment('来源')
            ->timestamps()
            ->index(['user_id'])
            ->exec();
    }

    /**
     * {@inheritdoc}
     */
    public function down()
    {
        $this->schema->dropIfExists('addresses');
    }
}
