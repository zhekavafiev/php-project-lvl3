@extends('layouts.app')

@section("content")
@if(Session::has('errors'))
  <div class="alert alert-danger">  {{ Session::get('errors') }}</div>
@endif

@if(Session::has('message'))
  <div class="alert alert-success">  {{ Session::get('message') }}</div>
@endif

<table class="table table-striped table-sm">
  <tbody>
      <tr>
        <td>Name</td>
        <td>{{ $domain->name }}</td>
      </tr>
      <tr>
        <td>Creat</td>
        <td>{{ $domain->created_at }}</td>
      </tr>
      <tr>
        <td>Update</td>
        <td>{{ $domain->last_check }}</td>
      </tr>
      <tr>
        <td>Last status</td>
        <td>{{ $domain->status_code }}</td>
      </tr>
      <tr>
        <td>Last h1</td>
        <td>{{ $domain->h1 }}</td>
      </tr>
      <tr>
        <td>Last Keywords</td>
        <td>{{ $domain->keywords }}</td>
      </tr>
      <tr>
        <td>Last Description</td>
        <td>{{ $domain->description }}</td>
      </tr>
  </tbody>
</table>
<form class="form-inline mt-2 mt-md-0" action="{{route('check', $domain->id)}}" method="post">
    {{ csrf_field() }}
    <input class="btn btn-secondary btn-lg btn-block" type="submit" value="Run check">
</form><br>
{{ $checks->links() }}
<table class="table table-sm">
  <thead class="thead-dark">
    <tr>
      <th scope="col">id</th>
      <th scope="col">Create</th>
      <th scope="col">Status</th>
      <th scope="col">h1</th>
      <th scope="col">Keywords</th>
      <th scope="col">Description</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($checks as $check)
      <tr>
        <th scope="row"> {{ $check->id }} </th>
        <td>{{ $check->created_at }}</td>
        <td>{{ $check->status_code }}</td>
        <td>{{ $check->h1 }}</td>
        <td>{{ $check->keywords }}</td>
        <td>{{ $check->description }}</td>
      </tr>
    @endforeach
  </tbody>
</table>

@endsection