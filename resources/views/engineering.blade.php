@extends('home')

@section('feature')
  <h1>工程單價列表</h1>
@endsection

@section('content')
  <div class="row">
    <div class="col-sm-12">
      <table class="table table-striped">
        @foreach($engineering as $id => $name)
          <span>
            <thead>
              <tr>
                <td>項目</td>
                <td>單價</td>
                <td>單位</td>
              </tr>
            </thead>
            @foreach($sub_engineering[$id] as $sub_name)
              <tbody>
                <td>{{$sub_name['sub_project_name']}}</td>
                <td>{{$sub_name['unti_price']}}</td>
                <td>{{$sub_name['unti']}}</td>
              </tbody>
            @endforeach
        </span>
        @endforeach
      </table>
    <div>
  </div>
@endsection