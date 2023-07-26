@extends('layouts.master')

@section('content')
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
@endif
<div class="all-users row">
    <h4 class="text-dark font-weight-bold col-9">Help</h4>
</div>
<div class="card">
    <form action="{{route('admin.update_help')}}" method="POST">
    @csrf
        <input type="hidden" name="id" value="{{$help->id}}">
        <textarea name="help" id="editor" cols="15" rows="25" class="summernote">{{$help->help}}</textarea>
        <div class="modal-footer d-flex justify-content-center">
            <button class="btn btn-dark" >Save</button>
        </div>
    </form>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>

  $(document).ready(function () {
      $('.summernote').summernote();
  })

</script>
@endsection
