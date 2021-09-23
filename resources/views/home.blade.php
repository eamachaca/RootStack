@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Categories') }}</div>

                    <div class="card-body">
                        <ul class="list-group">
                            @foreach($categories as $category)
                                <li class="list-group-item list-group-item-action"><a href="{{route('products',$category->id)}}">{{$category->name}}({{$category->products_count}})</a>
                                    <ul class="list-group">
                                        @foreach($category->children as $child)
                                            <li class="list-group-item list-group-item-action">
                                                <a href="{{route('products',$child->id)}}">{{$child->name}}({{$child->products_count}})</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>


                        {!! $categories->links() !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
