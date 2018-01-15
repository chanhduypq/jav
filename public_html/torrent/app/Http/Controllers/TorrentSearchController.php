<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TorrentSearchController extends Controller
{
    public $limit = 50;
    public $page = 1;
    public $totalPages = 1;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Search torrents
     *
     * @param \Request $request
     * @return \view
     */
    public function search(Request $request)
    {
        if ($searchTerms = $request->post('search_terms')) {

            $searchTermsFiltred = urlencode(trim($searchTerms));
            $this->page = $request->get('page') ?? $this->page;

            $axon = new \App\Helpers\Axon\Search();
            $axon->registerProvider(new \App\Helpers\Axon\Search\Provider\YifyProvider());
            $axon->registerProvider(new \App\Helpers\Axon\Search\Provider\KickassProvider());
            $axon->registerProvider(new \App\Helpers\Axon\Search\Provider\PirateBayProvider());
            // $axon->registerProvider(new \App\Helpers\Axon\Search\Provider\EztvProvider());

            $torrents = $axon->search($searchTermsFiltred);

            $slice = array_slice($torrents, $this->page == 1 ? 0 : (($this->limit * $this->page) - $this->limit), $this->limit);

            $totalTorrents = sizeof($torrents);
            $this->totalPages = ceil($totalTorrents / $this->limit);
        }

        return view('torrent', [
            'torrents' => $slice ?? null,
            'page' => $this->page,
            'total_torrents' => $totalTorrents ?? 0,
            'total_pages' => $this->totalPages >= $this->page ? $this->totalPages : $this->page,
            'search_terms' => $searchTerms,
            'limit' => $this->limit,
        ]);
    }

    public function googleSearch(Request $request)
    {
        $result = [];
        $params = [];
        $toIndex = 10;

        if ($searchTerms = $request->post('search_terms')) {

            $searchTermsFiltred = urlencode(str_replace(' ', '+', trim($searchTerms)));

            $toIndex = $request->post('to_index') ?? 10;
            
            $key = "AIzaSyAT-cuMb6QLmqwEnT0sEDYRlxnKsdmTBDg";
            $cx = "007043409519568967944:zqts7n3gnc4";

            $url = "https://www.googleapis.com/customsearch/v1?start={$toIndex}&key={$key}&cx={$cx}&q={$searchTermsFiltred}";

            $ch = curl_init($url);
            curl_setopt( $ch , CURLOPT_SSL_VERIFYPEER , false );
            curl_setopt( $ch , CURLOPT_RETURNTRANSFER , 1 );
            $result = curl_exec($ch);        
            curl_close($ch);

            $result = json_decode($result);

            // echo "<pre>";
            // print_r($result); die();

            if (isset($result->error) && $result->error->code == 400) {
                return view('home', [
                    'enabled_search' => true,
                    'result' => null,
                    'search_terms' => $searchTerms ?? '',
                    'next_page' => null,
                    'to_index' => $toIndex,
                    'clear_search' => true,
                    'error' => 'Bad request',
                    'total_pages' => $this->totalPages,
                    'real_next_page' => $nextPage ?? true,
                ]);
            }

            if (!isset($result->items)) {
                return view('home', [
                    'enabled_search' => $toIndex == 10 && $result ? true : false,
                    'result' => null,
                    'search_terms' => $searchTerms ?? '',
                    'next_page' => null,
                    'real_next_page' => $nextPage ?? false,
                    'to_index' => $toIndex,
                    'clear_search' => true,
                    'total_pages' => $this->totalPages,
                ]);
            }

            $nextPageArray = $result->queries->nextPage;

            $nextPage = false;

            if ($nextPageArray && sizeof($nextPageArray) > 0) {
                $nextPage = true;
            }

            // echo "<pre>";
            // print_r($result);
            // die();;
        }

        return view('home', [
            'result' => $result ?? null,
            'search_terms' => $searchTerms ?? '',
            'params' => $params,
            'next_page' => $nextPage ?? 11,
            'real_next_page' => $nextPage ?? false,
            'enabled_search' => true,
            'total_pages' => $this->totalPages,
            'to_index' => $toIndex,
            'clear_search' => true,
        ]);
    }

}
