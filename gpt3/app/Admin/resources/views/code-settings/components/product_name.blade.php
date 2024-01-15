<table class="table">
    <thead class="thead-light">
        <tr>
            <th scope="col" width="2%">#</th>
            <th scope="col" width="18%">Language</th>
            <th scope="col" width="">Name</th>
            <th scope="col" width="">Description</th>
            <th scope="col" width="10%"><span class=" pull-left"><a href="#" class="btn btn-sm btn-primary pull-right add-more-product"><i class="fa fa-plus"></i></a></th>
        </tr>
    </thead>
    <tbody id="product_translation">
        @if (old('name'))
            @foreach (old('name') as $key => $value)
                @php
                    $lang = [];
                    if (old('lang')[$key] === 'en')
                        $lang = ['en' =>    'English'];

                    if (old('lang')[$key] === 'km')
                        $lang = ['en' =>    'Khmer'];
                @endphp

                <tr>
                    <td class="py-4">1</td>
                    <td>
                        {!! Form::select('lang', $lang, old('lang')[$key], ['class' => 'form-control select2', 'required']) !!}
                        {{-- {!! Form::hidden('lang[]', 'en', []) !!} --}}
                    </td>
                    <td>
                        <input class="form-control" type="text" value="{{$value}}" name="name[]" required>
                    </td>
                    <td>
                        {{-- {{ old('description') ? dd(old('description')) : ''  }} --}}
                        <textarea class="form-control" name="description[]" cols="30" rows="4" required> {{ old('description') ? old('description')[$key] : ''  }}</textarea>
                    </td>
                    <td><a class="btn btn-sm btn-light" {{$key === 0 ? 'disabled' : ''}} disabled><i class="fa fa-minus"></i></a></td>
                </tr>
            @endforeach

        @else

        @if (@$product_translation)
            @foreach (@$product_translation as $key => $item)
                {{-- @if ($key === 'en' || $key === 'km') --}}
                    <tr>
                        <td class="py-4">1</td>
                        <td>
                            {!! Form::select('lang_', ['en' => 'English', 'km' => 'Khmer'], @$item->locale, ['class' => 'form-control select2', 'required', 'disabled']) !!}
                            {!! Form::hidden('lang[]', @$item->locale, []) !!}
                            {!! Form::hidden('pid_tranlsate[]', @$item->id, []) !!}
                        </td>
                        <td>
                            <input class="form-control" type="text" value="{{@$item->name ?? ''}}" name="name[]" required>
                        </td>
                        <td>
                            <textarea class="form-control" name="description[]" cols="30" rows="1" required>{{@$item->description ?? ''}}</textarea>
                        </td>
                        <td><a class="btn btn-sm btn-light" disabled><i class="fa fa-minus"></i></a></td>
                    </tr>
                {{-- @endif --}}
            @endforeach
        @else
            <tr>
                <td class="py-4">1</td>
                <td>
                    {!! Form::select('lang_', ['en' => 'English'], 'en', ['class' => 'form-control select2', 'required']) !!}
                    {!! Form::hidden('lang[]', 'en', []) !!}
                </td>
                <td>
                    {{-- {{ Request::old('name') ? dd(old('name')) : ''}} --}}
                    <input class="form-control" type="text" value="{{ old('name') ? old('name')[0] : '' }}" name="name[]" required>
                </td>
                <td>
                    {{-- {{ old('description') ? dd(old('description')) : ''  }} --}}
                    <textarea class="form-control" name="description[]" cols="30" rows="1" required> {{ old('description') ? old('description')[0] : ''  }}</textarea>
                </td>
                <td><a class="btn btn-sm btn-light" disabled><i class="fa fa-minus"></i></a></td>
            </tr>
        @endif

        @endif
    </tbody>
</table>