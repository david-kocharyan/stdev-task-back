@extends('layouts.admin')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{$title}}</h3>

                    <div class="card-tools">
                        <div class="input-group input-group-sm">
                            <a class="btn btn-outline-secondary" href="{{"/categories/create"}}">Create New Category</a>
                        </div>
                    </div>
                </div>
                <!-- /.card-header -->
                <div class="card-body table-responsive p-0">
                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Created at</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $key=>$val)
                            <tr>
                                <td>{{$key + 1}}</td>
                                <td>{{$val->name}}</td>
                                <td>{{$val->created_at}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    {{$data->links('vendor.pagination.bootstrap-4')}}
                </div>
            </div>
            <!-- /.card -->
        </div>
    </div>
@endsection
