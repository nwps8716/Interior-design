<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>窩百態系統家具</title>
  @section('style')
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">
  @show

</head>

<body>
  @include('sweetalert::alert')

  <div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-light border-right" id="sidebar-wrapper">
      <div class="sidebar-heading"><a href="/home" class="list-group-item-action">窩百態系統</a></div>
      <div class="list-group list-group-flush">
        <a href="/pings" class="list-group-item list-group-item-action bg-light">坪數估價</a>
        @if(Session::get('login_user_info')['level'] < 3)
          <a href="/engineering/unitprice" class="list-group-item list-group-item-action bg-light">工程單價</a>
          <a href="/system/unitprice" class="list-group-item list-group-item-action bg-light">系統單價</a>
        @endif
        <a href="/engineering/budget" class="list-group-item list-group-item-action bg-light">裝潢工程預算表</a>
        <a href="/system/budget" class="list-group-item list-group-item-action bg-light">系統工程預算表</a>
        <a href="/system/free_gift" class="list-group-item list-group-item-action bg-light">好禮贈送表</a>
      </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">
      <nav class="navbar navbar-expand-lg navbar-light bg-light border-bottom">
        <h5>使用者目前設定之坪數: {{ Session::get('login_user_info')['pings'] }}</h5>
        
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Hi, {{ Session::get('login_user_info')['user_name'] }}
              </a>
              <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                <a
                  id="put-password"
                  class="put-password dropdown-item"
                  data-toggle="modal"
                  data-target="#putPassword">
                    修改密碼
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="/logout">登出</a>
              </div>
            </li>
          </ul>
        </div>
      </nav>

      <!-- 修改密碼 -->
      <div id="putPassword" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="putPasswordLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
              <div class="modal-header">
                <h5 class="modal-title" class="putPasswordLabel">修改密碼</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
              </div>
              <div class="modal-body">
                <div class="put-password-div">
                  <div style="margin-bottom: 6px;">
                    密碼:
                    <input type="password" name="password" id="password"/>
                  </div>
                  <div style="margin-bottom: 6px;">
                    密碼確認:
                    <input type="password" name="re_password" id="re_password"/>
                  </div>
                </div>
                <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-dismiss="modal">取消</button>
                  <button id="edit-password-btn" type="button" class="btn btn-success" data-dismiss="modal">確定修改</button>
                </div>
              </div>
            </div>
        </div>
      </div>

      <div class="container-fluid">
        @section('feature')
          <h1 class='content-title'>歡迎使用窩百態系統</h1>
        @show
        @section('content')
        @show
      </div>
    </div>
    <!-- /#page-content-wrapper -->

  </div>
  <!-- /#wrapper -->

  <!-- Bootstrap core JavaScript -->
  @section('script')
  <script src="{{asset('js/jquery.min.js')}}"></script>
  <script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
  @show

  <!-- Menu Toggle Script -->
  <script>
    // 設定csrf-token
    $.ajaxSetup({
      headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      }
    });
    // 修改工程分類項目
    $('.modal-footer').on('click', '#edit-password-btn', function() {
      $.ajax({
        type: 'put',
        url: '/user/password',
        data: {
          'password': $("#password").val(),
          're_password': $("#re_password").val(),
        },
        success: function(resp) {
          swal({
            title: "Success",
            text: `修改成功！`,
            icon: "success",
            buttons: false,
            timer: 1500,
          });
        }
      });
    });
  </script>

</body>

<style>
body {
  overflow-x: hidden;
}
.content-title {
  padding: 10px 0px;
}

#sidebar-wrapper {
  margin-left: 0;
  min-height: 100vh;
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
  width: 11rem;
}

#page-content-wrapper {
  min-width: 0;
  width: 100%;
}

#wrapper.toggled #sidebar-wrapper {
  margin-left: -11rem;
}

/* @media (min-width: 768px) {
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
} */
</style>
</html>
