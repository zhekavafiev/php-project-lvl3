@extends('layout')

@section('content')
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
    <?php endforeach; ?>
  </tbody>
</table>
<form class="form-inline mt-2 mt-md-0" action="/domains/{{ $row->id }}/check" method="post">
    {{ csrf_field() }}
    <input class="btn btn-primary btn-lg btn-block" type="submit" value="Run check">
</form>
@endsection