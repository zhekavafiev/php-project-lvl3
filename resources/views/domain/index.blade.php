@extends('layouts.app')

@section('content')
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