@extends('layouts.master')

@section('content')
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
@endif
<div class="all-users row">
    <h4 class="text-dark font-weight-bold col-9">Term And Service</h4>
</div>
<div class="card">
    <form action="{{route('admin.update_termsCondition')}}" method="POST">
    @csrf
        <input type="hidden" name="id" value="{{$termsCondition->id}}">
        <textarea class="summernote" name="termsCondition" id="editor" cols="15" rows="25">{{$termsCondition->termsCondition}}</textarea>
        <div class="modal-footer d-flex justify-content-center">
            <button class="btn btn-dark" >Save</button>
        </div>
    </form>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  /*tinymce.init({
    selector: 'textarea#editor',
    menubar: false
  });*/
  $(document).ready(function () {
      $('.summernote').summernote();
  })
</script>
@endsection
