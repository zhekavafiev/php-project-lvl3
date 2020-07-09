@extends('layouts.app')

@section('content')
@if(Session::has('message'))
  <div class="alert alert-success">  {{ Session::get('message') }}</div>
@endif
@if(Session::has('errors'))
  <div class="alert alert-danger">  {{ Session::get('errors') }}</div>
@endif

{{ $domains->links() }}
<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">id</th>
      <th scope="col">Name</th>
      <th scope="col">Create</th>
      <th scope="col">Last Check</th>
      <th scope="col">Last Status</th>
    </tr>
  </thead>
  <tbody>
    @foreach ($domains as $domain)
      <tr>
        <th scope="row"> {{ $domain->id }} </th>
        <td><a href="{{route('domains.show', $domain->id)}}">{{ $domain->name }}</a></td>
        <td>{{ $domain->created_at }}</td>
        <td>{{ $checks[$domain->id]->created_at ?? null}}</td>
        <td>{{ $checks[$domain->id]->status_code ?? null}}</td>
      </tr>
    @endforeach
  </tbody>
</table>
@endsection