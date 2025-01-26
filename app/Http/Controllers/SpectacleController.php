<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\DB;
use App\Http\Requests\BlogFilterRequest;
use App\Models\Spectacle;
use App\Models\Seat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Symfony\Component\Console\Input\Input;
use Illuminate\Support\Facades\File;


class SpectacleController  extends Controller
{
    public function seats($id)  {
        return view('seats', ['spectacletId' => $id]);
    }

    public function index()
    {
        $spectacles = Spectacle::all();
        return view('spectacles.index', compact('spectacles'));
    }

    public function create()
    {
        return view('spectacles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'total_seats' => 'required|integer|min:1',
            'state' => 'required|in:active,inactive',
            'image' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = $request->file('image')->store('spectacles', 'public');

        $Spectacle =Spectacle::create([
            'name' => $request->name,
            'description' => $request->description,
            'date' => $request->date,
            'total_seats' => $request->total_seats,
            'state' => $request->state,
            'image' => $imagePath,
        ]);

    // Load the SQL file
    $sqlFilePath = database_path('sql/seats_finale.sql'); // Path to your .sql file
    $sqlQuery = File::get($sqlFilePath);

    // Replace placeholders (if needed) with actual stage_id
    $sqlQuery = str_replace('?', $Spectacle->id_spectacle, $sqlQuery);
   
    // Execute the SQL query
    DB::unprepared($sqlQuery);


        return redirect()->route('spectacles.index')->with('success', 'Spectacle created successfully.');
    }

    public function edit(Spectacle $spectacle)
    {
        return view('spectacles.edit', compact('spectacle'));
    }

    public function update(Request $request, Spectacle $spectacle)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'required|date',
            'total_seats' => 'required|integer|min:1',
            'state' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($spectacle->image);
            $imagePath = $request->file('image')->store('spectacles', 'public');
            $spectacle->image = $imagePath;
        }

        $spectacle->update($request->only('name', 'description', 'date', 'total_seats', 'state'));

        return redirect()->route('spectacles.index')->with('success', 'Spectacle updated successfully.');
    }

    public function destroy(Spectacle $spectacle)
    {
        Storage::disk('public')->delete($spectacle->image);
        $spectacle->delete();
        Seat::where('id_spectacle', $spectacle->id_spectacle)->delete();

        return redirect()->route('spectacles.index')->with('success', 'Spectacle deleted successfully.');
    }

}
