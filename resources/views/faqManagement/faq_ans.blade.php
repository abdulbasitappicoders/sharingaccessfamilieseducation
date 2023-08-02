@extends('layouts.master')
@section('style')
<style>
    .newfqheading h5 {
        color: white;
        font-weight: 600;
    }

    .newfqbody {
        padding: 20px;
    }

    .setBtn {
        color: #fff;
        background-color: #000;
        border-color: #000;
    }

    .btnSpace {
        margin-left: 10px;
    }

    .tabwith {
        width: 100% !important;
    }
</style>
@endsection
@section('content')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
@endif
<div class="tab-content ">
    <div class="tab-pane active" id="Staff-all">
        <div class="card">
            <div class="table-responsive">
                <div class="all-users row ">
                    <h4 class="text-dark font-weight-bold col-9">FAQ Answers</h4>
                    <button type="button" class="btn btn-primary setBtn col-3" data-toggle="modal"
                        data-target="#exampleModal">
                        Add New
                    </button>
                    <br>
                    <div class="col-md-12 mt-3">
                        @if(Session::has('success'))
                        <div class="alert alert-success alert-dismissible" style="text-align:center;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
                            <strong>Success!</strong>
                            <?= htmlentities(Session::get('success'))?>
                        </div>
                        @endif
                        @if(Session::has('error'))
                        <div class="alert alert-danger alert-dismissible" style="text-align:center;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
                            <strong>Error!</strong>
                            <?= htmlentities(Session::get('error'))?>
                        </div>
                        @endif

                        @if($errors->any())
                        <div class="alert alert-danger alert-dismissible" style="text-align:center;">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close"></a>
                            <p><strong>Whoops!</strong> Please correct errors and try again!</p>
                            @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    <table class="table table-hover table-vcenter text-nowrap table-striped mb-0" id="faqs"
                        >
                        <thead class="bg-dark">
                            <tr>
                                <th class="text-white">S No</th>
                                <th class="text-white">Category Name</th>
                                <th class="text-white">FAQ Question</th>
                                <th class="text-white">FAQ Answer</th>
                                <th class="text-white">Created At</th>
                                <th class="text-white">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $i = 1;
                            @endphp
                            @foreach($faqs as $faq)

                            <tr>
                                <td>{{$i++}}</td>
                                <td>{{ $faq->faqCategory->name??'N/A' }}</td>
                                <td>{{ $faq->question??'N/A' }}</td>
                                <td>
                                    <p data-id="{{ $faq->answer }}" id="read" data-toggle="modal"
                                        data-target="#exampleModal3">
                                        {{ (strlen($faq->answer) > 20)?substr($faq->answer, 0, 20)." ... Read More
                                        ":$faq->answer??'N/A' }}
                                    </p>
                                </td>
                                <td>{{ formattedDate($faq->created_at) ?? 'N/A' }}</td>
                                <td>
                                    <div style="display: flex;">
                                        <button class="btn btn-primary btn-sm setBtn" data-toggle="modal"
                                            data-target="#exampleModal2" data-id="{{ $faq->id }}" id="edit"
                                            href="">Edit</button>
                                        <form action="{{ route('admin.delete_faq_answers',$faq->id) }}" id="deleteform" method="POST">
                                            @method('DELETE')
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-sm btnSpace" id="delete_btn" role="button"
                                                style=" display: block ruby;">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    {{-- {{ $faq_categories->links() }}--}}
                    <br>
                </div>
            </div>
        </div>
    </div>

    {{-- add faq ans:--}}
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            {{-- modal-dialog-centered--}}
            <div class="modal-content">
                <div class="modal-header newfqheading">
                    <h5 class="modal-title" id="exampleModalLabel">Add New FAQ Answer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body newfqbody">
                    <form action="{{ route('admin.insert_faq_answers') }}" method="POST" name="add_faq">
                        @csrf
                        <div class="row">
                            <div class="col-12 container-fluid">
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="selects">Category Name</label>
                                        <select class="selects form-control" name="faq_category_id" id="selects">
                                            <option value="">Select </option>
                                            @foreach($faq_categories as $faq)
                                            <option value="{{ $faq->id }}">{{ $faq->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">FAQ Question </label>
                                        <textarea placeholder="Enter Description" class="form-control " name="question"
                                            id="description" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">FAQ Answer </label>
                                        <textarea placeholder="Enter Description" class="form-control " name="answer"
                                            id="description" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    {{-- edit faq ans:--}}
    <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2" aria-hidden="true">
        <div class="modal-dialog ">
            {{-- modal-dialog-centered--}}
            <div class="modal-content">
                <div class="modal-header newfqheading">
                    <h5 class="modal-title" id="exampleModalLabel">Edit FAQ Answer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body newfqbody">
                    <form action="{{ route('admin.update_faq_answers') }}" method="POST" name="add_faq"
                        id="edit_faq_answers">
                        @csrf
                        <input type="hidden" name="id" id="id" value="">
                        <div class="row">
                            <div class="col-12 container-fluid">
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="selects">Category Name</label>
                                        <select class="selects form-control" name="faq_category_id" id="selects">
                                            <div id="category_faq" class="category_faq">

                                            </div>
                                        </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">FAQ Question </label>
                                        <textarea placeholder="Enter Description" class="form-control" name="questions"
                                            id="faq_question" rows="3"></textarea>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <div class="form-group">
                                        <label for="recipient-name" class="col-form-label">FAQ Answer </label>
                                        <textarea placeholder="Enter Description" class="form-control " name="answers"
                                            id="faq_answer" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal3" tabindex="-1" aria-labelledby="exampleModalLabel3" aria-hidden="true">
        <div class="modal-dialog ">
            {{-- modal-dialog-centered--}}
            <div class="modal-content">
                <div class="modal-header newfqheading">
                    <h5 class="modal-title" id="exampleModalLabel">FAQ Answer </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body newfqbody">
                    <p id="read_more">
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
                $('#faqs').DataTable();
            });

            $(document).on("click",'#read',function () {
                 var str = $(this).data('id').length;
                 if (str >= 20){
                     $("#read").css({"cursor":"pointer"});
                 }
                 $("#read_more").text($(this).data('id'));
            });

            $(document).on("click",'#edit',function () {

                var id = $(this).data("id");

                var url = '{{ route('admin.edit_faq_answers',':id') }}';
                url = url.replace(':id', id );
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
                    }
                });

                $.ajax({
                    type: "GET",
                    url: url,
                    success: function (data) {
                        // alert('work')
                        console.log(data.faqs);
                        console.log(data.options);
                        $('#id').val(data.faqs.id);
                        $("textarea[name='questions']").val(data.faqs.question);
                        $("textarea[name='answers']").val(data.faqs.answer);
                        $("select[name='faq_category_id']").html(data.options);
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });

            });

            /*$(document).on('click','#delete_btn',function (e) {
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#deleteform').submit();
                        Swal.fire(
                            'Deleted!',
                            'deleted successfully.',
                            'success'
                        )
                    }
                });
        })*/

        $(document).ready(function () {
            $(document).on('click','#delete_btn',function(event){
                var form =  $(this).closest("form");
                var name = $(this).data("name");
                event.preventDefault();
                swal({
                    title: "Are you sure?",
                    text: "You want to Delete it!",
                    icon: "warning",
                    type: "warning",
                    buttons: ["Cancel","Yes!"],
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((willDelete) => {
                    if (willDelete) {
                        form.submit();
                    }
                });
            });
        })
    </script>

    @endsection
