<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request->search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->when($request->role && $request->role != 'all', function ($query) use ($request) {
                $query->where('role', $request->role);
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();
        return view('pages.admin.users.index', compact('users'));
    }
    public function create()
    {

        return view('pages.admin.users.create');
    }
    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();

        $data['password'] = Hash::make($request->password);

        if ($request->hasFile('profile_picture')) {
            $path = $request->file('profile_picture')->store('profiles', 'public');
            $data['profile_picture'] = $path;
        }

        User::create($data);

        return redirect()->route('admin.users.index')->with('success', 'تم إضافة المستخدم بنجاح!');
    }
    public function edit(User $user)
    {
        return view('pages.admin.users.edit', compact('user'));
    }
    public function update(UpdateUserRequest $request, User $user)
    {
        if (Auth::id() === $user->id && $request->role !== $user->role) {
            return back()->with('error', 'Security Alert: You cannot change your own role!');
        }
        $data = $request->validated();

        // التعامل مع الباسورد: لو بعت باسورد جديدة شفرها، لو مبعتش شيلها من المصفوفة خالص
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']);
        }

        // التعامل مع الصورة
        if ($request->hasFile('profile_picture')) {
            // امسح القديمة لو موجودة عشان السيرفر ميتمليش
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            $data['profile_picture'] = $request->file('profile_picture')->store('profiles', 'public');
        }

        $user->update($data);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully!');
    }
    public function show(User $user)
    {
        return view('pages.admin.users.show', compact('user'));
    }
    public function destroy(User $user)
    {
        if (auth::id() === $user->id) {
            return back()->with('error', 'You cannot delete your own account!');
        }


        if ($user->isInstructor()) {
            $user->courses()->update(['instructor_id' => null]);
        }

        if ($user->isStudent()) {
            $user->enrollments()->delete();
        }

        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User and related data cleared successfully!');
    }
}
