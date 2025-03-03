<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookController extends Controller
{
    //this method will show books listing page
   public function index(){

    return view('books.list');

   }

   //this method will show book create page
   public function create(){
    return view('books.create');
   }

   //this method will store  book in database
   public function store(){

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
