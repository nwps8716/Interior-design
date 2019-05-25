@extends('home')

@section('feature')
<div>
  <h1 class='content-title'>裝潢工程預算表</h1>
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
            {{$name}}級裝潢工程預算
          </option>
        @endforeach
      </select>
      <input type="submit" name="search" id="search" class="search btn btn-primary" value="查詢"/>
    </div>
  </div>
</div>
@endsection

@section('content')
<!-- 裝潢工程預算列表 -->
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped">
      <span>
        <thead>
          <tr class="">
            <td>{{$spacing[$budget_id]}}級裝潢工程總預算</td>
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
    @foreach($engineering as $id => $projectname)
      <h4 class='project-title'>{{$projectname}}</h4>
      <table class="table table-striped">
        <thead>
          <tr class="subproject-title">
            <td >項目</td>
            <td >單價</td>
            <td >數量</td>
            <td >小記</td>
            <td >尚餘空間</td>
            <td >單位</td>
            <td >備註</td>
          </tr>
        </thead>
        @foreach($list[$id] as $sub_name)
          <tbody>
            <td class="project-name">{{$sub_name['sub_project_name']}}</td>
            <td class="unti_price">{{$sub_name['unti_price']}}</td>
            <td class="number">{{$sub_name['number']}}</td>
            <td class="sub_total">
              {{number_format($sub_name['unti_price'] * $sub_name['number'], 2)}}
            </td>
            <td class="free_space">
              @if(number_format($total_info['remaining_money'] / $sub_name['unti_price'], 2) < 0)
                <span style="color:red">
                  {{number_format($total_info['remaining_money'] / $sub_name['unti_price'], 2)}}
                </span>
              @else
                {{number_format($total_info['remaining_money'] / $sub_name['unti_price'], 2)}}
              @endif
            </td>
            <td class="unti">{{$sub_name['unti']}}</td>
            <td class="remark">{{$sub_name['remark']}}</td>
          </tbody>
        @endforeach
      </table><br>
    @endforeach
  <div>
</div>
@endsection

@section('script')
@parent
<script>
  // 查詢其他級距工程預算表
  $('.search-budget').on('click', '.search', function() {
    var budgetID = $(".budget").val();
    window.location.href = window.location.origin + '/engineering/budget?budget=' + budgetID;
  });
</script>
@endsection

@section('style')
@parent
<style>
.search {
  margin: 0px 0px 0px 10px;
}
.table th, .table td {
  border: 1px solid #dee2e6;
}
.num-td {
  background-color: #FFFFBF;
}
.project-title {
  color: white;
  background-color: #2d76be;
  padding: 10px;
  text-align: center;
  margin-bottom: 0px;
}
.subproject-title {
  background-color: #a6c7e8;
}
.project-name {
  width: 40%;
}
.unti_price {
  width: 10%;
}
.number {
  width: 8%;
}
.sub_total {
  width: 10%;
}
.free_space {
  width: 12%;
}
.unti {
  width: 8%;
}
</style>
@endsection