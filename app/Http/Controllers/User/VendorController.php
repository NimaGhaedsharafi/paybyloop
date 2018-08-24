<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Vendor;

class VendorController extends Controller
{
    public function index()
    {
        return Vendor::all();
    }

    public function show($vendorId)
    {
        return Vendor::where('vendor_id', $vendorId)->firstOrFail();
    }
}
