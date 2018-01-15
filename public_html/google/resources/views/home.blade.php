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

            @if (isset($result) && sizeof($result) > 0)
                <div class="list-group" style="width: 45%;float: left;margin-right: 10%;">
                  @foreach ($result->items as $item)
                    <a href="{{ $item->link }}" class="list-group-item" target="_blank">
                        <h4 class="list-group-item-heading">{{ $item->title }}</h4>
                        <p class="list-group-item-text">{{ $item->snippet }}</p>
                    </a>
                  @endforeach
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
                </div>
                
            @endif
            <?php if (isset($result1) && is_array($result1)&& count($result1) > 0){?>
                <div class="list-group" style="width: 45%;float: left;">
                  <?php for ($i=0;$i<count($result1);$i++){?>
                      
                    <a href="<?php echo recursiveFind($result1[$i],'FirstURL');?>" class="list-group-item" target="_blank">
                        <h4 class="list-group-item-heading"><?php echo recursiveFind($result1[$i],'Text');?></h4>
                        <p class="list-group-item-text"></p>
                    </a>
                    
                  <?php } ?>
                </div>
                
            <?php 
            }
            ?>


            @if ($clear_search === true || isset($result) && sizeof($result) > 0)
              @if (!$enabled_search)
                  <div class="list-group">
                      <p>{{ $error ?? null }}</p>
                  </div>
              @endif

              @if ($enabled_search && $real_next_page)
<!--                  <ul class="pagination pagination-lg">
                        @for ($i = 1; $i <= $total_pages; $i++)
                          <li class="{{ $to_index == $i . 0 && $to_index <= ($i + 2 . 0) ? 'active' : '' }}">
                              <a href="{{ route('index.search', [
                                  'search_terms' => $search_terms,
                                  'to_index' => $i . 0,
                              ]) }}">{{ $i }}</a>
                          </li>
                        @endfor
                    </ul>-->
                @endif
            @endif

        </form>
    </div>
</div>

@stop
<?php 
function recursiveFind(array $haystack, $needle)
{
    $iterator  = new RecursiveArrayIterator($haystack);
    $recursive = new RecursiveIteratorIterator(
        $iterator,
        RecursiveIteratorIterator::SELF_FIRST
    );
    foreach ($recursive as $key => $value) {
        if ($key === $needle) {
            return $value;
        }
    }
}
?>