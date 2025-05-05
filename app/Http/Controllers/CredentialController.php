<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Credentials;
use App\Models\Employee;
use App\Models\CompanyEmail;
use App\Models\ShareableCredentialLink;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class CredentialController extends Controller
{
            /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
        $this->middleware(['permission:credential-list|credential-create|credential-edit|credential-delete'], ['only' => ['index', 'show']]);
        $this->middleware(['permission:credential-create'], ['only' => ['create', 'store']]);
        $this->middleware(['permission:credential-edit'], ['only' => ['edit', 'update']]);
        $this->middleware(['permission:credential-delete'], ['only' => ['destroy']]);
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $credentials = Credentials::all();
        return view('credentials.index', compact('credentials'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $employees = Employee::where('employee_status', 'Active')->get();
        $companyEmails = CompanyEmail::where('status', 'active')->get();
        return view('credentials.create', compact('employees', 'companyEmails'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'company_number' => 'nullable|numeric',
            'company_email' => 'nullable|email|exists:company_emails,email',
            'email_password' => 'nullable|string',
        ]);

        // If company_email is provided but password is not, get password from CompanyEmail model
        if (!empty($validatedData['company_email']) && empty($validatedData['email_password'])) {
            $companyEmail = CompanyEmail::where('email', $validatedData['company_email'])->first();
            if ($companyEmail) {
                $validatedData['email_password'] = $companyEmail->password;
            }
        }

        $credential = Credentials::create($validatedData);
        return redirect()->route('credentials.index')->with('success', 'Credentials created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Credentials $credential)
    {
        return view('credentials.show', compact('credential'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Credentials $credential)
    {
        $employees = Employee::where('employee_status', 'Active')->get();
        $companyEmails = CompanyEmail::where('status', 'active')->get();
        return view('credentials.edit', compact('credential', 'employees', 'companyEmails'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Credentials $credential)
    {
        $validatedData = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'company_number' => 'nullable|numeric',
            'company_email' => 'nullable|email|exists:company_emails,email',
            'email_password' => 'nullable|string',
        ]);

        // If company_email is provided but password is not, get password from CompanyEmail model
        if (!empty($validatedData['company_email']) && empty($validatedData['email_password'])) {
            $companyEmail = CompanyEmail::where('email', $validatedData['company_email'])->first();
            if ($companyEmail) {
                $validatedData['email_password'] = $companyEmail->password;
            }
        }

        $credential->update($validatedData);
        return redirect()->route('credentials.index')->with('success', 'Credentials updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Credentials $credential)
    {
        $credential->delete();
        return redirect()->route('credentials.index')->with('success', 'Credentials deleted successfully.');
    }
    
    /**
     * Get company email password (AJAX endpoint)
     */
    public function getEmailPassword(Request $request)
    {
        $email = $request->input('email');
        $companyEmail = CompanyEmail::where('email', $email)->first();
        
        if ($companyEmail) {
            return response()->json(['success' => true, 'password' => $companyEmail->password]);
        }
        
        return response()->json(['success' => false]);
    }
    
    /**
     * Show form to create a shareable link for credentials.
     */
    public function showShareForm()
    {
        $credentials = Credentials::all();
        return view('credentials.share', compact('credentials'));
    }

    /**
     * Generate shareable link for selected credentials.
     */
    public function generateShareableLink(Request $request)
    {
        $request->validate([
            'credentials' => 'required|array',
            'credentials.*' => 'exists:credentials,id',
            'description' => 'nullable|string|max:255',
            'expiration_time' => 'required|in:10,20,30,40,50,60',
        ]);

        // Create shareable link with selected expiry time
        $expirationMinutes = (int) $request->expiration_time;
        $shareableLink = ShareableCredentialLink::create([
            'token' => Str::random(64),
            'created_by' => auth()->id(),
            'description' => $request->description,
            'expires_at' => Carbon::now()->addMinutes($expirationMinutes),
        ]);

        // Attach selected credentials
        $shareableLink->credentials()->attach($request->credentials);

        // If the request is AJAX, return JSON response
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'token' => $shareableLink->token,
                'message' => 'Shareable link generated successfully.'
            ]);
        }

        // For regular form submission
        return redirect()->route('credentials.share-link', $shareableLink->token)
            ->with('success', 'Shareable link generated successfully.');
    }

    /**
     * Show the shareable link details.
     */
    public function showShareableLink(string $token)
    {
        $shareableLink = ShareableCredentialLink::where('token', $token)->firstOrFail();
        
        return view('credentials.share-link', compact('shareableLink'));
    }

    /**
     * Access a shared credential list via the public link.
     */
    public function accessSharedCredentials(string $token)
    {
        $shareableLink = ShareableCredentialLink::where('token', $token)
            ->where('expires_at', '>', Carbon::now())
            ->firstOrFail();

        $credentials = $shareableLink->credentials;
        $remainingTime = $shareableLink->remainingTimeInMinutes();

        return view('credentials.public-share', compact('credentials', 'shareableLink', 'remainingTime'));
    }

    /**
     * List all active shareable links for the current user.
     */
    public function listShareableLinks()
    {
        $shareableLinks = ShareableCredentialLink::where('created_by', auth()->id())
            ->active()
            ->with('credentials')
            ->orderBy('expires_at', 'desc')
            ->get();

        return view('credentials.shareable-links', compact('shareableLinks'));
    }

    /**
     * Delete a shareable link.
     */
    public function deleteShareableLink(ShareableCredentialLink $shareableLink)
    {
        // Only allow the creator or a Super Admin to delete
        $user = Auth::user();
        $isSuperAdmin = $user->roles->where('name', 'Super Admin')->count() > 0;

        if (auth()->id() !== $shareableLink->created_by && !$isSuperAdmin) {
            return redirect()->back()->with('error', 'You do not have permission to delete this link.');
        }

        $shareableLink->delete();
        return redirect()->route('credentials.shareable-links')
            ->with('success', 'Shareable link deleted successfully.');
    }
}
