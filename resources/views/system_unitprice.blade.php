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
    <button
      id="system-set"
      class="system-set btn btn-success"
      data-toggle="modal"
      data-target="#systemSet">
        系統分類設定
    </button>
    <button
      id="system-delete"
      class="system-delete btn btn-danger"
      data-toggle="modal"
      data-target="#systemDelete">
        刪除系統分類
    </button>
  </div>
  <!-- 系統單價列表 -->
  <div class="row system-list">
    <div class="col-sm-12">
      @foreach($system as $id => $systemname)
      <h4 class='system-list-title'>{{$systemname}}</h4>
      <table class="table table-bordered table-striped">
          <span>
            <thead>
              <tr class="subsystem-list-title">
                <td>統稱</td>
                <td>備註</td>
                <td>內容物</td>
                <td>規格</td>
                <td>單價</td>
                <td>單位</td>
                <td colspan = 2>Actions</td>
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
                    <td class="remark">{{$sub_name['remark']}}</td>
                    <td class="system_name">{{$sub_name['sub_system_name']}}</td>
                    <td class="format">{{$sub_name['format']}}</td>
                    <td class="unit_price">{{$sub_name['unit_price']}}</td>
                    <td class="unit">{{$sub_name['unit']}}</td>
                    <td>
                      <button
                        id="edit-sub"
                        class="edit-modal btn btn-success"
                        data-id="{{$sub_name['sub_system_id']}}"
                        data-sid="{{$id}}"
                        data-name="{{$sub_name['sub_system_name']}}"
                        data-generalname="{{$sub_name['general_name']}}"
                        data-format="{{$sub_name['format']}}"
                        data-unit="{{$sub_name['unit']}}"
                        data-unitprice="{{$sub_name['unit_price']}}"
                        data-systemname="{{$systemname}}"
                        data-remark="{{$sub_name['remark']}}"
                        data-toggle="modal"
                        data-target="#editSubSystem">
                          <span class="glyphicon glyphicon-edit">修改</span>
                      </button>
                      <button
                        id="delete-sub"
                        class="delete-modal btn btn-danger"
                        data-id="{{$sub_name['sub_system_id']}}"
                        data-sid="{{$id}}"
                        data-name="{{$sub_name['sub_system_name']}}"
                        data-generalname="{{$sub_name['general_name']}}"
                        data-systemname="{{$systemname}}"
                        data-toggle="modal"
                        data-target="#delSubSystem">
                          <span class="glyphicon glyphicon-trash">刪除</span>
                      </button>
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

@section('content')
  <!-- 系統分類設定 -->
  <div id="systemSet" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">系統分類設定</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="edit-system-div">
                <div style="margin-bottom: 6px;">
                  選擇系統分類:
                  <select class="edit-system-select">
                    @foreach($system as $id => $systemname)
                      <option value="{{$id}}">{{$systemname}}</option>
                    @endforeach
                  </select>
                </div>
                <div style="margin-bottom: 6px;">
                  預修改名稱:
                  <input type="text" class="edit-system-name">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button id="edit-system-btn" type="button" class="btn btn-success">確定修改</button>
              </div>
            </div>
          </div>
      </div>
  </div>
  <!-- 刪除系統分類 -->
  <div id="systemDelete" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">刪除系統分類</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="delete-system-div">
                <div style="margin-bottom: 6px;">
                  選擇預刪除系統分類:
                  <select class="delete-system-select">
                    @foreach($system as $id => $systemname)
                      <option value="{{$id}}"><span class="delete-name">{{$systemname}}</span></option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button id="delete-system-btn" type="button" class="btn btn-danger">確認刪除</button>
              </div>
            </div>
          </div>
      </div>
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
                <label class="control-label col-sm-4" for="edit_general_name">統稱:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="edit_general_name">
                </div>
              </div>
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
              <div class="form-group">
                <label class="control-label col-sm-2">備註:</label>
                <div class="col-sm-10">
                  <input type="text" class="form-control" id="remark">
                </div>
              </div>
              <input type="hidden" class="form-control" id="edit-sub-id">
              <input type="hidden" class="form-control" id="edit-sid">
              <input type="hidden" class="form-control" id="edit_origin_general_name">
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
          <input type="hidden" class="form-control" id="del-sid">
          <input type="hidden" class="form-control" id="del-general_name">
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
                  <label class="control-label col-sm-10">請選擇要新增在哪個系統項目分類底下:</label>
                  <select class="select-system">
                    @foreach($system as $id => $systemname)
                      <option value="{{$id}}">{{$systemname}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-4">統稱:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="general_name">
                  </div>
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
                <div class="form-group">
                  <label class="control-label col-sm-2">備註:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="add-remark">
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

@section('script')
@parent
<script>
  // 修改系統分類項目
  $('#edit-system-btn').on('click', function() {
    $.ajax({
      type: 'put',
      url: '/system',
      data: {
        'id': $(".edit-system-select").val(),
        'name': $(".edit-system-name").val(),
      },
      success: function(resp) {
        swal({
          title: "Success",
          text: `修改成功！`,
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
  // 刪除系統分類項目
  $('#delete-system-btn').on('click', function() {
    $.ajax({
      type: 'delete',
      url: '/system',
      data: {
        'id': $(".delete-system-select").val()
      },
      success: function(resp) {
        if (resp.result === false) {
          swal({
            title: "Error",
            text: `該系統分類底下還有子項目，無法刪除！！`,
            icon: "error",
            buttons: false,
            timer: 2000,
          })
        } else {
          swal({
            title: "Success",
            text: `刪除成功！`,
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
        'general_name': $("#general_name").val(),
        'sub_system_name': $("#subsystem_name").val(),
        'format': $("#format").val(),
        'unit_price': $("#unitprice").val(),
        'unit': $("#unitname").val(),
        'remark': $("#add-remark").val()
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
  $('.edit-modal').on('click', function() {
    $('#edit-sub-id').val($(this).data('id'));
    $('#edit-sid').val($(this).data('sid'));
    $('#edit_origin_general_name').val($(this).data('generalname'));
    $('#edit-subsystem-title').text('系統分類: ' + $(this).data('systemname'));
    $('#edit_general_name').val($(this).data('generalname'));
    $('#sub_system_name').val($(this).data('name'));
    $('#edit-format').val($(this).data('format'));
    $('#unit_price').val($(this).data('unitprice'));
    $('#unit').val($(this).data('unit'));
    $('#remark').val($(this).data('remark'));
  });
  // 修改子項目
  $('#editSubSystem-btn').on('click', function() {
    $.ajax({
      type: 'put',
      url: '/subsystem',
      data: {
        'id': $('#edit-sub-id').val(),
        'sid': $('#edit-sid').val(),
        'general_name': $('#edit_general_name').val(),
        'origin_general_name': $('#edit_origin_general_name').val(),
        'name': $('#sub_system_name').val(),
        'format': $('#edit-format').val(),
        'unit_price': $('#unit_price').val(),
        'unit': $('#unit').val(),
        'remark': $("#remark").val()
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
  $('.delete-modal').on('click', function() {
    $('#del-sub-title').text('系統分類: ' + $(this).data('systemname'));
    $('#del-sub-content').text('內容物: ' + $(this).data('name'));
    $('#del-sub-id').val($(this).data('id'));
    $('#del-sid').val($(this).data('sid'));
    $('#del-general_name').val($(this).data('generalname'));
  });
  // 刪除子項目
  $('#delSubSystem-btn').on('click', function() {
    $.ajax({
      type: 'delete',
      url: '/subsystem',
      data: {
        'id': $('#del-sub-id').val(),
        'sid': $('#del-sid').val(),
        'general_name': $('#del-general_name').val(),
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
.remark {
  width: 10%;
}
.system_name {
  width: 25%;
}
.format {
  width: 15%;
}
.unit_price {
  width: 10%;
}
.unit {
  width: %;
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
  left: 352px;
}
.system-set {
  position: absolute;
  top: 13px;
  left: 489px;
}
.system-delete {
  position: absolute;
  top: 13px;
  left: 610px;
}
</style>
@endsection