@extends('layout')

@section('content')
@if(Session::has('message'))
  <div class="alert alert-success">  {{ Session::get('message') }}</div>
@endif

<link href="/../css/album.css" rel="stylesheet">
<table class="table table-striped">
  <tbody>
    <?php foreach ($table as $row) : ?>
      <h1>Site: {{ $row->name }}</h1>
      <tr>
        <td>Id</td>
        <td>{{ $row->id }}</td>
      </tr>
      <tr>
        <td>Name</td>
        <td>{{ $row->name }}</td>
      </tr>
      <tr>
        <td>Creat</td>
        <td>{{ $row->created_at }}</td>
      </tr>
      <tr>
        <td>Update</td>
        <td>{{ $row->updated_at }}</td>
      </tr>
      <tr>
        <td>Last h1</td>
        <td>{{ $row->lastH1 }}</td>
      </tr>
      <tr>
        <td>Last Keywords</td>
        <td>{{ $row->lastKeywords }}</td>
      </tr>
      <tr>
        <td>Last Description</td>
        <td>{{ $row->lastDescription }}</td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>
<form class="form-inline mt-2 mt-md-0" action="/domains/{{ $row->id }}/check" method="post">
    {{ csrf_field() }}
    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Run check">
</form>
<table class="table">
  <thead>
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
    <?php foreach ($checks as $check) : ?>
      <tr>
        <th scope="row"> {{ $check->id }} </th>
        <td>{{ $check->created_at }}</td>
        <td>{{ $check->status_code }}</td>
        <td>{{ $check->h1 }}</td>
        <td>{{ $check->keywords }}</td>
        <td>{{ $check->description }}</td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

@endsection