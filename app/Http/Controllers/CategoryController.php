<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    //get all Category
    public function index(Request $request) {
        $categories = Category::paginate(10);
        return view('page.category.index', compact('categories'));
    }

    //create Category
    public function create(){
        return view('page.category.create');
    }

    //add new Category
    public function store(Request $request){
        $request->validate([
            'name'=> 'required',
            'image' => 'required',
        ]);

        //request input DB
        $categories = new Category;
        $categories->name = $request->name;
        $categories->description = $request->description;
        $categories->save();

        //save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/categories', $categories->id . '.' . $image->getClientOriginalExtension());
            $categories->image = 'storage/categories/' . $categories->id . '.' . $image->getClientOriginalExtension();
            $categories->save();
        }

        return redirect()->route('categories.index')->with('success', 'Category berhasil ditambahkan');
    }

    //link to page edit
    public function edit($id){
        $categories= Category::find($id);
        return view('page.category.edit', compact('categories'));
    }

    //update category
    public function update(Request $request, $id){
        $request->validate([
            'name' => 'required',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        //request update DB
        $categories = Category::find($id);
        $categories->name = $request->name;
        $categories->description = $request->description;
        $categories->save();

        //save image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $image->storeAs('public/categories', $categories->id . '.' . $image->getClientOriginalExtension());
            $categories->image = 'storage/categories/' . $categories->id . '.' . $image->getClientOriginalExtension();
            $categories->save();
        }
        return redirect()->route('categories.index')->with('success', 'Category berhasil diubah');
    }

    //delete Data
    public function destroy($id){
        $categories = Category::find($id);
        $categories->delete();

        return redirect()->route('categories.index')->with('success', 'Category berhasil dihapus');
    }
}