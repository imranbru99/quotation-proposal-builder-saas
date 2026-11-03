<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use App\Services\Modules\ModuleRegistry;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PublicWebsiteController extends Controller
{
    public function __construct(
        protected ModuleRegistry $moduleRegistry
    ) {}

    public function home()
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        $modules = $this->moduleRegistry->all();
        $categories = $this->moduleRegistry->byCategory();

        return view('public.home', compact('plans', 'modules', 'categories'));
    }

    public function modules()
    {
        $categories = $this->moduleRegistry->byCategory();
        return view('public.modules', compact('categories'));
    }

    public function moduleDetail(string $id)
    {
        $module = $this->moduleRegistry->find($id);
        if (!$module) {
            abort(404, "Module '{$id}' not found.");
        }

        $allModules = $this->moduleRegistry->all();
        return view('public.module-detail', compact('module', 'allModules'));
    }

    public function pricing()
    {
        $plans = Plan::where('is_active', true)->orderBy('sort_order')->get();
        return view('public.pricing', compact('plans'));
    }

    public function solutions()
    {
        return view('public.solutions');
    }

    public function aiFeatures()
    {
        return view('public.ai-features');
    }

    public function security()
    {
        return view('public.security');
    }

    public function documentation()
    {
        return view('public.documentation');
    }

    public function contact()
    {
        return view('public.contact');
    }

    public function submitContact(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'company' => 'nullable|string|max:255',
            'message' => 'required|string|max:2000',
        ]);

        return redirect()->back()->with('success', 'Thank you for reaching out! Our enterprise solutions team will contact you shortly.');
    }

    /**
     * Interactive 1-click Demo Login Launcher.
     */
    public function launchDemoRole(string $role)
    {
        $demoTenant = Tenant::where('is_demo', true)->first();
        if (!$demoTenant) {
            return redirect()->route('public.home')->with('info', 'Demo environment not yet seeded. Please run php artisan db:seed.');
        }

        $user = match ($role) {
            'superadmin' => User::where('is_platform_superadmin', true)->first(),
            'owner' => User::where('tenant_id', $demoTenant->id)->where('role', 'owner')->first(),
            'sales' => User::where('tenant_id', $demoTenant->id)->where('role', 'sales_executive')->first(),
            'accountant' => User::where('tenant_id', $demoTenant->id)->where('role', 'accountant')->first(),
            default => User::where('tenant_id', $demoTenant->id)->first(),
        };

        if (!$user) {
            return redirect()->route('public.home')->with('info', "Demo account for '{$role}' not found.");
        }

        Auth::login($user);

        if ($user->is_platform_superadmin) {
            return redirect('/platform');
        }

        return redirect('/app');
    }
}

