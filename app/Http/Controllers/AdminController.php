<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Show the admin dashboard.
     */
    public function dashboard(): View
    {
        // User statistics
        $totalUsers = User::count();
        $adminUsers = User::where('role', 'admin')->count();
        $regularUsers = $totalUsers - $adminUsers;

        // Recent users
        $recentUsers = User::orderBy('created_at', 'desc')->take(5)->get();

        // Products count
        $totalProducts = Product::count();
        
        // Orders count
        $totalOrders = Order::count();

        // Recent products
        $products = Product::orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.dashboard', compact(
            'totalUsers', 
            'adminUsers', 
            'regularUsers', 
            'recentUsers',
            'totalProducts',
            'totalOrders',
            'products'
        ));
    }

    /**
     * Show user management page
     */
    public function userManagement(): View
    {
        $users = User::all();
        return view('admin.user-management', compact('users'));
    }

    /**
     * Update user role
     */
    public function updateUserRole(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'role' => 'required|in:admin,user'
        ]);

        $user->update(['role' => $validatedData['role']]);

        return redirect()->route('admin.user-management')
            ->with('success', 'User role updated successfully.');
    }
}
