define(['comps/artTemplate/template.min', 'comps/jquery-cascading/jquery-cascading'], function (template) {
  var Addresses = function () {
    // do nothing.
  };

  $.extend(Addresses.prototype, {
    indexAction: function (options) {
      var picker = new AddressPicker();
      picker.render(options);
    },

    showPicker: function (options) {
      var picker = new AddressPicker();
      options.picker = true;
      picker.render(options);
    }
  });

  /**
   * 下单时的地址选择器
   */
  var AddressPicker = function () {
    // do nothing.
  };

  $.extend(AddressPicker.prototype, {
    $el: null,

    /**
     * 地址列表
     */
    $list: null,

    /**
     * 添加/编辑表单
     */
    $form: null,

    /**
     * 是否为选择器模式
     */
    picker: false,

    /**
     * 选中的地址
     */
    selectedId: 0,

    /**
     * 当前的地址数据
     */
    data: [],

    /**
     * 后台返回的地址列表
     */
    addresses: [],

    /**
     * 关闭表单后,是否要重新刷新列表
     */
    reload: false,

    $: function (selector) {
      return this.$el.find(selector);
    },

    /**
     * 根据配置选择组件
     */
    render: function (options) {
      $.extend(this, options);

      template.helper('$', $);

      if (this.picker) {
        this.renderPicker();
      } else {
        this.showList();
      }
    },

    /**
     * 渲染选择器
     */
    renderPicker: function () {
      this.$el.html(template.render('addressPickerTpl', {
        address: this.data
      }));

      // 点击元素,弹出列表供选择
      var that = this;
      this.$el.click(function () {
        that.showList();
      });
    },

    /**
     * 加载列表数据
     */
    loadList: function () {
      var that = this;
      var tpl = this.picker ? 'addressPickerModalTpl' : 'addressListMangerTpl';

      $.ajax({
        url: $.url('addresses.json'),
        loading: true,
        dataType: 'json'
      }).done(function (ret) {
        if (ret.code !== 1) {
          $.msg(ret);
          return;
        }

        that.addresses = ret.data;
        that.$list = $(template.render(tpl, {
          data: ret.data,
          selectedId: that.data.id
        }));
        $(document).trigger('address:renderList', [that]);
        that.$list.appendTo('body');
        that.showList();
        that.bindEvents();

        // 如果是选择器模式,且没有记录,自动弹出新增地址表单
        if (that.picker && ret.data.length === 0) {
          that.showNewForm();
        }
      });
    },

    /**
     * 展示地址列表
     */
    showList: function () {
      if (!this.$list) {
        this.loadList();
        return;
      }

      if (this.picker) {
        this.$list.modal('show');
      } else {
        this.$list.show();
      }
    },

    /**
     * 隐藏地址列表
     */
    hideList: function () {
      if (this.picker) {
        this.$list.modal('hide');
      } else {
        this.$list.hide();
      }
    },

    /**
     * 重新加载地址列表
     */
    reloadList: function () {
      this.$list.remove();
      this.$list = null;
      this.showList();
    },

    /**
     * 绑定各类事件
     */
    bindEvents: function () {
      this.bindListEvents();
      if (this.picker) {
        this.bindPickerEvents();
      }
    },

    /**
     * 绑定列表的事件
     */
    bindListEvents: function () {
      var that = this;

      // 新增地址
      this.$list.on('click', '.js-address-new', $.proxy(this.showNewForm, this));

      // 编辑地址
      this.$list.on('click', '.js-address-edit', function () {
        var id = $(this).closest('.js-address-item').data('id');
        var address = that.addresses[id];
        that.hideList();
        that.showForm(address);
      });
    },

    /**
     * 绑定选择器的事件
     */
    bindPickerEvents: function () {
      var that = this;

      // 选择地址,更新到目标位置
      this.$list.on('click', '.js-address-select', function () {
        that.select(this);
      });
    },

    /**
     * 选择某项地址
     */
    select: function (el) {
      var $select = $(el);
      var $item = $select.closest('.js-address-item');
      var data = this.addresses[$item.data('id')];

      var event = $.Event('address:beforeSelect');
      $(document).trigger(event, [this, data]);
      if (event.isDefaultPrevented()) {
        return;
      }

      $select.closest('.js-address-list').find('.js-address-selected-icon').removeClass('bm-ok');
      $item.find('.js-address-selected-icon').addClass('bm-ok');

      this.data = data;
      this.renderPicker();
      this.hideList();

      $(document).trigger('address:select', [this]);
    },

    /**
     * 展示新增地址的表单
     */
    showNewForm: function () {
      // 读取默认资料
      var that = this;
      $.ajax({
        url: $.url('addresses/new.json'),
        loading: true,
        dataType: 'json',
        success: function (ret) {
          that.hideList();
          that.showForm(ret.code === 1 ? ret.data : null);
        }
      });
    },

    /**
     * 展示表单
     */
    showForm: function (address) {
      // 1. 渲染模板
      address = address || {};
      var $form = this.$form = $(template.render('addressFormModalTpl', {
        address: address
      }));

      $(document).trigger('address:renderForm', [this, $form]);

      // 2. 加载数据
      if (address) {
        $form.loadJSON(address);
        // TODO #989
        if (address.defaultAddress !== '1') {
          $form.find('.js-address-default').prop('checked', false);
        }
      }

      $form.find('.js-cascading-item').cascading({
        url: $.url('areas.json'),
        valueKey: 'label',
        valueDataKey: 'value',
        values: [address.province, address.city, address.area]
      });

      // 3. 显示表单
      $form.appendTo('body').modal('show');

      // 4. 绑定事件
      this.bindFormEvents();
    },

    /**
     * 隐藏表单
     */
    hideForm: function () {
      this.$form.modal('hide');
    },

    /**
     * 隐藏表单并重新加载列表
     */
    hideFormAndReloadList: function () {
      this.reload = true;
      this.hideForm();
    },

    /**
     * 绑定表单事件
     */
    bindFormEvents: function () {
      var $form = this.$form;

      // 记录地区编号
      $form.find('.js-area').change(function () {
        $form.find('.js-area-id').val($(this).find(':selected').data('value'));
      });

      var that = this;
      $form.ajaxForm({
        url: $.url('addresses/update'),
        dataType: 'json',
        success: function (ret) {
          $.msg(ret, function () {
            if (ret.code === 1) {
              that.hideFormAndReloadList();
            }
          });
        }
      });

      // 隐藏表单并显示列表
      $form.on('hidden.bs.modal', function () {
        if (that.reload) {
          that.reload = false;
          that.reloadList();
        } else {
          that.showList();
        }
      });

      // 点击删除
      $form.find('.js-address-destroy').click(function () {
        var id = $(this).data('id');
        $.confirm('确认删除', function (result) {
          if (!result) {
            return;
          }
          $.ajax({
            url: $.url('addresses/%s/destroy', id),
            type: 'post',
            dataType: 'json',
            success: function (ret) {
              $.msg(ret, function () {
                if (ret.code === 1) {
                  that.hideFormAndReloadList();
                }
              });
            }
          });
        });
      });
    }
  });

  return new Addresses();
});
