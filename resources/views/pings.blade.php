@extends('home')

@section('feature')
  <div class="pings-title">
    <h1 class='content-title'>坪數估價</h1>
    <button id="pings-set" type="button" class="pings-set-btn btn btn-primary">儲存當前試算坪數估價表</button>
    @if(Session::get('login_user_info')['level'] < 3)
      <button
        type="button"
        id="edit-enginner"
        class="system-btn btn btn-primary"
        data-toggle="modal"
        data-target="#exampleModal">
          級距坪數價格、預算％數設定
      </button>
    @endif
  </div>
  <table class="table table-bordered table-striped pings-table">
    <thead class="thead-dark">
      <tr>
        <th>實內坪數</th>
        <th>坪數</th>
        <th>等級</th>
        <th>錢/每坪</th>
        <th>價錢</th>
        <th>工程預算%</th>
        <th>工程總預算</th>
        <th>系統預算%</th>
        <th>系統牌價</th>
        <th>系統折數</th>
        <th>系統售價</th>
      </tr>
    </thead>
    <tbody>
      @foreach($main_data as $id => $sub_data)
        <tr>
          @if($id === 1)
            <td rowspan="11" class="pings-td">
              <input id="pings-number" type="number" value="{{ $default_pings }}" min="1" max="10000"/>
            </td>
          @endif
          @foreach($sub_data as $name => $detail_data)
            <td class="{{$name}}">{{ $detail_data }}</td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>
  <!-- 級距坪數價格、預算％數設定 -->
  <div id="exampleModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">級距坪數價格、預算％數設定</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <div style="margin-bottom: 6px;">工程預算: <input id="engineering_budget" value="{{ $engineering_budget }}" type="number" min="0" max="100"/> %</div>
          <div>系統預算: <input name="system_budget" id="system_budget" value="{{ $system_budget }}" type="number" min="0" max="100"/> %</div>
          <hr>
          @foreach($level_data as $level => $level_detail)
            <div>
              {{ $level_detail['level_name'] }}:
              <input class="level-input" id="level_{{$level}}" value="{{ $level_detail['pings_price'] }}" type="number"/>
            </div>
          @endforeach
          <hr>
          <div>系統折數開關:
            @if($system_discount_switch === 1)
              <input checked="" id="system-discount-btn"" type="checkbox"/>
            @else
              <input id="system-discount-btn"" type="checkbox"/>
            @endif
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
          <button id="edit-confirm" type="button" class="btn btn-primary">確定修改</button>
        </div>
      </div>
    </div>
  </div>
  <hr>
@endsection
<!-- 特殊估價表格 -->
@section('content')
<div class="total-title">
  <h4 class='content-title'>特殊估價</h4>
  <button id="special-set" type="button" class="special-set-btn btn btn-primary">儲存當前試算特殊估價表</button>
</div>
<table class="table table-bordered table-striped total-table">
  <thead class="thead-dark">
    <tr>
      <th>總預算</th>
      <th>工程預算%</th>
      <th>工程總預算</th>
      <th>系統預算%</th>
      <th>系統牌價</th>
      <th>系統折數</th>
      <th>系統售價</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="total-budget-td">
        <input id="total-number" type="number" value="{{ $special_data['total_budget'] }}" min="0"/> 萬
      </td>
      <td class="engineering-budget-td">
        <input id="eb-number" type="number" value="{{ $special_data['engineering_budget'] }}" min="1" max="100"/>
      </td>
      <td class="system_card_price">{{ number_format($special_data['engineering_total_budget'], 2) }}</td>
      <td class="system-budget-td">
        <input id="sb-number" type="number" value="{{ $special_data['system_budget'] }}" min="1" max="100"/>
      </td>
      <td class="system_card_price">{{ number_format($special_data['system_card_price'], 2) }}</td>
      @if ($system_discount_switch === 1)
        <td class="system-discount-td">
          <input id="discount-number" type="number" step="0.01" value="{{ $special_data['system_discount'] }}"  min="0.60" max="1"/>
        </td>
      @else
        <td class="system_discount">
          {{ $special_data['system_discount'] }}
          <input id="discount-number" type="hidden" value="{{ $special_data['system_discount'] }}"/>
        </td>
      @endif
      <td class="system_price">{{ number_format($special_data['system_price'], 2) }}</td>
    </tr>
  </tbody>
</table>
@endsection

@section('script')
@parent
<script>
  $('#edit-enginner').on('click', function() {
    $('#engineering_budget').val({!! json_encode($engineering_budget) !!});
    $('#system_budget').val({!! json_encode($system_budget) !!});
  });
  $('#engineering_budget').keyup(function (e) {
    this.value = this.value.replace(/[^0-9\.-]/g, '');
  });
  $('#engineering_budget').change(function (e) {
    if (this.value <= 100 && this.value >= 0) {
      $('#system_budget').val(100 - this.value);
    } else {
      $('#engineering_budget').val({!! json_encode($engineering_budget) !!});
      alert('請輸入有效數字');
    }
  });
  $('#system_budget').keyup(function (e) {
    this.value = this.value.replace(/[^0-9\.-]/g, '');
  });
  $('#system_budget').change(function (e) {
    if (this.value <= 100 && this.value >= 0) {
      $('#engineering_budget').val(100 - this.value);
    } else {
      $('#system_budget').val({!! json_encode($system_budget) !!});
      alert('請輸入有效數字');
    }
  });
  $('#pings-number').keyup(function (e) {
    this.value = this.value.replace(/[^0-9\.-]/g, '');
  });
  // 試算坪數
  $('#pings-number').change(function (e) {
    if (this.value > 10000) {
      this.value = 10000;
    } else if (this.value < 1) {
      this.value = 1;
    }
    TrialAmount(
      $("#pings-number").val(),
      $("#total-number").val(),
      $("#eb-number").val(),
      $("#sb-number").val(),
      $("#discount-number").val()
    );
  });
  // 試算特殊總預算
  $('#total-number').change(function (event) {
    event.preventDefault();
    if (this.value <= 0 || this.value == '') {
      swal({
        title: "Error",
        text: "請輸入有效的總預算",
        icon: "error",
        buttons: false,
        timer: 1500,
      })
      .then(() => {
        this.value = event.target.defaultValue;
      });
    } else {
      TrialAmount(
        $("#pings-number").val(),
        $("#total-number").val(),
        $("#eb-number").val(),
        $("#sb-number").val(),
        $("#discount-number").val()
      );
    }
  });
  // 試算特殊工程售價
  $('#eb-number').change(function (event) {
    event.preventDefault();
    if (this.value < 0 || this.value > 100 || this.value == '') {
      swal({
        title: "Error",
        text: "請輸入有效的%數",
        icon: "error",
        buttons: false,
        timer: 1500,
      })
      .then(() => {
        this.value = event.target.defaultValue;
      });
    } else {
      $('#sb-number').val(100 - this.value);
      TrialAmount(
        $("#pings-number").val(),
        $("#total-number").val(),
        $("#eb-number").val(),
        $("#sb-number").val(),
        $("#discount-number").val()
      );
    }
  });
  // 試算特殊系統售價
  $('#sb-number').change(function (event) {
    event.preventDefault();
    if (this.value < 0 || this.value > 100 || this.value == '') {
      swal({
        title: "Error",
        text: "請輸入有效的%數",
        icon: "error",
        buttons: false,
        timer: 1500,
      })
      .then(() => {
        this.value = event.target.defaultValue;
      });
    } else {
      $('#eb-number').val(100 - this.value);
      TrialAmount(
        $("#pings-number").val(),
        $("#total-number").val(),
        $("#eb-number").val(),
        $("#sb-number").val(),
        $("#discount-number").val()
      );
    }
  });
  // 試算特殊系統折數
  $('#discount-number').change(function (event) {
    event.preventDefault();
    if (((this.value < 0.6 || this.value > 1) && this.value > 0) || this.value < 0 || this.value == '') {
      swal({
        title: "Error",
        text: "請輸入有效的折數",
        icon: "error",
        buttons: false,
        timer: 1500,
      })
      .then(() => {
        this.value = event.target.defaultValue;
      });
    } else {
      TrialAmount(
        $("#pings-number").val(),
        $("#total-number").val(),
        $("#eb-number").val(),
        $("#sb-number").val(),
        $("#discount-number").val()
      );
    }
  });
  // 試算資料
  function TrialAmount (pings, total_budget, e_budget, s_budget, discount) {
    var data = '?';
    data = data + 'total_budget=' + total_budget;
    data = data + '&e_budget=' + e_budget;
    data = data + '&s_budget=' + s_budget;
    data = data + '&system_discount=' + discount;
    setTimeout(function () {
      window.location.href = window.location.origin + '/pings/' + pings + '/trial/amount' + data;
    }, 100);
  }
  // 修改工程預算、系統預算
  $('#edit-confirm').on('click', function() {
    var level_price = [];
    var system_discount_switch = 0;
    for (var i = 1; i < 7; i += 1) {
      level_price[i] = $('#level_' + i).val();
    }
    if ($("#system-discount-btn:checked").val() === 'on') {
      system_discount_switch = 1;
    }
    $.ajax({
      type: 'put',
      url: '/pings/percent',
      data: {
        'engineering_budget': $("#engineering_budget").val(),
        'system_budget': $('#system_budget').val(),
        'level_price': level_price,
        'system_discount_switch': system_discount_switch,
      },
      success: function(resp) {
        swal({
          title: "Success",
          text: "修改成功！",
          icon: "success",
          buttons: false,
          timer: 1500,
        })
        .then(() => {
          location.reload(true);
        });
      }
    });
  });
  // 儲存使用者當前試算坪數
  $('#pings-set').on('click', function() {
    var pings_num = $("#pings-number").val();
    if (pings_num === '') {
      swal({
        title: "Error",
        text: "請輸入有效的數字",
        icon: "error",
        buttons: false,
        timer: 1500,
      });
    } else {
      $.ajax({
        type: 'put',
        url: '/user/pings',
        data: {
          'pings': pings_num
        },
        success: function(resp) {
          swal({
            title: "Success",
            text: "設定成功！",
            icon: "success",
            buttons: false,
            timer: 1500,
          })
          .then(() => {
            setTimeout(function () {
              window.location.href = window.location.origin + '/pings';
            }, 100);
          });
        }
      });
    }
  });
  // 儲存使用者當前試算特殊估價表
  $('#special-set').on('click', function() {
    $.ajax({
      type: 'put',
      url: '/user/total/budget',
      data: {
        'total_budget': $("#total-number").val(),
        'e_budget': $("#eb-number").val(),
        's_budget': $("#sb-number").val(),
        'system_discount': $("#discount-number").val()
      },
      success: function(resp) {
        swal({
          title: "Success",
          text: "設定成功！",
          icon: "success",
          buttons: false,
          timer: 1500,
        })
        .then(() => {
          setTimeout(function () {
            window.location.href = window.location.origin + '/pings';
          }, 100);
        });
      }
    });
  });
</script>
@endsection

@section('style')
@parent
<style>
  .pings-title, .total-title {
    position: relative;
  }
  .system-btn {
    position: absolute;
    top: 13px;
    left: 356px;
  }
  .pings-set-btn {
    position: absolute;
    top: 13px;
    left: 160px;
  }
  .special-set-btn {
    position: absolute;
    top: 4px;
    left: 100px;
  }
  .total-table,
  .pings-table,
  .level {
    text-align: center;
  }
  tbody {
    text-align: right;
  }
  .pings-td,
  .total-budget-td,
  .engineering-budget-td,
  .system-budget-td,
  .system-discount-td {
    vertical-align: middle !important;
    font-size: 20px;
    text-align: center;
  }
  input {
    border-radius: 6px;
  }
  .level-input{
    margin-top: 6px;
  }
  .table td {
    vertical-align: inherit;
  }
  .total-budget-td {
    width: 20%;
  }
  .engineering-budget-td,
  .system-budget-td,
  .system-discount-td,
  .system_discount {
    width: 10%;
  }
  .engineering_total_budget,
  .system_card_price {
    width: 16%;
  }
  #total-number {
    width: 70%;
  }
</style>
@endsection

