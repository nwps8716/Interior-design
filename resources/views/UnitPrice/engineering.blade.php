@extends('home')

@section('feature')
  <div>
      <h1 class='content-title'>工程單價列表</h1>
      <button class="add-project btn btn-primary">新增工程分類</button>
      <button class="add-sub-project btn btn-primary">新增工程子項目</button>
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
                <td >項目</td>
                <td >單價</td>
                <td >單位</td>
                <td colspan = 2>Actions</td>
              </tr>
            </thead>
            @foreach($sub_engineering[$id] as $sub_name)
              <tbody>
                <td class="project-name">{{$sub_name['sub_project_name']}}</td>
                <td class="unti_price">{{$sub_name['unti_price']}}</td>
                <td class="unti">{{$sub_name['unti']}}</td>
                <td>
                  <button
                    class="edit-modal btn btn-info"
                    data-id="{{$sub_name['sub_project_id']}}"
                    data-name="{{$sub_name['sub_project_name']}}"
                    data-unti="{{$sub_name['unti']}}"
                    data-untiprice="{{$sub_name['unti_price']}}"
                    data-projectname="{{$projectname}}">
                      <span class="glyphicon glyphicon-edit">Edit</span>
                  </button>
                  <button
                    class="delete-modal btn btn-danger"
                    data-id="{{$sub_name['sub_project_id']}}"
                    data-name="{{$sub_name['sub_project_name']}}"
                    data-projectname="{{$projectname}}">
                      <span class="glyphicon glyphicon-trash">Delete</span>
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
              <h4 class="modal-title"></h4>
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
                  <label class="control-label col-sm-2" for="unti_price">單價:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="unti_price">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-2">單位:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="unti">
                  </div>
                </div>
                <input type="hidden" class="form-control" id="sub_project_id">
              </form>
              <div class="deleteContent">
                確定要刪除 “<span class="sub_project_name"></span> ” 嗎?
              </div>
              <div class="modal-footer">
                <button type="button" class="btn actionBtn" data-dismiss="modal">
                  <span id="footer_action_button" class='glyphicon'> </span>
                </button>
                <button type="button" class="btn btn-warning" data-dismiss="modal">
                  <span class='glyphicon glyphicon-remove'></span> Close
                </button>
              </div>
            </div>
          </div>
      </div>
  </div>

  <!-- 新增工程項目分類 -->
  <div id="addProject" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"></h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" role="form">
                <div class="form-group">
                  <label class="control-label col-sm-4" for="project_name">工程分類名稱:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="project_name">
                  </div>
                </div>
              </form>
              <div class="modal-footer">
                <button type="button" class="btn actionBtn" data-dismiss="modal">
                  <span id="add_button" class='glyphicon'>Add</span>
                </button>
                <button type="button" class="btn btn-warning" data-dismiss="modal">
                  <span class='glyphicon glyphicon-remove'></span> Close
                </button>
              </div>
            </div>
          </div>
      </div>
  </div>

  <!-- 新增工程項目 - 子項目 -->
  <div id="addSubProject" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h4 class="modal-title"></h4>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <form class="form-horizontal" role="form">
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
                  <label class="control-label col-sm-4" for="untiprice">單價:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="untiprice">
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-sm-4" for="unitname">單位:</label>
                  <div class="col-sm-10">
                    <input type="text" class="form-control" id="unitname">
                  </div>
                </div>
              </form>
              <div class="modal-footer">
                <button type="button" class="btn actionBtn" data-dismiss="modal">
                  <span id="add_button" class='glyphicon'>Add</span>
                </button>
                <button type="button" class="btn btn-warning" data-dismiss="modal">
                  <span class='glyphicon glyphicon-remove'></span> Close
                </button>
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
      $('#footer_action_button').text("Update");
      $('#footer_action_button').addClass('glyphicon-check');
      $('#footer_action_button').removeClass('glyphicon-trash');
      $('.actionBtn').addClass('btn-success');
      $('.actionBtn').removeClass('btn-danger');
      $('.actionBtn').addClass('edit');
      $('.modal-title').text($(this).data('projectname'));
      $('.form-horizontal').show();
      $('.deleteContent').hide();
      $('#sub_project_id').val($(this).data('id'));
      $('#sub_project_name').val($(this).data('name'));
      $('#unti_price').val($(this).data('untiprice'));
      $('#unti').val($(this).data('unti'));
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
        'unti_price': $('#unti_price').val(),
        'unti': $('#unti').val()
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
      $('#footer_action_button').text("Delete");
      $('#footer_action_button').removeClass('glyphicon-check');
      $('#footer_action_button').addClass('glyphicon-trash');
      $('.actionBtn').removeClass('btn-success');
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
  // 新增工程項目分類 Dialog
  $(document).on('click', '.add-project', function() {
      $('#add_button').addClass('glyphicon-trash');
      $('.actionBtn').addClass('btn-success');
      $('.actionBtn').addClass('addProject');
      $('.modal-title').text('新增工程項目分類');
      $('.form-horizontal').show();
      $('#addProject').modal('show');
  });
  // 新增工程項目分類
  $('.modal-footer').on('click', '.addProject', function() {
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
  // 新增工程項目 - 子項目 Dialog
  $(document).on('click', '.add-sub-project', function() {
      $('#add_button').addClass('glyphicon-trash');
      $('.actionBtn').addClass('btn-success');
      $('.actionBtn').addClass('addSubProject');
      $('.modal-title').text('新增工程項目 - 子項目');
      $('.form-horizontal').show();
      $('#addSubProject').modal('show');
  });
  // 新增工程項目 - 子項目
  $('.modal-footer').on('click', '.addSubProject', function() {
    $.ajax({
      type: 'post',
      url: '/subengineering',
      data: {
        'project_id': $(".select-engineering").val(),
        'sub_project_name': $("#subproject_name").val(),
        'unti_price': $("#untiprice").val(),
        'unti': $("#unitname").val()
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
</script>
@endsection

@section('style')
@parent
<style>
.sub_project_name {
  color: red;
}
.select-engineering {
  margin-left: 15px;
  width: 200px;
}
.add-project {
  margin: 0px 10px 10px 0px;
}
.add-sub-project {
  margin: 0px 10px 10px 0px;
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
  width: 50%;
}
.unti_price {
  width: 15%;
}
.unti {
  width: 10%;
}
</style>
@endsection