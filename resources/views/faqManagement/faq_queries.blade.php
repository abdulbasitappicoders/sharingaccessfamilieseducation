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
        .setBtn{
            color: #fff;
            background-color: #000;
            border-color: #000;
        }
        .btnSpace{
            margin-left: 10px;
        }
        .bgcolor{
            color: #fff;
            background-color: #000;
            border-color: #000;
        }
    </style>
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
@if(Session::has('message'))
<p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
@endif
<div class="tab-content">
    <div class="tab-pane active" id="Staff-all">
        <div class="card">
            <div class="table-responsive">
                <div class="all-users row">
                    <h4 class="text-dark font-weight-bold col-9">Faq Queries</h4>
                    <div class="card ">
                        <div class="card-body">
                            <div class="form-group fitler">
                                <form action="{{route('admin.faq_queries')}}" id="faqForm">
                                    <select name="category_id" id="categories" class="form-control myselect"
                                        style="width: 200px">
                                        <option value="" selected disabled>Select Support Category</option>
                                        @forelse($faq_categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                        @empty
                                        @endforelse
                                    </select>
                                </form>
                            </div>
                        </div>
                    </div>

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
                        style="width: 1400px; margin:10px;git status">
                        <thead class="bg-dark">
                            <tr>
                                <th class="text-white">S No</th>
                                <th class="text-white">Support Category </th>
                                <th class="text-white">Staff User</th>
                                <th class="text-white">Query User</th>
                                <th class="text-white">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                                @foreach($chatlists as $chatlist)
                                    <tr>
                                        <td>{{$chatlist->id}}</td>
                                        <td>{{$chatlist->category ? $chatlist->category->name : null}}</td>
                                        <td>{{$chatlist->fromUser ? $chatlist->fromUser->username : null}}</td>
                                        <td>{{$chatlist->toUser ? $chatlist->toUser->username : null}}</td>
                                        <td><a class='btn btn-primary bgcolor' href="{{ route('admin.faq_querie_chat',['id'=>encrypt($chatlist->id)]) }}"><i class="fa fa-eye" aria-hidden="true"></i></a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                        <br>
                    </div>
                        </thead>
                        <tbody>
                            @foreach($chatlists as $chatlist)
                            <tr>
                                <td>{{$chatlist->id}}</td>
                                <td>{{$chatlist->category ? $chatlist->category->name : null}}</td>
                                <td>{{$chatlist->fromUser ? $chatlist->fromUser->username : null}}</td>
                                <td>{{$chatlist->toUser ? $chatlist->toUser->username : null}}</td>
                                <td><a class='btn btn-success' href="#">View</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <br>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog ">
            <div class="modal-content">
                <div class="modal-header newfqheading">
                    <h5 class="modal-title" id="exampleModalLabel">Add New Faq Answer</h5>
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

    <script type="text/javascript">
        $(document).ready(function() {
                $('#faqs').DataTable();
            });

            $(document).ready(function () {
                $(document).on("change",'#categories',function () {
                    $('#faqForm').trigger('submit');
                })
            });
    </script>

    @endsection
