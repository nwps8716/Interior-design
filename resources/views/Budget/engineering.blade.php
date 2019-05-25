@extends('home')

@section('feature')
  <div>
      <h1 class='content-title'>裝潢工程預算表</h1>
      <div>
        <form action="/engineering/budget" method="POST" class="budget-form" id="budget-form">
          {{ csrf_field() }}
          <div class="form-group">
            <label class="control-label">級距:</label>
            <select class="budget">
              @foreach($spacing as $id => $name)
                <option value="{{$id}}">{{$name}}級裝潢工程預算</option>
              @endforeach
            </select>
              <input type="submit" name="search" id="search" class="search-btn btn btn-primary" value="查詢"/>
          </div>
        </form>
      </div>
  </div>
@endsection

@section('content')
<!-- 裝潢工程預算列表 -->
<div class="row">
  <div class="col-sm-12">
    {{-- @foreach($engineering as $id => $projectname)
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
    @endforeach --}}
  <div>
</div>

@endsection

@section('script')
@parent
<script>
</script>
@endsection

@section('style')
@parent
<style>
.search-btn {
  margin: 0px 0px 0px 10px;
}
</style>
@endsection