@extends('layouts.app')

@section('main')
    <div class="container">
        <div class="row my-5">
            <div class="col-md-3">
                <div class="card border-0 shadow-lg">
                    <div class="card-header  text-white">
                        Welcome, {{ Auth::user()->name }}
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            @if (Auth::user()->image != "")

                                <img src="{{ asset('uploads/profile/thumb/' . Auth::user()->image) }}"
                                    class="img-fluid rounded-circle" alt="Luna John">

                            @endif
                        </div>
                        <div class="h5 text-center">
                            <strong>{{ Auth::user()->name }}</strong>
                            <p class="h6 mt-2 text-muted">5 Reviews</p>
                        </div>
                    </div>
                </div>
                <div class="card border-0 shadow-lg mt-3">
                    <div class="card-header  text-white">
                        Navigation
                    </div>
                    <div class="card-body sidebar">

                        @include('layouts.sidebar')
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                @include('layouts.message')
                <div class="card border-0 shadow">
                    <div class="card-header  text-white">
                        Books
                    </div>
                    <div class="card-body pb-0">
                        <div class="d-flex justify-content-between">
                            <a href="{{route('books.create')}}" class="btn btn-primary">Add Book</a>

                            <form action="" method="get">
                                <div class="d-flex">
                                    <input type="text" class="form-control" value="{{Request::get('keyword')}}" name="keyword" placeholder="Keyword">
                                    <button type="submit"  class="btn btn-primary ms-2">Search</button>
                                    <a href="{{ route('books.index') }}" class="btn btn-secondary ms-2">Clear</a>
                                </div>
                            </form>


                        </div>

                        <table class="table  table-striped mt-3">
                            <thead class="table-dark">
                                <tr>
                                    <th>Title</th>
                                    <th>Author</th>
                                    <th>Rating</th>
                                    <th>Status</th>
                                    <th width="150">Action</th>
                                </tr>
                            <tbody>
                                @if ($books->isNotEmpty())
                                    @foreach ($books as $book)
                                        <tr>
                                            <td>{{$book->title}}</td>
                                            <td>{{$book->author}}</td>
                                            <td>3.0 (3 Reviews)</td>
                                            <td>
                                                @if ($book->status == 1)
                                                    <span class="text-success">Available</span>
                                                @else
                                                    <span class="text-danger">Not Available</span>

                                                @endif
                                            </td>
                                            <td>
                                                <a href="#" class="btn btn-success btn-sm"><i class="fa-regular fa-star"></i></a>
                                                <a href="edit-book.html" class="btn btn-primary btn-sm"><i
                                                        class="fa-regular fa-pen-to-square"></i>
                                                </a>
                                                <a href="" class="btn btn-danger btn-sm"><i class="fa-solid fa-trash"></i></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="5">Book not found</td>
                                    </tr>

                                @endif


                            </tbody>
                            </thead>
                        </table>
                        @if ($books->isNotEmpty())
                            {{$books->links()}}
                        @endif

                        {{-- <nav aria-label="Page navigation ">
                            <ul class="pagination">
                                <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">Next</a></li>
                            </ul>
                        </nav> --}}
                    </div>

                </div>
            </div>
        </div>


    </div>
    </div>
    </div>
@endsection