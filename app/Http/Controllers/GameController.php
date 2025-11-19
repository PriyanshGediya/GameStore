<?php

namespace App\Http\Controllers;

use App\Models\Game;
use App\Models\Genre;
use App\Models\MembershipPlan; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// ONLY ONE CLASS DEFINITION
class GameController extends Controller 
{
    // In App\Http\Controllers\GameController.php

   public function homeIndex()
{
    $all_games = Game::paginate(10);
    $last_five_games = Game::orderBy('created_at', 'desc')->take(6)->get();
    $popular_games = Game::inRandomOrder()->take(6)->get();

    // Fetch all memberships dynamically
    $all_membership_plans = MembershipPlan::all();

    return view('Home.customer', compact(
        'all_games',
        'last_five_games',
        'popular_games',
        'all_membership_plans'
    ));
}

    // ... other methods ...


    public function detail(Game $gameDetail)
{
    $alreadyPurchased = false;

    if (Auth::check()) {
        // Check in transactions table instead of purchase_history
        $alreadyPurchased = \App\Models\Transaction::where('transaction_user_id', Auth::id())
            ->where('transaction_game_id', $gameDetail->id)
            ->exists();

        // If admin, show admin view
        if (Auth::user()->role == 'admin') {
            return view('Detail.detail_admin', compact('gameDetail'));
        }

        // If user, show customer view with purchase info
        return view('Detail.detail_customer', compact('gameDetail', 'alreadyPurchased'));
    }

    // Guest view
    return view('Detail.detail_guest', compact('gameDetail'));
}


public function library()
{
   $userId = Auth::id();

    // Fetch all purchased games for the logged-in user
    $ownedGames = Game::whereIn('id', function ($query) use ($userId) {
    $query->select('transaction_game_id')
          ->from('transactions')
          ->where('transaction_user_id', $userId);
})->get();


    return view('Library.library_customer', compact('ownedGames'));
}



    public function searchGame(Request $request){
        $search_game_name = $request->search_game_name;

        $search_games = Game::where('game_name', 'LIKE', "%$search_game_name%")->paginate(10)->withQueryString();

        if(Auth::check()){
            if(Auth::user()->role == 'admin'){
                return view('Search.search_admin', compact('search_games'));
            }else{
                return view('Search.search_customer', compact('search_games'));
            }
        }else{
            return view('Search.search_guest', compact('search_games'));
        }
    }



    //--------------Admin       

    public function adminHome()
{
    $all_games = Game::with('genre')->paginate(12);
    return view('Home.admin', compact('all_games'));
}

public function filterByGenre($id)
{
    $genre = Genre::findOrFail($id);
    $games = Game::where('genre_id', $id)->paginate(12);

    return view('Customer.filter_results', compact('games', 'genre'));
}




}
