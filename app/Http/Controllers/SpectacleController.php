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

        //store the image into /public/uploads/spectacles
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension(); // Rename file
            $image->move(public_path('uploads/spectacles'), $imageName); // Move to public/uploads/spectacles
            $imagePath = 'uploads/spectacles/' . $imageName; // Save relative path in DB
        }

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
        //delete old image if there is a new image 
        if ($request->hasFile('image')) {
        // Delete old image if it exists
            $oldImagePath = public_path($spectacle->image);
            if (file_exists($oldImagePath) && is_file($oldImagePath)) {
                unlink($oldImagePath);
            }

            // Upload new image
            $image = $request->file('image');
            $imageName = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('uploads/spectacles'), $imageName);

            // Save new image path
            $spectacle->image = 'uploads/spectacles/' . $imageName;
        }

        $spectacle->update($request->only('name', 'description', 'date', 'total_seats', 'state'));

        return redirect()->route('spectacles.index')->with('success', 'Spectacle updated successfully.');
    }

    public function destroy(Spectacle $spectacle)
    {
        //verify if the image exist before remove it 
        $imagePath = public_path($spectacle->image);
        if (file_exists($imagePath) && is_file($imagePath)) {
            unlink($imagePath);
        }

        $spectacle->delete();

        Seat::where('id_spectacle', $spectacle->id_spectacle)->delete();

        return redirect()->route('spectacles.index')->with('success', 'Spectacle deleted successfully.');
    }

}
