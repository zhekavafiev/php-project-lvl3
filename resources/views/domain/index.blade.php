@extends('layouts.app')

@section('content')
@if(Session::has('message'))
  <div class="alert alert-success">  {{ Session::get('message') }}</div>
@endif

<table class="table">
  <thead>
    <tr>
      <th scope="col">id</th>
      <th scope="col">Name</th>
      <th scope="col">Create</th>
      <th scope="col">Last Check</th>
      <th scope="col">Last Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($table as $row)
      <tr>
        <th scope="row"> {{ $row->id }} </th>
        <td><a href="/domains/{{ $row->id }}">{{ $row->name }}</a></td>
        <td>{{ $row->created_at }}</td>
        <td>{{ $row->last_check }}</td>
        <td>{{ $row->status_code }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection