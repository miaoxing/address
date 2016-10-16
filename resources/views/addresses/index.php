<?php $view->layout() ?>

<?= $block('css') ?>
<link rel="stylesheet" href="<?= $asset('@address/assets/addresses.css') ?>">
<?= $block->end() ?>

<div class="js-address-container">

</div>

<?php require $view->getFile('address:addresses/picker.php') ?>

<?= $block('js') ?>
<script>
  require([
    'vendor/miaoxing/address/assets/addresses',
    'comps/artTemplate/template.min',
    'comps/jquery.loadJSON/index.min',
    'jquery-form'
  ], function (address) {
    address.indexAction({
      $el: $('.js-address-container')
    });
  });
</script>
<?= $block->end() ?>
