@extends('home')

@section('feature')
  <div>
    <h1 class='content-title'>系統工程預算表</h1>
    <div class="search-budget">
      <div class="form-group">
        <label class="control-label">級距:</label>
        <select class="budget">
          @foreach($spacing as $id => $name)
            <option
              value="{{$id}}"
              @if ($id == $budget_id)
                selected="selected"
              @endif>
              {{$name}}級系統工程預算
            </option>
          @endforeach
        </select>
        <input type="submit" name="search" id="search" class="search btn btn-primary" value="查詢"/>
        <button
          id="reset-budget"
          class="reset-budget btn btn-danger"
          data-toggle="modal"
          data-target="#resetBudget">
            還原設定
        </button>
      </div>
    </div>
  </div>
@endsection

@section('content')
<!-- 系統工程預算列表 -->
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped">
      <span>
        <thead>
          <tr class="">
            <td>{{$spacing[$budget_id]}}級系統工程總預算</td>
            <td class="num-td">${{number_format($total_info['total'], 2)}}</td>
            <td>小記</td>
            <td class="num-td">${{number_format($total_info['sub_total'], 2)}}</td>
            <td>剩餘預算</td>
            <td class="num-td">
              @if($total_info['remaining_money'] < 0)
                <span style="color:red">
                  ${{number_format($total_info['remaining_money'], 2)}}
                </span>
              @else
                ${{number_format($total_info['remaining_money'], 2)}}
              @endif
            </td>
          </tr>
        </thead>
      </span>
    </table>
    @foreach($system as $id => $systemname)
      <h4 class='system-list-title'>{{$systemname}}</h4>
      <table class="table table-bordered table-striped">
          <span>
            <thead>
              <tr class="subsystem-list-title">
                <td>統稱</td>
                <td>內容物</td>
                <td>規格</td>
                <td>單價</td>
                <td>單位</td>
                <td>數量</td>
                <td>小記</td>
                <td>尚餘空間</td>
              </tr>
            </thead>
            <tbody>
              @foreach($sub_system[$id] as $general)
                @foreach($general as $key => $sub_name)
                  <tr>
                    @if (count($general) > 1 && $key === 0)
                      <td rowspan="{{count($general)}}" class="general_name">{{$sub_name['general_name']}}</td>
                    @elseif (count($general) <= 1)
                      <td class="general_name">{{$sub_name['general_name']}}</td>
                    @endif
                    <td class="system_name">{{$sub_name['sub_system_name']}}</td>
                    <td class="format">{{$sub_name['format']}}</td>
                    <td class="unit_price">{{$sub_name['unit_price']}}</td>
                    <td class="unit">{{$sub_name['unit']}}</td>
                    <td class="number">
                      <input
                        type="number"
                        class="subsystem-num"
                        id="{{$sub_name['sub_system_id']}}"
                        value="{{$sub_name['number']}}"
                        min="0"
                        max="999">
                    </td>
                    <td class="sub_total-td">
                      {{number_format($sub_name['unit_price'] * $sub_name['number'], 2)}}
                    </td>
                    <td class="free_space-td">
                      @if(number_format($total_info['remaining_money'] / $sub_name['unit_price'], 2) < 0)
                        <span style="color:red">
                          {{number_format($total_info['remaining_money'] / $sub_name['unit_price'], 2)}}
                        </span>
                      @else
                        {{number_format($total_info['remaining_money'] / $sub_name['unit_price'], 2)}}
                      @endif
                    </td>
                  </tr>
                @endforeach
              @endforeach
            </tbody>
        </span>
      </table><br>
      @endforeach
  <div>
</div>
@endsection

@section('script')
@parent
<script>
</script>
@endsection

@section('style')
@parent
<style>
.reset-budget {
  float:right;
}
.table th, .table td {
  border: 1px solid #dee2e6;
}
.num-td {
  background-color: #FFFFBF;
}
.system-list-title {
  color: white;
  background-color: #2d76be;
  padding: 10px;
  text-align: center;
  margin-bottom: 0px;
}
.subsystem-list-title {
  background-color: #a6c7e8;
}
/* 去除webkit中input的type="number"時出現的上下圖標 */
.subsystem-num input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
}
.subsystem-num {
  border-radius: 6px;
  width: 60px;
}
</style>
@endsection