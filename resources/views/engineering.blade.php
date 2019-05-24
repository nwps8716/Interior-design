@extends('home')

@section('feature')
  <h1>工程單價列表</h1>
  <button id="edit-sub-preject" class="btn btn-primary btn-submit">修改子項目</button>
@endsection

@section('content')
  <div class="row">
    <div class="col-sm-12">
      <table class="table table-striped">
        @foreach($engineering as $id => $projectname)
          <span>
            <thead>
              <tr>
                <td>項目</td>
                <td>單價</td>
                <td>單位</td>
                <td colspan = 2>Actions</td>
              </tr>
            </thead>
            @foreach($sub_engineering[$id] as $sub_name)
              <tbody>
                <td>{{$sub_name['sub_project_name']}}</td>
                <td>{{$sub_name['unti_price']}}</td>
                <td>{{$sub_name['unti']}}</td>
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
        @endforeach
      </table>
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
        location.reload(true);
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
        location.reload(true);
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
</style>
@endsection