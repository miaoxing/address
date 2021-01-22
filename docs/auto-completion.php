<?php

/**
 * @property    Miaoxing\Address\Service\Address $address
 * @method      Miaoxing\Address\Service\Address address() 返回当前对象
 */
class AddressMixin {
}

/**
 * @mixin AddressMixin
 */
class AutoCompletion {
}

/**
 * @return AutoCompletion
 */
function wei()
{
    return new AutoCompletion;
}

/** @var Miaoxing\Address\Service\Address $address */
$address = wei()->address;
