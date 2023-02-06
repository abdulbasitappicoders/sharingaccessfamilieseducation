@extends('layouts.master')

@section('content')
    @if(Session::has('message'))
        <p class="alert {{ Session::get('alert-class', 'alert-success') }}">{{ Session::get('message') }}</p>
    @endif
    <div class="tab-content">
        <div class="tab-pane active" id="Staff-all">
            <div class="card">
                <div class="table-responsive">
                    <div class="all-users row">
                        <h4 class="text-dark font-weight-bold col-9">Faq Answers</h4>
                    </div>
                    <table class="table table-hover table-vcenter text-nowrap table-striped mb-0">
                        <thead class="bg-dark">
                        <tr>
                            <th class="text-white">S No</th>
                            <th class="text-white">Category Name</th>
                            <th class="text-white">Faq Question</th>
                            <th class="text-white">Faq Answer</th>
                            <th class="text-white">Created At</th>
                            <th class="text-white">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($faqs as $faq)
                            <tr>
                                <td>{{$faq->id??''}}</td>
                                <td>{{$faq->faqCategory->name??'N/A'}}</td>
                                <td>{{$faq->question??'N/A'}}</td>
                                <td>{{$faq->answer??'N/A'}}</td>
                                <td>{{$faq->created_at??'N/A'}}</td>
                                <td>
                                    <a class="btn btn-danger" href="#">Delete</a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <br>
                    {{--                    {{ $faq_categories->links() }}--}}
                    <br>
                </div>
            </div>
        </div>
    </div>
@endsection
