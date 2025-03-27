<?php

namespace App\Http\Controllers;

use App\Models\book;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;


class BookController extends Controller
{
    //this method will show books listing page
   public function index(Request $request){

    $books = book::orderBy('created_at','desc');

    if(!empty($request->keyword)){
       $books = book::where('title','like','%'.$request->keyword.'%');
    }

      $books = $books->paginate(3);

    return view('books.list',[
        'books' => $books
    ]);

   }

   //this method will show book create page
   public function create(){
    return view('books.create');
   }

   //this method will store  book in database
   public function store(Request $request){

    $rules = [
        'title' => 'required|min:5',
        'author' => 'required|min:3',
        'status' => 'required',

    ];
    if(!empty($request->image)){
        $rules['image'] = 'image|mimes:jpeg,png,jpg,gif,svg|max:2048';
    }

    $validator = Validator::make($request->all(),$rules);

    if($validator->fails()){

        return redirect()->route('books.create')->withInput()->withErrors($validator);

    }
    //save book in database
    $book = new book();
    $book->title = $request->title;
    $book->author = $request->author;
    $book->status = $request->status;
    $book->save();

    //upload book image
    if(!empty($request->image)){
       $image =$request->image;
       $ext = $image->getClientOriginalExtension();
       $imageName = time().'.'.$ext;
       $image->move(public_path('uploads/books/'),$imageName);
       $book->image = $imageName;
       $book->save();

       $manager = new ImageManager(Driver::class);
       $img = $manager->read(public_path('uploads/books/' . $imageName));

       $img->resize(500);
       $img->save(public_path('uploads/books/thumbs'.$imageName));
       
    }

    return redirect()->route('books.index')->with('success','Book added successfully');

}

    //this method will show edit  book page
    public function edit(){

    }

    //this method will update a book 
    public function update(){

    }

     //this method will delete a book from database
     public function destroy(){

     }
}
