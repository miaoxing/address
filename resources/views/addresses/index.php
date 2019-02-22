<?php $view->layout() ?>

<?= $block->css() ?>
<link rel="stylesheet" href="<?= $asset('plugins/address/css/addresses.css') ?>">
<?= $block->end() ?>

<div class="js-address-container">

</div>

<?php require $view->getFile('@address/addresses/picker.php') ?>

<?= $block->js() ?>
<script>
  require([
    'plugins/address/js/addresses',
    'plugins/app/libs/artTemplate/template.min',
    'comps/jquery.loadJSON/index.min',
    'plugins/app/libs/jquery-form/jquery.form'
  ], function (address) {
    address.indexAction({
      $el: $('.js-address-container')
    });
  });
</script>
<?= $block->end() ?>
