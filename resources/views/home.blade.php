@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Phone Number</th>
                                <th>Action</th>
                            </thead>
                            <tbody>
                    @if(isset($users))
                        @foreach($users as $key => $user)
                                <tr>
                                    <td>{!! $user->first_name !!}</td>
                                    <td>{!! $user->last_name !!}</td>
                                    <td>{!! $user->user_name !!}</td>
                                    <td>{!! $user->email !!}</td>
                                    <td>{!! $user->phone_number !!}</td>
                                    <td>
                                        <a href="{{ url('/edit/'.$user->id) }}" class='btn btn-default btn-xs'>Edit</a> | 
                                        <a href="{{ url('/deleted/'.$user->id) }}" onclick="return confirm('Are you sure?')" class='btn btn-danger btn-xs'>Delete</a>

                                    </td>
                                    
                                </tr>
                        @endforeach
                    @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
