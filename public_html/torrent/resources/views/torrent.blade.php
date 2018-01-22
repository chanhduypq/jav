@extends('layouts.app')

@section('content')
	
<div class="container">
    <div class="row">
        <form method="GET" action="{{ route('search') }}">
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

            @if ($page > 1 || ($torrents > $limit))
                <div class="list-group-item">
                    <h5>
                        Total Torrents:
                        <small class="text-muted">{{ $total_torrents }}</small>
                    </h3>
                </div>
            @endif

            @if (isset($torrents) && sizeof($torrents) > 0)
                <div class="list-group">
                    @foreach ($torrents as $key => $torrent)
                        @if ($torrent->getSize() > 0)
                            <a href="{{ $torrent->getLink() }}" class="list-group-item" target="_blank">
                                <h4 class="list-group-item-heading">{{ $torrent->getName() }}</h4>
                                @if ($torrent->getSeeds())
                                     <p>Seeds: {{ $torrent->getSeeds() }}</p>
                                @endif
                                <p>Size: {{ $torrent->convertSize($torrent->getSize()) }}</p>
                            </a>
                        @endif
                    @endforeach
                </div>
            @elseif ($search_terms!='')
                <div class="list-group">
                      no result  
                </div>
            @endif

            @if ($page > 1 || ($torrents > $limit))
                <ul class="pagination pagination-lg">
                    @for ($i = 1; $i <= $total_pages; $i++)
                      <li class="{{ $page == $i ? 'active' : '' }}">
                          <a href="{{ route('index.search', [
                              'search_terms' => $search_terms,
                              'page' => $i,
                          ]) }}">{{ $i }}</a>
                      </li>
                    @endfor
                </ul>
            @endif

        </form>
    </div>
</div>

@stop
