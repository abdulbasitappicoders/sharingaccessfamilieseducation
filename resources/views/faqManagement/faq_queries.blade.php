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
                                        <select name="category_id" id="categories" class="form-control myselect" style="width: 200px">
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
                        <table class="table table-hover table-vcenter text-nowrap table-striped mb-0" id="faqs" style="width: 968px;">
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
                                        <td><a class='btn btn-success' href="{{ route('admin.faq_querie_chat',['id'=>encrypt($chatlist->id)]) }}">View</a></td>
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
