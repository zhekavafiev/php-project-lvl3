@extends('layout')

@section('content')
<table class="table table-striped">
  <tbody>
    <?php foreach ($table as $row) : ?>
      <h1>Site: {{ $row->name }}</h1>
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
@endsection