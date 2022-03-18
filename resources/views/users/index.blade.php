@extends('users.layout')

@section('content')
<div class="row">
    <div class="col-lg-12 margin-tb">
        <div class="pull-left">
            <h2>Data User</h2>
        </div>
        <div class="pull-right">
            <a class="btn btn-success" href="{{ route('users.create') }}"> Create New User</a>
        </div>
    </div>
</div>

@if ($message = Session::get('success'))
<div class=" alert alert-success">
    <p>{{ $message }}</p>
</div>
@endif

<table class="table table-bordered">
    <tr>
        <th>No</th>
        <th>First Name</th>
        <th>Last Name</th>
        <th width="280px">Action</th>
    </tr>
    @php
    $number = 1;
    @endphp
    @forelse($users['data'] as $user)
    <tr>
        <td>{{ $number++ }}</td>
        <td>{{ $user['firstName'] }}</td>
        <td>{{ $user['lastName'] }}</td>
        <td>

            <form action="{{ route('users.destroy', $user['id']) }}" method=" POST" onsubmit="return confirm('Are you sure to delete this user?');">
                <a class="btn btn-primary" href="{{ route('users.edit', $user['id']) }}">Edit</a>
                @csrf
                @method('DELETE')
                <button class="btn btn-danger">Delete</button>
            </form>

        </td>
    </tr>
    @empty
    <tr>
        <td colspan="6" align="center">No Record(s) Found!</td>
    </tr>
    @endforelse
</table>

@endsection