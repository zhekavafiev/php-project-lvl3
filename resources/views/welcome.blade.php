@extends('layouts.app')

@section('content')
<h1 class="mt-5">Page analizator</h1>
<form class="form-inline mt-2 mt-md-0" action="{{route('domains.store')}}" method="post">
  {{ csrf_field() }}
  <input class="form-control mr-sm-2" type="text" placeholder="http://example.com" name="name" >
  <button class="btn btn-outline-dark my-2 my-lg-0" type="submit">Check</button>
</form>
@endsection