@extends('home')

@section('feature')
  <div class='system-title'>
    <h1 class='content-title'>系統單價列表</h1>
    <button
      id="add-system"
      class="add-system btn btn-primary"
      data-toggle="modal"
      data-target="#addSystem">
        新增系統分類
    </button>
    <button
      id="add-subsystem"
      class="add-sub-system btn btn-primary"
      data-toggle="modal"
      data-target="#addSubSystem">
        新增系統子項目
    </button>
  </div>
  <!-- 系統單價列表 -->
  <div class="row system-list">
    <div class="col-sm-12">
      @foreach($system as $id => $systemname)
      <h4 class='system-list-title'>{{$systemname}}</h4>
      <table class="table table-striped">
          <span>
            <thead>
              <tr class="subsystem-list-title">
                <td>內容物</td>
                <td>規格</td>
                <td>單價</td>
                <td>單位</td>
                <td colspan = 2>Actions</td>
              </tr>
            </thead>
            @foreach($sub_system[$id] as $sub_name)
              <tbody>
                <td class="system-name">{{$sub_name['sub_system_name']}}</td>
                <td class="format">{{$sub_name['format']}}</td>
                <td class="unit_price">{{$sub_name['unit_price']}}</td>
                <td class="unit">{{$sub_name['unit']}}</td>
                <td>
                  <button
                    id="edit-sub"
                    class="edit-modal btn btn-success"
                    data-id="{{$sub_name['sub_system_id']}}"
                    data-name="{{$sub_name['sub_system_name']}}"
                    data-format="{{$sub_name['format']}}"
                    data-unit="{{$sub_name['unit']}}"
                    data-unitprice="{{$sub_name['unit_price']}}"
                    data-systemname="{{$systemname}}"
                    data-toggle="modal"
                    data-target="#editSubSystem">
                      <span class="glyphicon glyphicon-edit">修改</span>
                  </button>
                  <button
                    id="delete-sub"
                    class="delete-modal btn btn-danger"
                    data-id="{{$sub_name['sub_system_id']}}"
                    data-name="{{$sub_name['sub_system_name']}}"
                    data-systemname="{{$systemname}}"
                    data-toggle="modal"
                    data-target="#delSubSystem">
                      <span class="glyphicon glyphicon-trash">刪除</span>
                  </button>
                </td>
              </tbody>
            @endforeach
        </span>
      </table><br>
      @endforeach
    <div>
  </div>
  <!-- 修改子項目Dialog -->
  <div id="editSubSystem" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="edit-subsystem-title"></h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form class="form-horizontal" role="form">
              <div class="form-group">
                <label class="control-label col-sm-4" for="sub_system_name">內容物:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="sub_system_name">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-4" for="edit-format">規格:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="edit-format">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2" for="unit_price">單價:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="unit_price">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-sm-2">單位:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="unit">
                </div>
              </div>
              <input type="hidden" class="form-control" id="edit-sub-id">
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">
              <span class='glyphicon glyphicon-remove'>取消</span>
            </button>
            <button type="button" class="btn actionBtn btn-success" data-dismiss="modal">
              <span id="editSubSystem-btn" class='glyphicon'>確定修改</span>
            </button>
          </div>
        </div>
    </div>
  </div>
  <!-- 刪除子項目 dialog -->
  <div id="delSubSystem" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="del-sub-title"></h5>
            <button type="button" class="close" data-dismiss="modal">&times;</button>
          </div>
          <div class="modal-body">
            <form class="form-horizontal" role="form">
              <div class="form-group">
                <label class="control-label" id="del-sub-content" for="system_name"></label>
              </div>
            </form>
          </div>
          <input type="hidden" class="form-control" id="del-sub-id">
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
            <button id="delSubSystem-btn" type="button" class="btn btn-danger">確定刪除</button>
          </div>
        </div>
    </div>
  </div>
  <!-- 新增系統單價分類 -->
  <div id="addSystem" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addSystemLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title addSystemLabel">新增系統單價分類</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" role="form">
                <div class="form-group">
                  <label class="control-label col-sm-4" for="system_name">系統分類名稱:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="system_name">
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
              <button id="addSystem-btn" type="button" class="addSystem btn btn-primary">確定新增</button>
            </div>
          </div>
      </div>
  </div>
  <!-- 新增系統單價 - 子項目 -->
  <div id="addSubSystem" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addSubSystemLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title addSubSystemLabel">新增系統單價 - 子項目</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" role="form">
                <div class="form-group">
                  <label class="control-label col-sm-10" for="project_name">請選擇要新增在哪個系統項目大類底下:</label>
                  <select class="select-system">
                    @foreach($system as $id => $systemname)
                      <option value="{{$id}}">{{$systemname}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-4" for="subsystem_name">內容物:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="subsystem_name">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-4" for="format">規格:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="format">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-4" for="unitprice">單價:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="unitprice">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-4" for="unitname">單位:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="unitname">
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
              <button id="addSubSystem-btn" type="button" class="addSubSystem btn btn-primary">確定新增</button>
            </div>
          </div>
      </div>
  </div>
@endsection

@section('content')
@endsection

@section('script')
@parent
<script>
  // 新增系統單價分類
  $('.modal-footer').on('click', '.addSystem', function() {
    $.ajax({
      type: 'post',
      url: '/system',
      data: {
        'system_name': $("#system_name").val()
      },
      success: function(resp) {
        swal({
          title: "Success",
          text: `新增系統項目 => ${$("#system_name").val()} 成功！`,
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
  // 新增系統單價 - 子項目
  $('.modal-footer').on('click', '.addSubSystem', function() {
    $.ajax({
      type: 'post',
      url: '/subsystem',
      data: {
        'system_id': $(".select-system").val(),
        'sub_system_name': $("#subsystem_name").val(),
        'format': $("#format").val(),
        'unit_price': $("#unitprice").val(),
        'unit': $("#unitname").val()
      },
      success: function(resp) {
        swal({
          title: "Success",
          text: `新增系統子項目 => ${$("#subsystem_name").val()} 成功！`,
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
  // 修改子項目 dialog 事件
  $('#edit-sub').on('click', function() {
    $('#edit-sub-id').val($(this).data('id'));
    $('#edit-subsystem-title').text('系統分類: ' + $(this).data('systemname'));
    $('#sub_system_name').val($(this).data('name'));
    $('#edit-format').val($(this).data('format'));
    $('#unit_price').val($(this).data('unitprice'));
    $('#unit').val($(this).data('unit'));
  });
  // 修改子項目
  $('#editSubSystem-btn').on('click', function() {
    console.log($('#unit').val());
    $.ajax({
      type: 'put',
      url: '/subsystem',
      data: {
        'id': $('#edit-sub-id').val(),
        'name': $('#sub_system_name').val(),
        'format': $('#edit-format').val(),
        'unit_price': $('#unit_price').val(),
        'unit': $('#unit').val()
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
  // 刪除子項目 dialog 事件
  $('#delete-sub').on('click', function() {
    $('#del-sub-title').text('系統分類: ' + $(this).data('systemname'));
    $('#del-sub-content').text('內容物: ' + $(this).data('name'));
    $('#del-sub-id').val($(this).data('id'));
  });
  // 刪除子項目
  $('#delSubSystem-btn').on('click', function() {
    $.ajax({
      type: 'delete',
      url: '/subsystem',
      data: {
        'id': $('#del-sub-id').val()
      },
      success: function(resp) {
        swal({
          title: "Success",
          text: "刪除成功！",
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
.system-title {
  position: relative;
}
.system-name {
  width: 35%;
}
.format {
  width: 25%;
}
.unit_price {
  width: 10%;
}
.unit {
  width: 10%;
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
.select-system {
  margin-left: 15px;
  width: 200px;
}
.add-system {
  position: absolute;
  top: 13px;
  left: 230px;
}
.add-sub-system {
  position: absolute;
  top: 13px;
  left: 354px;
}
</style>
@endsection