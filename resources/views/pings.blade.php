@extends('home')

@section('feature')
  <div class="pings-title">
    <h1 class='content-title'>坪數估價</h1>
    <button class="system-btn btn btn-primary">工程、系統預算修改</button>
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
@endsection

@section('script')
@parent
<script>
  $('#pings-number').keyup(function (e) {
    this.value = this.value.replace(/[^0-9\.-]/g, '');
  });
  $('#pings-number').change(function (e) {
    // var url = window.location.href;
    if (this.value > 10000) {
      this.value = 10000;
    } else if (this.value < 1) {
      this.value = 1;
    }
    window.location.href = window.location.origin + '/pings?pings=' + this.value;
    // console.log(url);
    // setTimeout(function () {
    //   console.log(123);
    // }, 100);
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
</style>
@endsection

