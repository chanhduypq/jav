@extends('layouts.app')

@section('content')
	
<div class="container">
    <div class="row">

        <form method="POST" action="{{ route('search') }}">
    
            <h2>Custom Search</h2>
            <div id="custom-search-input">
                <div class="input-group col-md-12">
                    <input type="text" class="  search-query form-control" name="search_terms" value="{{ $search_terms ?? null }}" />
                    <span class="input-group-btn">
                        <button class="btn btn-danger" type="submit">
                            <span class=" glyphicon glyphicon-search"></span>
                        </button>
                    </span>
                </div>
            </div>

              @if (isset($result) && sizeof($result) > 0)
                <div class="list-group">
                  @foreach ($result->items as $item)
                    <a href="{{ $item->link }}" class="list-group-item" target="_blank">
                        <h4 class="list-group-item-heading">{{ $item->title }}</h4>
                        <p class="list-group-item-text">{{ $item->snippet }}</p>
                    </a>
                  @endforeach
                </div>
                
            @endif


            @if ($clear_search === true || isset($result) && sizeof($result) > 0)
              @if (!$enabled_search)
                  <div class="list-group">
                      <p>{{ $error ?? null }}</p>
                  </div>
              @endif

              @if ($enabled_search && $real_next_page)
                  <ul class="pagination pagination-lg">
                        @for ($i = 1; $i <= $total_pages; $i++)
                          <li class="{{ $to_index == $i . 0 && $to_index <= ($i + 2 . 0) ? 'active' : '' }}">
                              <a href="{{ route('index.search', [
                                  'search_terms' => $search_terms,
                                  'to_index' => $i . 0,
                              ]) }}">{{ $i }}</a>
                          </li>
                        @endfor
                    </ul>
                @endif
            @endif

        </form>
    </div>
</div>

@stop
