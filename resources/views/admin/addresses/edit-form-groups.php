<div class="form-group">
  <label for="receiver-name" class="col-sm-3 control-label">收货人</label>

  <div class="col-sm-8">
    <input type="text" class="form-control" id="receiver-name" name="receiverName" placeholder="没有请留空">
  </div>
</div>

<div class="js-form-group-receiver-mobile form-group">
  <label for="receiver-mobile" class="col-sm-3 control-label">手机号码</label>

  <div class="col-sm-8">
    <input type="text" class="form-control" id="receiver-mobile" name="receiverMobile" placeholder="没有请留空">
  </div>
</div>

<div class="form-group">
  <label for="receiver-id-card" class="col-sm-3 control-label">身份证</label>

  <div class="col-sm-8">
    <input type="text" class="form-control" id="receiver-id-card" name="receiverIdCard" placeholder="没有请留空">
  </div>
</div>

<div class="form-group">
  <label class="col-lg-2 offset-md-1 control-label" for="province">
    省市区
  </label>

  <div class="col-lg-8">
    <div class="form-row">
      <div class="col">
        <select class="js-cascading-item js-province province form-control" id="receiver-province" name="receiverProvince">
        </select>
      </div>

      <div class="col">
        <select class="js-cascading-item js-city city form-control" id="receiver-city" name="receiverCity">
        </select>
      </div>

      <div class="col">
        <select class="js-cascading-item js-area area form-control" id="receiver-area" name="receiverArea">
        </select>
      </div>
    </div>
  </div>
</div>

<div class="form-group">
  <label for="receiver-address" class="col-sm-3 control-label">地址</label>

  <div class="col-sm-8">
    <input type="text" class="form-control" id="receiver-address" name="receiverAddress" placeholder="没有请留空">
  </div>
</div>

<div class="form-group">
  <label for="receiver-post-code" class="col-sm-3 control-label">邮政编号</label>

  <div class="col-sm-8">
    <input type="text" class="form-control" id="receiver-post-code" name="receiverPostCode" placeholder="没有请留空">
  </div>
</div>

<input type="hidden" class="form-control" id="id" name="id">
