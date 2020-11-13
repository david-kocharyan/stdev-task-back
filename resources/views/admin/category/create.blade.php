@extends('layouts.admin')
@section('content')


    <div class="col-md-12">
        <!-- general form elements disabled -->
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title text-uppercase">Create Categories</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body">
                <form method="post" action="/categories"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Name</label>
                        @error('name')
                        <p class="text-danger" role="alert">
                            <i class="far fa-times-circle"></i>
                            <strong>{{ $message }}</strong>
                        </p>
                        @enderror
                        <input type="text" name="name" class="form-control @error('title') is-invalid @enderror" placeholder="Name ..." value="{{old('name')}}">
                    </div>

                    <button type="submit" class="btn btn-primary col-md-12">Save</button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('footer')
    <script src="{{asset('admin/plugins/bs-custom-file-input/bs-custom-file-input.min.js')}}"></script>
    <script !src="">
        $(function () {
            bsCustomFileInput.init();
        });
    </script>
@endpush
