@extends('home')

@section('feature')
<div>
  <h1 class='content-title'>裝潢工程預算表</h1>
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
            {{$name}}裝潢工程預算
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
<!-- 裝潢工程預算列表 -->
<div class="row">
  <div class="col-sm-12">
    <table class="table table-striped">
      <span>
        <thead>
          <tr class="">
            <td>{{$spacing[$level_id]}}裝潢工程總預算</td>
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
            <td >備註</td>
            <td >項目</td>
            <td >單價</td>
            <td >數量</td>
            <td >小記</td>
            <td >尚餘空間</td>
            <td >單位</td>
          </tr>
        </thead>
        @foreach($list[$id] as $sub_name)
          <tbody>
            <td class="remark-td">{{$sub_name['remark']}}</td>
            <td class="project-name-td">{{$sub_name['sub_project_name']}}</td>
            <td class="unit_price-td">{{$sub_name['unit_price']}}</td>
            <td class="number-td">
              <input
                type="number"
                class="subproject-num"
                id="{{$sub_name['sub_project_id']}}"
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
            <td class="unit-td">{{$sub_name['unit']}}</td>
          </tbody>
        @endforeach
      </table><br>
    @endforeach
  <div>
</div>

<!-- 還原設定 -->
<div id="resetBudget" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="resetBudgetLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
      <h5 class="modal-title" class="resetBudgetLabel">{{$spacing[$level_id]}}裝潢工程預算 - 還原設定</h5>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">
        <div class="deleteContent">
          確定要還原 "{{$spacing[$level_id]}}裝潢工程預算 -> 數量" 設定嗎?
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
        <button id="resetBudget-btn" type="button" class="resetBudget btn btn-danger" data-dismiss="modal">確定還原</button>
      </div>
    </div>
  </div>
</div>
@endsection

@section('script')
@parent
<script>
  // 查詢其他級距工程預算表
  $('.search-level').on('click', '.search', function() {
    var levelID = $(".level").val();
    window.location.href = window.location.origin + '/engineering/budget?level_id=' + levelID;
  });
  // 更改數量
  $('.subproject-num').change(function (event) {
    event.preventDefault();
    var subproject_number = event.target.value;
    var subproject_id = event.target.id;
    var level_id = $(".level").val();
    var default_value = event.target.defaultValue;
    if (subproject_number == '' || subproject_number < 0) {
      swal({
        title: "Error",
        text: "請輸入有效的數字",
        icon: "error",
        buttons: false,
        timer: 1500,
      })
      .then(() => {
        event.target.value = event.target.defaultValue;
      });
    } else if (subproject_number !== default_value) {
      $.ajax({
        type: 'put',
        url: '/user/budget/' + level_id,
        data: {
          'sub_project_id': subproject_id,
          'sub_project_number': subproject_number,
          'category_id': 1
        },
        success: function(resp) {
          if (resp.result === false) {
            swal({
              title: "Error",
              text: "修改失敗！",
              icon: "error",
              buttons: false,
              timer: 1500,
            });
          } else {
            location.reload(true);
          }
        }
      });
    }
  });
  // 還原使用者該級距的數量設定
  $('#resetBudget-btn').on('click', function() {
    var level_id = $(".level").val();
    $.ajax({
        type: 'delete',
        url: '/user/budget/' + level_id,
        data: {
          'category_id': 1
        },
        success: function(resp) {
          if (resp.result === true) {
            swal({
              title: "Success",
              text: "還原成功！",
              icon: "success",
              buttons: false,
              timer: 1500,
            })
            .then(() => {
              location.reload(true);
            });
          }
        }
      });
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
  float: right;
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
.remark-td {
  width: 8%;
}
.project-name-td {
  width: 25%;
}
.unit_price-td {
  width: 1%;
}
.number-td {
  width: 1%;
}
.sub_total-td {
  width: 7%;
}
.free_space-td {
  width: 7%;
}
.unit-td {
  width: 5%;
}
/* 去除webkit中input的type="number"時出現的上下圖標 */
.subproject-num input::-webkit-outer-spin-button,
input::-webkit-inner-spin-button {
  -webkit-appearance: none;
}
.subproject-num {
  border-radius: 6px;
  width: 60px;
}
</style>
@endsection