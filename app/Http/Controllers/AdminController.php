<?php

namespace App\Http\Controllers;

use App\Models\Business;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingBusinesses = Business::with(['category', 'user'])
            ->where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $approvedCount = Business::where('status', 'approved')->count();
        $pendingCount = Business::where('status', 'pending')->count();
        $rejectedCount = Business::where('status', 'rejected')->count();
        
        return view('admin.dashboard', compact('pendingBusinesses', 'approvedCount', 'pendingCount', 'rejectedCount'));
    }
    
    public function show(Business $business)
    {
        $business->load(['category', 'user', 'images']);
        return view('admin.businesses.show', compact('business'));
    }
    
    public function approve(Business $business)
    {
        $business->update([
            'status' => 'approved',
            'published' => true,
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);
        
        return redirect()->route('admin.dashboard')
            ->with('success', "El negocio '{$business->name}' fue aprobado correctamente.");
    }
    
    public function reject(Request $request, Business $business)
    {
        $business->update([
            'status' => 'rejected',
            'published' => false,
            'rejection_reason' => $request->input('rejection_reason'),
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);
        
        return redirect()->route('admin.dashboard')
            ->with('success', "El negocio '{$business->name}' fue rechazado.");
    }
}