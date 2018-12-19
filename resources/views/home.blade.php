@extends('layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            {{$abc}}
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Dashboard</div>
                    <div class="panel-body">
                        <a href="{{ url('/posts/create') }}" class="btn btn-primary">
                            Create A Post
                        </a>

                            <h3>Your Posts</h3>
                            <table class="table table-striped">
                                <tr>
                                    <th></th>
                                    <th>Title</th>
                                    <th></th>
                                    <th></th>
                                </tr>

                                    <tr>
                                        <td>


                                        </td>
                                        <td>

                                        </td>
                                        <td>
                                            <a href="/lsapp/public/posts//edit" class="btn btn-primary">
                                                Edit
                                            </a>
                                        </td>

                                    </tr>

                            </table>

                            <h3>You have no posts</h3>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
