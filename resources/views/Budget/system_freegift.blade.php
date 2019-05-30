@extends('home')

@section('feature')
<div>
  <h1 class='content-title'>好禮贈送</h1>
  <div class="search-level">
    <div class="form-group">
      <label class="control-label">級距:</label>
      <select class="level">
        @foreach($spacing as $id => $name)
          <option
            value="{{$id}}"
            @if ($id == $level_id)
              selected="selected"
            @endif>
            {{$name}}級系統工程 - 好禮贈送
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
<!-- 系統工程預 - 好禮贈送 -->
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped">
      <span>
        <thead>
          <tr class="">
            <td>{{$spacing[$level_id]}}級系統工程 - 好禮贈送</td>
            <td class="num-td">${{number_format($total_info['total'], 2)}}</td>
            <td>小記</td>
            <td class="num-td">${{number_format($total_info['sub_total'], 2)}}</td>
            <td>剩餘好禮金額</td>
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
  </div>
</div>
@endsection

@section('script')
@parent
<script>
  // 查詢其他級距工程預算表
  $('.search-level').on('click', '.search', function() {
    var levelID = $(".level").val();
    window.location.href = window.location.origin + '/system/free_gift?level_id=' + levelID;
  });
</script>
@endsection

@section('style')
@parent
<style>
.search {
  margin: 0px 0px 0px 10px;
}
.reset-budget {
  float:right;
}
.table th, .table td {
  border: 1px solid #dee2e6;
}
.num-td {
  background-color: #FFFFBF;
}
</style>
@endsection