<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>窩百態室內裝潢</title>

  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>

<body>
  @include('sweetalert::alert')

  <div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper">
      <div class="sidebar-heading"><a href="/home" class="list-group-item-action">室內裝潢-後台</a></div>
      <div class="list-group list-group-flush">
        <a href="#" class="list-group-item list-group-item-action bg-light">坪數估價</a>
        <a href="/unitprice/engineering" class="list-group-item list-group-item-action bg-light">工程單價</a>
        <a href="#" class="list-group-item list-group-item-action bg-light">系統單價</a>
      </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <button class="btn btn-primary" id="menu-toggle">選單</button>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                使用者名稱
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a class="dropdown-item" href="#">修改密碼</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/logout">登出</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>

      <div class="container-fluid">
        @section('feature')
          <h1>歡迎使用窩百態後台</h1>
        @show
        @section('content')
        @show
      </div>
    </div>
    <!-- /#page-content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Bootstrap core JavaScript -->
  <script src="{{asset('js/jquery.min.js')}}"></script>
  <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>

  <!-- Menu Toggle Script -->
  <script>
    // 設定csrf-token
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    // 選單收合
    $("#menu-toggle").click(function(e) {
      e.preventDefault();
      $("#wrapper").toggleClass("toggled");
    });
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

</body>

<style>
    body {
        overflow-x: hidden;
    }

    #sidebar-wrapper {
        min-height: 100vh;
        margin-left: -15rem;
        -webkit-transition: margin .25s ease-out;
        -moz-transition: margin .25s ease-out;
        -o-transition: margin .25s ease-out;
        transition: margin .25s ease-out;
    }

    #sidebar-wrapper .sidebar-heading {
        padding: 0.775rem 1.25rem;
        font-size: 1.2rem;
    }

    #sidebar-wrapper .list-group {
        width: 15rem;
    }

    #page-content-wrapper {
        min-width: 100vw;
    }

    #wrapper.toggled #sidebar-wrapper {
        margin-left: 0;
    }

    @media (min-width: 768px) {
        #sidebar-wrapper {
            margin-left: 0;
        }

        #page-content-wrapper {
            min-width: 0;
            width: 100%;
        }

        #wrapper.toggled #sidebar-wrapper {
            margin-left: -15rem;
        }
    }
</style>

</html>
