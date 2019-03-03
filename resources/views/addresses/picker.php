<script type="text/html" id="addressPickerTpl">
  <ul class="list">
    <li>
      <a class="list-item list-has-feedback" href="javascript:;">
        <div class="list-col">
          <% if (!address.id) { %>
          <h4 class="list-title address-empty">
            请先添加<?= $setting('order.titleReceive') ?: '收货' ?>地址
          </h4>
          <% } else { %>
          <h4 class="list-title">
            <?= $setting('order.titleReceive') ?: '收货' ?>人: <%= address.name %>
            <span class="float-right"><%= address.contact %></span>
          </h4>
          <div class="list-text">
            <%= address.province + address.city + address.area + address.street + address.address %>
          </div>
          <% } %>
          <input type="hidden" class="js-address-id" id="addressId" name="addressId" value="<%= address.id %>">
        </div>
        <i class="bm-angle-right list-feedback"></i>
      </a>
    </li>
  </ul>
</script>

<script type="text/html" class="js-address-list-tpl" id="addressListTpl">
  <ul class="js-address-list address-list list">
    <% $.each(data, function(i, address) { %>
    <li class="js-address-item list-item" data-id="<%= i %>">
      <div class="js-address-select address-select list-col">
        <i class="js-address-selected-icon address-selected-icon
          <%= selectedId == address.id ? 'bm-ok' : '' %> text-primary">
        </i>
        <h4 class="list-title">
          <%= address.name %> <%= address.contact %>
        </h4>
        <div class="list-text">
          <%= address.province + address.city + address.area + address.street + address.address %>
        </div>
      </div>
      <div class="list-col list-middle address-list-action">
        <a class="js-address-edit btn btn-sm btn-secondary" href="javascript:;">编辑</a>
      </div>
    </li>
    <% })%>
    <% if (data.length == 0) { %>
    <li class="list-empty bg-muted">您还没有地址</li>
    <% } %>
  </ul>
</script>

<script type="text/html" class="js-address-action-tpl" id="addressActionTpl">
  <button class="js-address-new btn btn-primary btn-fluid flex-grow-1" type="button">
    新增地址
  </button>
</script>

<script type="text/html" id="addressListMangerTpl">
  <% include('addressListTpl') %>
  <div class="footer-bar footer-bar-fluid flex">
    <% include('addressActionTpl') %>
  </div>
</script>

<script type="text/html" id="addressPickerModalTpl">
  <div class="address-list-modal modal-bottom modal fade" tabindex="-1" role="dialog"
    aria-labelledby="addressModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <div class="modal-title text-center" id="addressModalLabel">
            请选择地址
          </div>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="address-modal-body modal-body modal-body-fluid">
          <% include('addressListTpl') %>
        </div>
        <div class="modal-footer modal-footer-fluid flex">
          <% include('addressActionTpl') %>
        </div>
      </div>
    </div>
  </div>
</script>

<script type="text/html" id="addressFormModalTpl">
  <div class="address-form-modal modal-bottom modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <form class="js-address-form form" method="post">
          <div class="modal-header">
            <div class="modal-title text-center">
              地址管理
            </div>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body modal-body-fluid">
            <div class="js-form-group-name form-group">
              <label for="name" class="control-label">
                姓名
                <span class="text-warning">*</span>
              </label>

              <div class="col-control">
                <input type="text" class="form-control" id="name" name="name"
                  placeholder="请输入<?= $setting('order.titleReceive') ?: '收货' ?>人姓名">
              </div>
            </div>

            <div class="js-form-group-contact form-group">
              <label for="contact" class="control-label">
                手机
                <span class="text-warning">*</span>
              </label>

              <div class="col-control">
                <input type="tel" class="form-control" id="contact" name="contact"
                  placeholder="请输入<?= $setting('order.titleReceive') ?: '收货' ?>人手机号码">
              </div>
            </div>

            <div class="js-form-group-id-card form-group hide">
              <label for="idCard" class="control-label">
                身份证
                <span class="text-warning">*</span>
              </label>

              <div class="col-control">
                <input type="text" class="form-control" id="idCard" name="idCard" placeholder="请输入收货人身份证"
                  value="">
              </div>
            </div>

            <?php require $view->getFile('@address/addresses/edit-area.php') ?>

            <div class="js-form-group-address form-group">
              <label for="address" class="control-label">
                详细地址
                <span class="text-warning">*</span>
              </label>

              <div class="col-control">
                <textarea class="form-control" id="address" name="address" rows="2" placeholder="请输入详细地址"></textarea>
              </div>
            </div>

            <div class="js-form-group-default form-group">
              <div class="col-control">
                <div class="checkbox checkbox-circle checkbox-success checkbox-sm">
                  <label>
                    <input class="js-address-default" id="defaultAddress" type="checkbox" name="defaultAddress"
                      value="1">
                    <span class="checkbox-label">设为默认</span>
                  </label>
                </div>
              </div>
            </div>

          </div>
          <div class="modal-footer modal-footer-fluid flex">
            <input type="hidden" name="id" id="id">
            <input type="hidden" class="js-area-id" name="areaId" id="areaId">
            <input type="hidden" name="zipcode" id="zipcode">
            <button type="submit" class="btn btn-primary btn-fluid flex-grow-1">保存</button>
            <% if (address.id) { %>
            <button type="button" class="js-address-destroy btn btn-danger btn-fluid flex-grow-1"
              data-id="<%= address.id %>">删除
            </button>
            <% } %>
          </div>
        </form>
      </div>
    </div>
  </div>
</script>

<?php $event->trigger('addressManagerRender') ?>
