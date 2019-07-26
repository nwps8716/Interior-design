@extends('home')

@section('feature')
  <div class="project-menu-title">
    <h1 class='content-title'>工程排序設置</h1>
    <button
      id="set_sort-btn"
      class="btn btn-primary">
        儲存設定
    </button>
  </div>
@endsection

@section('content')
  <nav class="change-tab">
    <div class="nav nav-tabs" id="change-project" role="tablist">
      <a class="nav-item nav-link active"
        id="project-tab"
        data-toggle="tab"
        href="#chose-project"
        role="tab"
        aria-controls="nav-project"
        aria-selected="true">
          裝潢工程
      </a>
      <a class="nav-item nav-link"
        id="system-tab"
        data-toggle="tab"
        href="#chose-system"
        role="tab"
        aria-controls="nav-system"
        aria-selected="false">
          系統工程
      </a>
      <a class="nav-item nav-link"
        id="system-detail-tab"
        data-toggle="tab"
        href="#chose-system-detail"
        role="tab"
        aria-controls="nav-system-detail"
        aria-selected="false">
          系統工程 - 細項
      </a>
    </div>
  </nav>
  <div class="tab-content" id="nav-tabContent">
    {{-- 裝潢工程排序 --}}
    <div class="tab-pane fade show active" id="chose-project" role="tabpanel" aria-labelledby="nav-project">
      <div class="row">
        <div class="col-md-3">
          <div class="card">
            <div class="card-body">
              <div class="list-group" id="list-tab" role="tablist">
                @foreach($project_list as $projectid => $projectname)
                  <a class="list-group-item list-group-item-action {{ $projectid == 0 ? 'active' : '' }}"
                    id="project_{{ $projectid }}"
                    data-toggle="list"
                    href="#project-{{ $projectid }}"
                    role="tab"
                    aria-controls="list-project-{{ $projectid }}">
                      {{ $projectname }}
                  </a>
                @endforeach
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-9">
          <div class="card">
            <div class="card-body">
              <div class="tab-content" id="nav-tabContent">
                @foreach($project_sort as $projectid => $projectvalue)
                  <div class="tab-pane fade {{ $projectid == 0 ? 'show active' : '' }}"
                    id="project-{{ $projectid }}"
                    role="tabpanel"
                    aria-labelledby="list-project-{{ $projectid }}"
                  >
                    <ul id="sortable-{{ $projectid }}" class="connectedSortable">
                      @foreach($projectvalue as $sortkey => $value)
                        <li class="ui-state-default" id="{{ $projectid == 0 ? $value['project_id'] : $value['sub_project_id'] }}">
                          <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                          @if($projectid == 0)
                            <span class="project_name">{{ $value['project_name'] }}</span>
                          @else
                            <span class="sub_project_name">{{ $value['sub_project_name'] }}</span>
                          @endif
                        </li>
                      @endforeach
                    </ul>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- 系統工程排序 --}}
    <div class="tab-pane fade" id="chose-system" role="tabpanel" aria-labelledby="nav-system">
      <div class="row">
        <div class="col-md-3">
          <div class="card">
            <div class="card-body">
              <div class="list-group" id="list-system-tab" role="tablist">
                @foreach($system_list as $systemid => $systemname)
                  <a class="list-group-item list-group-item-action {{ $systemid == 0 ? 'active' : '' }}"
                    id="system_{{ $systemid }}"
                    data-toggle="list"
                    href="#system-{{ $systemid }}"
                    role="tab"
                    aria-controls="list-system-{{ $systemid }}">
                      {{ $systemname }}
                  </a>
                @endforeach
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-9">
          <div class="card">
            <div class="card-body">
              <div class="tab-content" id="nav-tabContent">
                @foreach($system_sort as $systemid => $systemvalue)
                  <div class="tab-pane fade {{ $systemid == 0 ? 'show active' : '' }}"
                    id="system-{{ $systemid }}"
                    role="tabpanel"
                    aria-labelledby="list-system-{{ $systemid }}"
                  >
                    <ul id="sortable-system-{{ $systemid }}" class="connectedSortable">
                      @foreach($systemvalue as $sortkey => $value)
                        <li class="ui-state-default" id="{{ $systemid == 0 ? $value['system_id'] : $value['sgn_id'] }}">
                          <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                          @if($systemid == 0)
                            <span class="system_name">{{ $value['system_name'] }}</span>
                          @else
                            <span class="general_name">{{ $value['general_name'] }}</span>
                          @endif
                        </li>
                      @endforeach
                    </ul>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- 系統工程 - 細項排序 --}}
    <div class="tab-pane fade" id="chose-system-detail" role="tabpanel" aria-labelledby="nav-system-detail">
      <div class="row">
        {{-- 大項目 --}}
        <div class="col-md-6">
          <span> 請選擇大項目:
          <select id="box1">
            <option value="">-- 請選擇 --</option>
          @foreach($system_detail_sort as $systemid => $eneral)
              <option value="{{$systemid}}">{{ $system_list[$systemid] }}</option>
          @endforeach
          </select>
        </div>
        {{-- 統稱 --}}
        <div class="col-md-6">
          <span> 請選擇統稱:
          <select id="box2">
          </select>
        </div>
        {{-- 統稱 - 細項 --}}
        <div class="col-md-12" id="general-detail-div">
          <div class="card">
            <div class="card-body">
              <div class="tab-content" id="nav-tabContent">
                <ul id="sortable-general-detail" class="connectedSortable"></ul>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('script')

@parent
<script>
  var changProjectID = 0;
  var changSystemID = 0;
  var category_id = 1;
  var systemDetailSortData = {!! json_encode($system_detail_sort) !!};
  var systemGeneral = {!! json_encode($system_general) !!};
  var generalSort = {!! json_encode($general_sort) !!};

  // 切換工程大項目
  $('#change-project').on('click', 'a', function(e) {
    e.preventDefault();
    var choseName = $(this).attr('id');
    category_id = (choseName === 'system-tab') ? 2 : 1;
    category_id = (choseName === 'system-detail-tab') ? 3 : category_id;
  });
  // 初始設定裝潢工程排序大項目
  $( "#sortable-0" ).sortable({
    placeholder: "ui-state-highlight"
  });
  $( "#sortable-0" ).disableSelection();
  // 更換裝潢工程排序項目
  $('#list-tab').on('click', 'a', function(e) {
    e.preventDefault();
    var project_id = $(this).attr('id');
    var pid = project_id.replace(/project_/, '');
    $("#sortable-" +  pid).sortable({
      placeholder: "ui-state-highlight"
    });
    $("#sortable-" + pid).disableSelection();
    changProjectID = pid;
  });
  // 初始設定系統工程排序大項目
  $("#sortable-system-0").sortable({
    placeholder: "ui-state-highlight"
  });
  $("#sortable-system-0").disableSelection();
  // 更換系統工程排序項目
  $('#list-system-tab').on('click', 'a', function(e) {
    e.preventDefault();
    var system_id = $(this).attr('id');
    var sid = system_id.replace(/system_/, '');
    $("#sortable-system-" +  sid).sortable({
      placeholder: "ui-state-highlight"
    });
    $("#sortable-system-" + sid).disableSelection();
    changSystemID = sid;
  });
  // 更新排序
  $('#set_sort-btn').on('click', function() {
    var productOrder;
    var sub_status;
    // 裝潢工程
    if (category_id === 1) {
      productOrder = $('#sortable-' + changProjectID).sortable('toArray');
      sub_status = (changProjectID > 0) ? 1 : 0;

    // 系統工程
    } else if (category_id === 2) {
      productOrder = $('#sortable-system-' + changSystemID).sortable('toArray');
      sub_status = (changSystemID > 0) ? 1 : 0;

    // 系統工程 - 統稱係項
    } else if (category_id === 3) {
      productOrder = $('#sortable-general-detail').sortable('toArray');
      sub_status = 1;
    }

    // 判斷有資料才做更新
    if (Array.isArray(productOrder) && productOrder.length > 0) {
      $.ajax({
        type: 'put',
        url: '/engineering/' + category_id + '/sort',
        data: {
          'sub_status': sub_status,
          'sort_data': productOrder
        },
        success: function(resp) {
          swal({
            title: "Success",
            text: "設定成功！",
            icon: "success",
            buttons: false,
            timer: 1500,
          });
        }
      });
    } else {
      swal({
        title: "Error",
        text: "設定失敗!!",
        icon: "error",
        buttons: false,
        timer: 1500,
      });
    }
  });
  // 選擇大項目後 - 取得該所有統稱資料
  $("select#box1").on('change', function(){
    var selectedId = $(this).val();
    var subItems = systemGeneral[selectedId];
    $("select#box2").html('');
    $("ul#sortable-general-detail").html('');
    for (var subId in subItems) {
      var optionFirst = $('<option></option>');
      var option = $('<option></option>');
      if (subId == 0) {
        optionFirst.attr('value', '');
        optionFirst.html('-- 請選擇 --');
        $("select#box2").append(optionFirst);
      }
      option.attr('value', subId);
      option.html(subItems[subId]);
      $("select#box2").append(option);
    }
  });
  // 選擇統稱後 - 取得該所有細項資料
  $("select#box2").on('change', function(){
    var systemID = $("select#box1").val();         // 系統工程大項ID
    var generalList = systemGeneral[systemID];     // 統稱列表
    var selectedId = $(this).val();                // 統稱ID
    var li = '';
    var liList = [];

    // 判斷統稱不為空
    if (selectedId !== '') {
      // 統稱
      var general = generalList[selectedId];
      // 統稱係項資料
      var generalDetail = [systemDetailSortData[systemID][general]];
      $("ul#sortable-general-detail").html('');
      var test = '';
      Object.keys(generalDetail[0]).forEach((key) => {
        const val = generalDetail[0][key];
        const content = `備註：${val.remark}、 內容：${val.sub_system_name}、 規格：${val.format}`;
        liList[key] =
          `<li class="ui-state-default" id="${key}">
            <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
            <span class="general-detail">${content}</span>
          </li>`;
      });

      // 判斷有資料，重新排序
      if (liList.length > 0) {
        var liTmp = [liList];
        var liSortData = [];
        Object.keys(liTmp[0]).forEach((key) => {
          liSortData[generalSort[key]] = liTmp[0][key];
        });
        liSortData.forEach((item) => {
          li = li + item
        });
      }
    } else {
      $("ul#sortable-general-detail").html('');
    }
    $("ul#sortable-general-detail").append(li);
  });
  // 初始設定系統工程 - 統稱細項排序
  $("#sortable-general-detail").sortable({
    placeholder: "ui-state-highlight"
  });
  $("#sortable-general-detail").disableSelection();
</script>
@endsection

@section('style')
@parent
<style>
  .change-tab {
    margin: 0px 0px 10px 0px;
  }
  .project-menu-title {
    position: relative;
  }
  #set_sort-btn {
    position: absolute;
    top: 13px;
    left: 230px;
  }
  .connectedSortable {
    list-style-type: none;
    margin: 0;
    padding: 0;
    width: 100%;
  }
  .connectedSortable li {
    margin: 0 5px 5px 5px;
    padding-left: 1.5em;
    font-size: 1.7em;
    height: 40px;
  }
  .connectedSortable li span {
    position: absolute;
    margin-left: -1.3em;
  }
  .project_name, .sub_project_name, .general_name, .system_name, .general-detail {
    padding: 0px 0px 0px 30px;
  }
  .ui-icon {
    margin-top: 10px;
  }
  #general-detail-div {
    margin-top: 20px;
  }
</style>
@endsection