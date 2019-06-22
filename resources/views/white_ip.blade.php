@extends('home')

@section('feature')
  <div class="project-menu-title">
    <h1 class='content-title'>白名單IP設置</h1>
    <button
      id="add-white_ip-btn"
      class="btn btn-primary"
      data-toggle="modal"
      data-target="#addWhiteIP">
        新增白名單IP
    </button>
  </div>
@endsection

@section('content')
  <!-- 白名單IP列表 -->
  <div class="row">
    <div class="col-sm-12">
      <table class="table table-striped">
        <span class="white_ip-span">
          <thead>
            <tr class="whiteip-title">
              <td >流水號</td>
              <td >ＩＰ</td>
              <td >備註</td>
              <td colspan = 2>Actions</td>
            </tr>
          </thead>
          @foreach($ip_list as $id => $value)
            <tbody>
              <td class="id-td">{{$id}}</td>
              <td class="ip-td">{{$value['ip']}}</td>
              <td class="remark-td">{{$value['remark']}}</td>
              <td class="action-td">
                <button
                  id="delete"
                  class="delete-modal btn btn-danger"
                  data-ip="{{$value['ip']}}"
                  data-toggle="modal"
                  data-target="#delWhiteIp">
                    <span class="glyphicon glyphicon-trash">刪除</span>
                </button>
              </td>
            </tbody>
          @endforeach
        </span>
      </table>
    <div>
  </div>

  <!-- 新增白名單IP -->
  <div id="addWhiteIP" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="addWhiteIPLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" class="addWhiteIPLabel">工程分類設定</h5>
              <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
              <div class="add-ip-div">
                <div style="margin-bottom: 6px;">
                  白名單IP:
                  <input type="text" class="form-control" id="add-white_ip">
                </div>
                <div style="margin-bottom: 6px;">
                  備註:
                  <input type="text" class="form-control" id="add-white_ip-remark">
                </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
              <button id="addWhiteIp-btn" type="button" class="editProject btn btn-success">新增</button>
            </div>
          </div>
      </div>
  </div>

  <!-- 刪除子項目 dialog -->
  <div id="delWhiteIp" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="del-title"></h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <form class="form-horizontal" role="form">
            <div class="form-group">
              <label class="control-label" id="del-content"></label>
            </div>
          </form>
        </div>
        <input type="hidden" class="form-control" id="del-whiteip">
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
          <button id="delWhiteIp-btn" type="button" class="btn btn-danger">確定刪除</button>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')
@parent
<script>
  // 新增白名單IP
  $('.modal-footer').on('click', '#addWhiteIp-btn', function() {
    $.ajax({
      type: 'post',
      url: '/whiteip',
      data: {
        'white_ip': $("#add-white_ip").val(),
        'remark': $("#add-white_ip-remark").val()
      },
      success: function(resp) {
        var title = 'Success';
        var icno = 'success';
        var msg = `新增IP=> ${$("#add-white_ip").val()} 成功！`;
        if (!resp.result) {
          title = 'Error';
          icno = 'error';
          msg = resp.errormsg;
        }
        swal({
          title: title,
          text: msg,
          icon: icno,
          buttons: false,
          timer: 1500,
        })
        .then(() => {
          if (resp.result) {
            location.reload(true);
          }
        });
      }
    });
  });
  // 刪除子項目 dialog 事件
  $('.delete-modal').on('click', function() {
    $('#del-title').text('刪除白名單IP');
    $('#del-content').text('是否刪除IP: ' + $(this).data('ip'));
    $('#del-whiteip').val($(this).data('ip'));
  });
  // 刪除白名單IP
  $('#delWhiteIp-btn').on('click', function() {
    $.ajax({
      type: 'delete',
      url: '/whiteip',
      data: {
        'white_ip': $("#del-whiteip").val()
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
  .project-menu-title {
    position: relative;
  }
  #add-white_ip-btn {
    position: absolute;
    top: 13px;
    left: 230px;
  }
  .project-title {
    color: white;
    background-color: #2d76be;
    padding: 10px;
    text-align: center;
    margin-bottom: 0px;
  }
  .whiteip-title {
    background-color: #a6c7e8;
  }
</style>
@endsection