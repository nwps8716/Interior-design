@extends('home')

@section('feature')
  <div class="project-menu-title">
      <h1 class='content-title'>工程單價列表</h1>
      <button
        id="add-project"
        class="add-project btn btn-primary"
        data-toggle="modal"
        data-target="#addProject">
          新增工程分類
      </button>
      <button
        id="add-subproject"
        class="add-sub-project btn btn-primary"
        data-toggle="modal"
        data-target="#addSubProject">
          新增工程子項目
      </button>
      <button
        id="project-set"
        class="project-set btn btn-success"
        data-toggle="modal"
        data-target="#projectSet">
          工程分類設定
      </button>
      <button
        id="project-delete"
        class="project-delete btn btn-danger"
        data-toggle="modal"
        data-target="#projectDelete">
          刪除工程分類
      </button>
  </div>
@endsection

@section('content')
  <!-- 工程單價列表 -->
  <div class="row">
    <div class="col-sm-12">
      @foreach($engineering as $id => $projectname)
      <h4 class='project-title'>{{$projectname}}</h4>
      <table class="table table-striped">
          <span>
            <thead>
              <tr class="subproject-title">
                <td >備註</td>
                <td >項目</td>
                <td >單價</td>
                <td >單位</td>
                <td colspan = 2>Actions</td>
              </tr>
            </thead>
            @foreach($sub_engineering[$id] as $sub_name)
              <tbody>
                <td class="remark">{{$sub_name['remark']}}</td>
                <td class="project-name">{{$sub_name['sub_project_name']}}</td>
                <td class="unit_price">{{$sub_name['unit_price']}}</td>
                <td class="unit">{{$sub_name['unit']}}</td>
                <td>
                  <button
                    class="edit-modal btn btn-success"
                    data-id="{{$sub_name['sub_project_id']}}"
                    data-name="{{$sub_name['sub_project_name']}}"
                    data-unit="{{$sub_name['unit']}}"
                    data-unitprice="{{$sub_name['unit_price']}}"
                    data-remark="{{$sub_name['remark']}}"
                    data-projectname="{{$projectname}}">
                      <span class="glyphicon glyphicon-edit">修改</span>
                  </button>
                  <button
                    class="delete-modal btn btn-danger"
                    data-id="{{$sub_name['sub_project_id']}}"
                    data-name="{{$sub_name['sub_project_name']}}"
                    data-projectname="{{$projectname}}">
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
  <div id="editSubProject" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title"></h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" role="form">
                <div class="form-group">
                  <label class="control-label col-sm-4" for="sub_project_name">項目名稱:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="sub_project_name">
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
                <input type="hidden" class="form-control" id="sub_project_id">
              </form>
              <div class="deleteContent">
                確定要刪除 “<span class="sub_project_name"></span> ” 嗎?
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <span class='glyphicon glyphicon-remove'></span>取消
              </button>
              <button type="button" class="btn actionBtn" data-dismiss="modal">
                <span id="footer_action_button" class='glyphicon'> </span>
              </button>
            </div>
          </div>
      </div>
  </div>

  <!-- 新增工程單價分類 -->
  <div id="addProject" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addProjectLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" class="addProjectLabel">新增工程單價分類</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form class="add-project-form" role="form">
                <div class="form-group">
                  <label class="control-label col-sm-4" for="project_name">工程分類名稱:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="project_name">
                  </div>
                </div>
              </form>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
              <button id="addProject-btn" type="button" class="addProject btn btn-primary">確定新增</button>
            </div>
          </div>
      </div>
  </div>

  <!-- 新增工程單價 - 子項目 -->
  <div id="addSubProject" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addSubProjectLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" class="addSubProjectLabel">新增工程單價 - 子項目</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form class="add-sub-form" role="form">
                <div class="form-group">
                  <label class="control-label col-sm-10" for="project_name">請選擇要新增在哪個工程項目大類底下:</label>
                  <select class="select-engineering">
                    @foreach($engineering as $id => $projectname)
                      <option value="{{$id}}">{{$projectname}}</option>
                    @endforeach
                  </select>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-4" for="subproject_name">工程子項目名稱:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="subproject_name">
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
              <button id="addSubProject-btn" type="button" class="addSubProject btn btn-primary">確定新增</button>
            </div>
          </div>
      </div>
  </div>

  <!-- 工程分類設定 -->
  <div id="projectSet" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="projectSetLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" class="projectSetLabel">工程分類設定</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="edit-project-div">
                <div style="margin-bottom: 6px;">
                  選擇工程分類:
                  <select class="edit-project-select">
                    @foreach($engineering as $id => $projectname)
                      <option value="{{$id}}">{{$projectname}}</option>
                    @endforeach
                  </select>
                </div>
                <div style="margin-bottom: 6px;">
                  預修改名稱:
                  <input type="text" class="edit-project-name" id="edit-project-name">
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button id="edit-project-btn" type="button" class="editProject btn btn-success">確定修改</button>
              </div>
            </div>
          </div>
      </div>
  </div>

  <!-- 刪除工程分類 -->
  <div id="projectDelete" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="projectDeleteLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" class="projectDeleteLabel">刪除工程分類</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="delete-project-div">
                <div style="margin-bottom: 6px;">
                  選擇預刪除工程分類:
                  <select class="delete-project-select">
                    @foreach($engineering as $id => $projectname)
                      <option value="{{$id}}"><span class="delete-name">{{$projectname}}</span></option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                <button id="delete-project-btn" type="button" class="deleteProject btn btn-danger">確認刪除</button>
              </div>
            </div>
          </div>
      </div>
  </div>
@endsection

@section('script')
@parent
<script>
  // 觸發修改子項目Dialog
  $(document).on('click', '.edit-modal', function() {
      $('#footer_action_button').text("確認修改");
      $('#footer_action_button').addClass('glyphicon-check');
      $('#footer_action_button').removeClass('glyphicon-trash');
      $('.actionBtn').removeClass('btn-danger');
      $('.actionBtn').removeClass('delete');
      $('.actionBtn').addClass('btn-success');
      $('.actionBtn').addClass('edit');
      $('.modal-title').text($(this).data('projectname'));
      $('.form-horizontal').show();
      $('.deleteContent').hide();
      $('#sub_project_id').val($(this).data('id'));
      $('#sub_project_name').val($(this).data('name'));
      $('#unit_price').val($(this).data('unitprice'));
      $('#unit').val($(this).data('unit'));
      $('#remark').val($(this).data('remark'));
      $('#editSubProject').modal('show');
  });
  // 更新子項目
  $('.modal-footer').on('click', '.edit', function() {
    $.ajax({
      type: 'put',
      url: '/subengineering',
      data: {
        'id': $("#sub_project_id").val(),
        'name': $('#sub_project_name').val(),
        'unit_price': $('#unit_price').val(),
        'unit': $('#unit').val(),
        'remark': $('#remark').val()
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
  // 觸發刪除子項目Dialog
  $(document).on('click', '.delete-modal', function() {
      $('#footer_action_button').text("確認刪除");
      $('#footer_action_button').addClass('glyphicon-check');
      $('#footer_action_button').removeClass('glyphicon-trash');
      $('.actionBtn').removeClass('btn-success');
      $('.actionBtn').removeClass('edit');
      $('.actionBtn').addClass('btn-danger');
      $('.actionBtn').addClass('delete');
      $('.modal-title').text($(this).data('projectname'));
      $('.deleteContent').show();
      $('.form-horizontal').hide();
      $('#sub_project_id').val($(this).data('id'));
      $('.sub_project_name').html($(this).data('name'));
      $('#editSubProject').modal('show');
  });
  // 刪除子項目
  $('.modal-footer').on('click', '.delete', function() {
    $.ajax({
      type: 'delete',
      url: '/subengineering',
      data: {
        'id': $("#sub_project_id").val()
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
  // 新增工程單價分類
  $('.modal-footer').on('click', '#addProject-btn', function() {
    $.ajax({
      type: 'post',
      url: '/engineering',
      data: {
        'project_name': $("#project_name").val()
      },
      success: function(resp) {
        swal({
          title: "Success",
          text: `新增工程項目 => ${$("#project_name").val()} 成功！`,
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
  // 新增工程單價 - 子項目
  $('.modal-footer').on('click', '#addSubProject-btn', function() {
    $.ajax({
      type: 'post',
      url: '/subengineering',
      data: {
        'project_id': $(".select-engineering").val(),
        'sub_project_name': $("#subproject_name").val(),
        'unit_price': $("#unitprice").val(),
        'unit': $("#unitname").val(),
        'remark': $("#add-remark").val()
      },
      success: function(resp) {
        swal({
          title: "Success",
          text: `新增工程子項目 => ${$("#subproject_name").val()} 成功！`,
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
  // 修改工程分類項目
  $('.modal-footer').on('click', '#edit-project-btn', function() {
    $.ajax({
      type: 'put',
      url: '/engineering',
      data: {
        'id': $(".edit-project-select").val(),
        'name': $(".edit-project-name").val(),
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
  // 刪除工程分類項目
  $('.modal-footer').on('click', '#delete-project-btn', function() {
    $.ajax({
      type: 'delete',
      url: '/engineering',
      data: {
        'id': $(".delete-project-select").val()
      },
      success: function(resp) {
        if (resp.result === false) {
          swal({
            title: "Error",
            text: `該工程大類底下還有子項目，無法刪除！！`,
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
</script>
@endsection

@section('style')
@parent
<style>
.project-menu-title {
  position: relative;
}
.sub_project_name {
  color: red;
}
.select-engineering {
  margin-left: 15px;
  width: 200px;
}
.add-project {
  position: absolute;
  top: 13px;
  left: 230px;
}
.add-sub-project {
  position: absolute;
  top: 13px;
  left: 352px;
}
.project-set {
  position: absolute;
  top: 13px;
  left: 489px;
}
.project-delete {
  position: absolute;
  top: 13px;
  left: 610px;
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
.remark {
  width: 10%;
}
.project-name {
  width: 50%;
}
.unit_price {
  width: 15%;
}
.unit {
  width: 10%;
}
.edit-project-name {
  width: 50%;
}
.editProject {
  margin: 0px 0px 0px 10px;
}
.deleteProject {
  margin: 0px 0px 0px 10px;
}
</style>
@endsection