@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header"><strong>{{ __('Category') }}:</strong>{{$category->name}}</div>

                    <div class="card-body">
                        @foreach($category->products as $product)
                            <div class="card p-3">
                                <div class="d-flex justify-content-between align-items-center ">
                                    <div class="mt-2">
                                        <h4 class="text-uppercase">
                                            <strong>{{$product->type}}</strong> ({{$product->location}})</h4>
                                        <div class="mt-5">
                                            <h5 class="text-uppercase mb-0">{{$product->name}}</h5>
                                            @if(!empty($product->price))
                                                <h1 class="main-heading mt-0">{{$product->price}}€</h1>
                                            @endif
                                            <div class="d-flex flex-row user-tags">
                                                @foreach($product->tags as $tag)
                                                    <h6 class="text-muted ml-1 border border-info">{{$tag->name}}</h6>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                    <div class="image"><img src="{{$product->image}}" width="200"></div>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-2 mb-2"><span>Available colors</span>
                                </div>
                                <p>{{$product->body}} </p>
                            </div>
                        @endforeach
                    </div>
                    <a href="{{route('home')}}" class="btn btn-primary">{{__('Category')}}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
