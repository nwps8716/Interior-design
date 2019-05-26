@extends('home')

@section('feature')
  <div class="pings-title">
    <h1 class='content-title'>坪數估價</h1>
    <button id="edit-enginner" type="button" class="system-btn btn btn-primary" data-toggle="modal" data-target="#exampleModal">工程、系統預算修改</button>
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
        <th>系統售價</th>
        <th>系統牌價</th>
        <th>系統折數</th>
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
  <!-- Dialog Modal -->
  <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">修改工程預算、系統預算％數</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div style="margin-bottom: 6px;">工程預算: <input id="engineering_budget" value="{{ $engineering_budget }}" type="number" min="0" max="100"/> %</div>
          <div>系統預算: <input id="system_budget" value="{{ $system_budget }}" type="number" min="0" max="100"/> %</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
          <button id="edit-confirm" type="button" class="btn btn-primary">確定修改</button>
        </div>
      </div>
    </div>
  <div>
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
  $('#pings-number').change(function (e) {
    if (this.value > 10000) {
      this.value = 10000;
    } else if (this.value < 1) {
      this.value = 1;
    }
    setTimeout(function () {
      window.location.href = window.location.origin + '/pings?pings=' + $("#pings-number").val();
    }, 100);
  });
  // 修改工程預算、系統預算
  $('#edit-confirm').on('click', function() {
    $.ajax({
      type: 'put',
      url: '/pings/percent',
      data: {
        'engineering_budget': $("#engineering_budget").val(),
        'system_budget': $('#system_budget').val(),
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
</script>
@endsection

@section('style')
@parent
<style>
  .pings-title {
    position: relative;
  }
  .system-btn {
    position: absolute;
    top: 13px;
    left: 160px;
  }
  .pings-table,
  .level {
    text-align: center;
  }
  tbody {
    text-align: right;
  }
  .pings-td {
    vertical-align: middle !important;
    font-size: 24px;
    text-align: center;
  }
  input {
    border-radius: 6px;
  }
</style>
@endsection

