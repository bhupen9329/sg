<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Company;

class ManualMatching extends Controller
{
    public function index()
    {
        $companies = Company::all(); // Retrieve all companies
        return view('manual_matching.index',compact('companies'));
    }
}
