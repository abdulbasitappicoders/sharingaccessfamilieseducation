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
    </style>
@endsection
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet"/>
    @if(Session::has('message'))
        <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
    @endif
    <div class="tab-content">
        <div class="tab-pane active" id="Staff-all">
            <div class="card">
                <div class="table-responsive">
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
                    <div class="all-users row">
                        <h4 class="text-dark font-weight-bold col-9">Staff</h4>
                        @if($staffs->count() == 0)
                        <button type="button" class="btn btn-primary setBtn col-3" data-toggle="modal"
                                data-target="#exampleModal">
                            Add New Staff
                        </button>
                        @endif
                        <br>
                        <table class="table table-hover table-vcenter text-nowrap table-striped mb-0" id="faqs"
                               style="width: 1400px;">
                            <thead class="bg-dark">
                            <tr>
                                <th class="text-white">S No</th>
                                <th class="text-white">First Name</th>
                                <th class="text-white">Last Name</th>
                                <th class="text-white">Username</th>
                                <th class="text-white">Email</th>
                                <th class="text-white">Gender</th>
{{--                                <th class="text-white">Category</th>--}}
                                <th class="text-white">Status</th>
                                <th class="text-white">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php
                                $i = 1;
                            @endphp
                            @forelse($staffs as $user)
                                <tr>
                                    <td>{{$i++}}</td>
                                    <td>{{$user->first_name??'N/A'}}</td>
                                    <td>{{$user->last_name??'N/A'}}</td>
                                    <td>{{$user->username??'N/A'}}</td>
                                    <td>{{$user->email??'N/A'}}</td>
                                    <td>{{strtoupper($user->gender)??'N/A'}}</td>
{{--                                    <td>{{$user->supportCategory ? $user->supportCategory->name : '-'}}</td>--}}
                                    <td>{{($user->status == 1)?'Active':"In Active"}}</td>
                                    <td>
                                        <div style="display: flex;">
                                            <button class="btn btn-primary btn-sm setBtn ml-3" data-toggle="modal"
                                                    data-target="#exampleModal2" data-id="{{ $user->id }}" id="edit"
                                                    href="">Edit
                                            </button>
                                            <form action="{{ route('admin.delete_staff',$user->id) }}" id="deleteform"
                                                  method="POST">
                                                @method('DELETE')
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm btnSpace"
                                                        id="delete_btn" role="button"
                                                        style=" display: block ruby;">Delete
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                            @endforelse
                            </tbody>
                        </table>
                        <br>
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
                        <h5 class="modal-title" id="exampleModalLabel">Add New Staff</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body newfqbody">
                        <form action="{{ route('admin.insert_staff') }}" method="POST" name="add_staff">
                            @csrf
                            <div class="row">
                                <div class="col-12 container-fluid">
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="selects">First Name</label>
                                            <input type="text" class="form-control" id="first_name" name="first_name">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="selects">Last Name</label>
                                            <input type="text" class="form-control" id="last_name" name="last_name">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="selects">Email</label>
                                            <input type="email" class="form-control" id="email" name="email">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="selects">Gender</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="gender"
                                                       value="male">
                                                <label class="form-check-label" for="gender">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="gender2"
                                                       value="female">
                                                <label class="form-check-label" for="gender2">Female</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="gender" id="gender3"
                                                       value="other">
                                                <label class="form-check-label" for="gender3">Other</label>
                                            </div>
                                        </div>
                                    </div>
                                    {{--<div class="mb-3">
                                        <div class="form-group">
                                            <label for="selects">Support Category</label>
                                            <select class="selects form-control" name="support_category_id"
                                                    id="selects">
                                                <option value="" selected disabled>Select Support Category</option>
                                                @foreach($faq_categories as $faq)
                                                    <option value="{{ $faq->id }}">{{ $faq->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>--}}

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="selects">Password</label>
                                            <input type="password" class="form-control" id="password" name="password"
                                                   value="">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="selects">Confirm Password</label>
                                            <input type="password" class="form-control" id="confirm_password"
                                                   name="confirm_password" value="">
                                        </div>
                                    </div>


                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Save</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        {{-- edit faq ans:--}}
        <div class="modal fade" id="exampleModal2" tabindex="-1" aria-labelledby="exampleModalLabel2"
             aria-hidden="true">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header newfqheading">
                        <h5 class="modal-title" id="exampleModalLabel">Edit Staff</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body newfqbody">
                        <form action="{{ route('admin.update_staff') }}" method="POST" name="add_staff">
                            @csrf
                            <input type="hidden" name="id" id="id" value="">
                            <div class="row">
                                <div class="col-12 container-fluid">
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="selects">First Name</label>
                                            <input type="text" class="form-control" id="first_name1" name="first_name"
                                                   value="">
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="selects">Last Name</label>
                                            <input type="text" class="form-control" id="last_name1" name="last_name"
                                                   value="">
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="selects">Email</label>
                                            <input type="email" class="form-control" id="email1" name="email">
                                        </div>
                                    </div>

                                    {{--<div class="mb-3">
                                        <div class="form-group">
                                            <label for="selects">Gender</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="staffgender" id="gender1"
                                                    value="male">
                                                <label class="form-check-label" for="gender1">Male</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="staffgender" id="gender2"
                                                    value="female">
                                                <label class="form-check-label" for="gender2">Female</label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="staffgender" id="gender3"
                                                    value="other">
                                                <label class="form-check-label" for="gender3">Other</label>
                                            </div>
                                        </div>
                                    </div>--}}

                                    {{--<div class="mb-3">
                                        <div class="form-group">
                                            <label for="selects">Support Category</label>
                                            <select class="selects form-control" name="support_category_id"
                                                    id="selects">

                                            </select>
                                        </div>
                                    </div>--}}
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

        <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function () {
                $('#faqs').DataTable();
            });

            $(document).on("click", '#edit', function () {

                var id = $(this).data("id");

                var url = '{{ route('admin.edit_staff',':id') }}';
                url = url.replace(':id', id);
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
                        console.log(data);
                        console.log(data);
                        $('#id').val(data.user_staff.id);
                        $('#first_name1').val(data.user_staff.first_name);
                        $('#last_name1').val(data.user_staff.last_name);
                        $('#username1').val(data.user_staff.username);
                        $('#email1').val(data.user_staff.email);
                        $("select[name='support_category_id']").html(data.options);

                        var $radios = $('input:radio[name=staffgender]');
                        if ($radios.is(':checked') === false) {
                            var value = data.user_staff.gender;
                            $radios.filter("[value='" + value + "']").prop('checked', true);
                        }
                    },
                    error: function (data) {
                        console.log(data);
                    }
                });

            });

            /*$(document).on('click', '#delete_btn', function (e) {
                e.preventDefault(false);
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
                            'Staff deleted successfully.',
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
