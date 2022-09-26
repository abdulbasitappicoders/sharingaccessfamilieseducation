@extends('layouts.master')

@section('content')
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
@endif
<div class="card">
    <form action="{{route('admin.update_termsCondition')}}" method="POST">
    @csrf    
        <input type="hidden" name="id" value="{{$termsCondition->id}}">
        <textarea name="termsCondition" id="editor" cols="15" rows="25">{{$termsCondition->termsCondition}}</textarea>
        <div class="modal-footer d-flex justify-content-center">
            <button class="btn btn-dark" >Save</button>
        </div>
    </form>
</div>

<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/5/tinymce.min.js" referrerpolicy="origin"></script>
<script>
  tinymce.init({
    selector: 'textarea#editor',
    menubar: false
  });
</script>
@endsection