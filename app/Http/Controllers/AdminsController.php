<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\MembershipPlan;
use App\Models\Game;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class AdminsController extends Controller
{
    public function game_index(){
        $games = Game::paginate(10)->withQueryString();

        return view('ManageGame.ManageGame_home', compact('games'));
    }

    public function genre_index(){
        $genres = Genre::paginate(10);
        return view('ManageGenre.ManageGenre_home', compact('genres'));
    }

    public function addGameLogic(Request $request){

        if($request->game_genre_id == "Choose genre"){
            $request->session()->flash('error', 'Please select a genre');
            return redirect()->back();
        }

        $request->validate([
            'game_name' => 'required',
            'game_detail' => 'required',
            'game_price' => 'required|integer',
            'game_image' => 'required|mimes:png,jpg,jpeg',
            'game_pegi_rating' => 'required|integer|in:0,3,7,12,16,18',
            // 'game_genre_id' => 'required:not_in:Choose genre',
        ]);

        $original_name = $request->file('game_image')->getClientOriginalName();
        $original_ext = $request->file('game_image')->getClientOriginalExtension();
        $game_image_name = $original_name . time() . '.' . $original_ext;

        $request->file('game_image')->storeAs('public/images', $game_image_name);
        $game_image = 'storage/images/' . $game_image_name;


        DB::table('games')->insert([
            'game_name' => $request->game_name,
            'game_detail' => $request->game_detail,
            'game_price' => $request->game_price,
            'game_image' => $game_image,
            'game_pegi_rating' => $request->game_pegi_rating,
            'game_genre_id' => $request->game_genre_id
        ]);

        return redirect()->route('admin.games.index')->with('success', 'Add New Game Successful');


    }

    public function deleteGame(Request $request){
        Game::where('id', $request->id)->delete();

        return redirect()->route('admin.games.index')->with('success', 'Deleted a Game');;
    }

    public function updateGameView($id, Request $request){
        // $games = Game::where('id', $request->id)->first();
        // $genres = Genre::all();
        // return view('ManageGame.ManageGame_update', compact('games'));
        // return view('ManageGame.ManageGame_update', ['genres' => $genres]);


        $game = Game::findOrFail($id);
        $genres = Genre::all();
        return view('ManageGame.ManageGame_update', ['game' => $game, 'genres' => $genres]);

    }

    public function updateGameLogic(Request $request, $id)
{
    $game = Game::findOrFail($id);

    $data = [];

    $request->validate([
        'game_name' => 'nullable|string|max:255',
        'game_detail' => 'nullable|string',
        'game_price' => 'nullable|numeric',
        'genre_id' => 'nullable|exists:genres,id',
        'game_pegi_rating' => 'nullable|integer|in:0,3,7,12,16,18',
        'game_image' => 'nullable|mimes:jpg,jpeg,png|max:2048',
        'installer' => 'nullable|mimes:zip,rar,exe|max:102400', // up to 100MB
    ]);

    // Check for updates
    if ($request->filled('game_name') && $request->game_name != $game->game_name) {
        $data['game_name'] = $request->game_name;
    }

    if ($request->filled('game_detail') && $request->game_detail != $game->game_detail) {
        $data['game_detail'] = $request->game_detail;
    }

    if ($request->filled('game_price') && $request->game_price != $game->game_price) {
        $data['game_price'] = $request->game_price;
    }

    if ($request->filled('genre_id') && $request->genre_id != $game->genre_id) {
        $data['genre_id'] = $request->genre_id;
    }

    if ($request->filled('game_pegi_rating') && $request->game_pegi_rating != $game->game_pegi_rating) {
        $data['game_pegi_rating'] = $request->game_pegi_rating;
    }

    // Handle image upload
    if ($request->hasFile('game_image')) {
        $file = $request->file('game_image');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->storeAs('public/images', $filename);
        $data['game_image'] = 'storage/images/' . $filename;
    }

    // Handle installer upload
    if ($request->hasFile('installer')) {
    $installer = $request->file('installer');
    $installerName = time() . '_' . $installer->getClientOriginalName();
    $installer->storeAs('public/installers', $installerName);

    // Store the public path so it matches your asset() call
    $data['installer'] = 'installers/' . $installerName;
}


    if (empty($data)) {
        return back()->withErrors(['nothing_changed' => 'You must change at least one field to update!']);
    }

    $game->update($data);

    return redirect()->route('admin.games.index')->with('success2', 'Game updated successfully!');
}



    public function searchManageGames(Request $request){
        $search_game_name = $request->search_game_name;

        $search_games = Game::where('game_name', 'LIKE', "%$search_game_name%")->paginate(10)->withQueryString();

        return view('Search.search_manage_game', compact('search_games'));
    }

        public function updateGenreName(Request $request) {
            $validatedData = $request->validate([
                'genre_name' => 'required|unique:genres,genre_name,'.$request->id,
            ]);

            DB::table('genres')->where('id', '=', $request->id)->update([
                'genre_name' => $request->genre_name,
            ]);

            return redirect()->route('manage_genre')->with('success', 'Genre updated successfully');
        }

        public function createGame()
        {
            $genres = Genre::all();
            return view('ManageGame.ManageGame_add', ['genres' => $genres]);
    }

    // Show all membership plans
    public function membership_index()
    {
        $plans = MembershipPlan::paginate(10);
        return view('ManageMembership.ManageMembership_home', compact('plans'));
    }

    // Show create form
    public function membership_create()
    {
        return view('ManageMembership.ManageMembership_add');
    }

    // Store new membership plan
    public function membership_store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'description' => 'nullable|string',
        'duration_type' => 'required|in:days,week,month',
        'duration_days' => 'required|integer|min:1',
    ]);

    \App\Models\MembershipPlan::create([
        'name' => $request->name,
        'price' => $request->price,
        'description' => $request->description,
        'duration_type' => $request->duration_type,
        'duration_days' => $request->duration_days,
    ]);

    return redirect()->route('admin.membership.index')->with('success', 'Membership created successfully!');
}


    // Show edit form
    public function membership_edit($id)
    {
        $plan = MembershipPlan::findOrFail($id);
        return view('ManageMembership.ManageMembership_edit', compact('plan'));
    }

    // Update membership plan
    public function membership_update(Request $request, $id)
{
    $plan = \App\Models\MembershipPlan::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'price' => 'required|numeric',
        'description' => 'nullable|string',
        'duration_type' => 'required|in:days,week,month',
        'duration_days' => 'required|integer|min:1',
    ]);

    $plan->update([
        'name' => $request->name,
        'price' => $request->price,
        'description' => $request->description,
        'duration_type' => $request->duration_type,
        'duration_days' => $request->duration_days,
    ]);

    return redirect()->route('admin.membership.index')->with('success', 'Membership updated successfully!');
}

    // Delete membership plan
    public function membership_delete($id)
    {
        $plan = MembershipPlan::findOrFail($id);
        $plan->delete();

        return redirect()->route('admin.membership.index')->with('success', 'Membership plan deleted successfully.');
    }




    // userrs
    public function manageUsers()
{
    $users = User::paginate(10); // 10 users per page
// fetch all users
    return view('ManageUsers.index', compact('users')); // point to your blade file
}

    public function deleteUser($id)
{
    $user = User::find($id);

    if (!$user) {
        return redirect()->back()->with('error', 'User not found.');
    }

    // Prevent admin from deleting themselves
    if (auth()->user()->id == $id) {
        return redirect()->back()->with('error', 'You cannot delete yourself!');
    }

    // Delete related records
    if (method_exists($user, 'transactions')) {
        $user->transactions()->delete(); // Deletes transactions
    }

    if (method_exists($user, 'carts')) {
        $user->carts()->delete(); // Deletes carts
    }

    // Add any other dependent relationships here
    // e.g., $user->orders()->delete();

    // Finally delete the user
    $user->delete();

    return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
}


    public function editUserView($id)
{
    $user = User::find($id);

    if (!$user) {
        return redirect()->route('admin.users.index')->with('error', 'User not found.');
    }

    return view('ManageUsers.edit', compact('user')); // blade file for editing
}

     public function updateUser(Request $request, $id)
    {
        // Validate input
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,user',
        ]);

        // Find user
        $user = User::findOrFail($id);

        // Update data
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();

        // Redirect with success message
        return redirect()->route('admin.users.index')
                         ->with('success', 'User updated successfully!');
    }

    // Show the Add User form
public function createUserView() {
    return view('ManageUsers.create'); // create.blade.php for add user form
}

// Store the new user
public function storeUser(Request $request) {
    $request->validate([
    'name' => 'required|string|max:255',
    'email' => 'required|email|unique:users,email',
    'password' => 'required|string|min:6|confirmed',
    'role' => 'required|string|in:admin,customer',
    'date_of_birth' => 'nullable|date|before:today|after:1900-01-01',
]);


    $date = $request->date_of_birth;

if (!$date || strtotime($date) === false || (int)substr($date, 0, 4) > 9999) {
    $date = '2000-01-01';
}

User::create([
    'name' => $request->name,
    'email' => $request->email,
    'password' => bcrypt($request->password),
    'role' => $request->role,
    'gender' => $request->gender ?? 'Not specified',
    'date_of_birth' => $date,
]);




    return redirect()->route('admin.users.index')->with('success', 'User added successfully!');
}


}
