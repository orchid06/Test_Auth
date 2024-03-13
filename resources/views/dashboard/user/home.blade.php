@extends('layouts.user')

@section('content')

<body class="antialiased">
    <div class="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white">
        <div class="container mt-5">
            @if(session()->has('error'))
            <div class="alert alert-danger" role="alert">
                {{session()->get('error')}}
            </div>
            @endif

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            @if(session()->has('success'))
            <div class="alert alert-success" role="alert">
                {{session()->get('success')}}
            </div>
            @endif

            <div class="mb-3">
                <div class="row-3">
                    <div class="container">
                        <div class="row">
                            <div class="col-11">
                                <div class="mt-1 d-flex justify-content-between align-items-center">
                                    <form action="{{ route('product.search') }}" method="get">
                                        @csrf
                                        <div class="input-group">
                                            <input type="text" class="form-control" placeholder="Search product" name="search" id="search" style="width: 950px;">
                                            <div class="input-group-append">
                                                <button class="btn btn-secondary" type="submit">
                                                    <i class="fa fa-search"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- Cart Button -->
                                    <a href="{{ Auth::check() ? route('cart.index', ['user_id' => Auth::user()->id]) : '#' }}" class="btn btn-dark">
                                        My Cart
                                        <i class="bi bi-cart"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--Cards-->
            <div class="mb-3">
                <div class="container">
                    @if(count($products)>0)
                    <div class="row">
                        @forelse( $products as $product )
                        <div class="col-4">
                            <div class="mt-3">
                                <div class="card" style="width: 18rem;">
                                    <div class="card-body">
                                        <img src="{{url('uploads/'.$product->image)}}" style="width:250px; height:150px;">
                                        <h6 class="card-subtitle mb-1 text-muted"></h6>
                                        <a href="{{route('product.page', ['id'=>$product->id])}}" target="_blank">
                                            <h5>{{$product->title}}</h5>
                                        </a>
                                        <p class="card-text">{{$product->description}}</p>
                                        <h6 class="card-title">Price : {{$product->price}} BDT</h6>
                                        <h6 class="card-title mb-1 text-muted">In Stock: {{$product->qty}}</h6>
                                        <h6 class="card-title mb-1 text-muted">Discount: {{$product->discount}} {{$product->discountType}}</h6>
                                        <h6 class="card-title mb-1 text-muted">Discounted Price : {{$product->discountedPrice}}</h6>
                                        <!--addTOCart Buttons-->
                                        <h6 class="card-title" style="text-align:right;">
                                            <form action="{{ route('product.addToCart', ['id' => $product->id ]) }}" method="post" class="container">
                                                @csrf
                                                <div class="mt-3">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="input-group">
                                                                <input id="qty" min="1" name="qty" value="1" type="number" class="form-control form-control-sm" />
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="input-group">
                                                                <button type="submit" class="btn btn-dark" style="font-size: 12px;">Add To Cart</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </h6>

                                    </div>

                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="row">
                            <div class="col">
                                <h3>No Product Found</h3>
                            </div>
                        </div>
                        @endforelse
                        <div class="mt-5">
                            <nav aria-label="Page navigation example">
                                <ul class="pagination justify-content-center">
                                    <!-- Previous Page Link -->
                                    @if ($products->onFirstPage())
                                    <li class="page-item disabled">
                                        <a class="page-link" href="#" tabindex="-1">Previous</a>
                                    </li>
                                    @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $products->previousPageUrl() }}" tabindex="-1">Previous</a>
                                    </li>
                                    @endif

                                    <!-- Pagination Elements -->
                                    @for ($i = 1; $i <= $products->lastPage(); $i++)
                                        <li class="page-item {{ $i == $products->currentPage() ? 'active' : '' }}">
                                            <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                                        </li>
                                        @endfor

                                        <!-- Next Page Link -->
                                        @if ($products->hasMorePages())
                                        <li class="page-item">
                                            <a class="page-link" href="{{ $products->nextPageUrl() }}">Next</a>
                                        </li>
                                        @else
                                        <li class="page-item disabled">
                                            <span class="page-link">Next</span>
                                        </li>
                                        @endif
                                </ul>
                            </nav>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</body>
@endsection