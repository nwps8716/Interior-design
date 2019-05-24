@extends('home')

@section('feature')
  <h1>坪數估價</h1>
  <button id="test">hello</button>
@endsection

@section('script')
@parent
<script>
  $("#test").click(function(e) {
    alert(123);
  });
</script>
@endsection

@section('style')
@parent
<style>
  #test {
    color: red;
  }
</style>
@endsection

