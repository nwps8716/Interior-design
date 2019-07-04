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
  <div class="row">
    <div class="col-md-3">
      <div class="card">
        <div class="card-body">
          <div class="list-group" id="list-tab" role="tablist">
            {{-- <a class="list-group-item list-group-item-action active"
              id="0"
              data-toggle="list"
              href="#large_project"
              role="tab"
              aria-controls="list-large_project">裝潢工程大項
            </a> --}}
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
            {{-- <div class="tab-pane fade show active" id="project-0" role="tabpanel" aria-labelledby="list-project-0">
              <ul id="sortable">
                @foreach($sort_data as $general)
                  <li class="ui-state-default" id="{{ $general['project_id'] }}">
                    <span class="ui-icon ui-icon-arrowthick-2-n-s"></span>
                    <span class="project_name">{{ $general['project_name'] }}</span>
                  </li>
                @endforeach
              </ul>
            </div> --}}
            @foreach($sort_data as $projectid => $projectvalue)
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
@endsection

@section('script')

@parent
<script>
  var changProjectID = 0;
  // 初始設定為排序大項目
  $( "#sortable-0" ).sortable({
    placeholder: "ui-state-highlight"
  });
  $( "#sortable-0" ).disableSelection();
  // 更換排序項目
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
  // 更新排序
  $('#set_sort-btn').on('click', function() {
    var productOrder = $('#sortable-' + changProjectID).sortable('toArray');
    var sub_status = (changProjectID > 0) ? 1 : 0;
    var category_id = 1;
    // console.log(changProjectID);
    // console.log('----------');
    // console.log(productOrder);
    // console.log(category_id);
    // console.log(sub_status);
    // console.log('----------');
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
  .project_name, .sub_project_name {
    padding: 0px 0px 0px 30px;
  }
  .ui-icon {
    margin-top: 10px;
  }
</style>
@endsection