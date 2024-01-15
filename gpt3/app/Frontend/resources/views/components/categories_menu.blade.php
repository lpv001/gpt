@if (@$categories)
<div class="card rounded" style="width: 100%; height:100%">
    <h6 class="card-title px-3 pt-3 pb-0">
        <span class="mr-2"><i class="fa fa-list-ul" aria-hidden="true"></i></span> 
        {{ __('frontend.categories') }}
      </h6>
        <div class="category-list-group" >
            @foreach ($categories as $item)
                <dl class="cl-item mb-0 py-1">
                    <dt class="px-1">
                        <span>
                            <img class="mr-2" style="width: 20%" src="{{ $item['image_name'] }}" alt="">
                        </span>
                        <a href="{{ route('search.category', $item['id']) }}" class="font-weight-normal">{{$item['default_name']}}</a>
                    </dt>
                    @if (count($item['sub_categories']) > 0)
                    <dd class="sub-cat">
                        <div class="sub-cat-main">
                            <div class="sub-cate-content">
                                <div class="row py-3">
                                    <div class="col-9">
                                        <div class="row">
                                            @foreach ($item['sub_categories'] as $sub_cat)
                                                <div class="col-4 mb-3">
                                                    <dl class="mb-0">
                                                        <dt style="font-weight: 900">
                                                            <img class="mr-2" style="width: 35%" src="{{ $sub_cat['image_name'] }}" alt="">
                                                                <a class="sub-cate-title" href="{{ route('search.category', $sub_cat['id']) }}">
                                                                        {{$sub_cat['default_name']}}
                                                                </a>
                                                        </dt>
                                                        <dd class="pl-3">
                                                            <div class="d-flex flex-column" style="word-wrap: break-word;">
                                                                @if (count($sub_cat['sub_categories']) > 0)
                                                                    @foreach ($sub_cat['sub_categories'] as $sub_cat2)
                                                                        <small class="">
                                                                            <a class="sub-cate-title" href="{{ route('search.category', $sub_cat2['id']) }}">{{$sub_cat2['default_name']}}</a>
                                                                        </small>
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                        </dd>
                                                    </dl>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>  
                                </div>
                            </div>
                        </div>
                    </dd>
                    @endif
                </dl>
            @endforeach
        </div>
    
  </div>
@endif