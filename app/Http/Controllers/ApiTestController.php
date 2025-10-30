<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ApiTestController extends Controller
{
    /**
     * Display the API testing page.
     */
    public function index()
    {
        $developers = User::whereHas('role', function($query) {
            $query->where('name', 'developer');
        })
        ->with(['projects' => function($query) {
            $query->where('is_active', true);
        }])
        ->get()
        ->map(function($developer) {
            return [
                'alt_name' => $developer->alt_name, // Use actual alt_name field
                'name' => $developer->name,
                'projects' => $developer->projects->map(function($project) {
                    return [
                        'alt_name' => $project->alt_name,
                        'name' => $project->name
                    ];
                })
            ];
        });

        // Common lead sources
        $sources = [
            'Website',
            'Facebook',
            'Instagram',
            'Google Ads',
            'Referral',
            'Walk-in',
            'Cold Call',
            'Email Marketing',
            'LinkedIn',
            'Twitter',
            'YouTube',
            'Other'
        ];

        return view('admin.apis', compact('developers', 'sources'));
    }

    /**
     * Test the Create Lead API by making a real HTTP request.
     */
    public function testCreateLead(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'message' => 'nullable|string',
            'developer_alt_name' => 'required|string',
            'project_alt_name' => 'required|string',
            'source' => 'nullable|string|max:255',
        ]);

        try {
            // Prepare API request data
            $apiData = [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'message' => $request->message,
                'developer_alt_name' => $request->developer_alt_name,
                'project_alt_name' => $request->project_alt_name,
                'source' => $request->source,
            ];

            // Use a different port or external URL to avoid circular dependency
            // For local testing, we'll use a different approach
            $apiUrl = 'http://127.0.0.1:8001/api/leads'; // Different port
            
            // Try the external API call first
            try {
                $response = Http::timeout(10)
                    ->retry(1, 500)
                    ->withHeaders([
                        'Accept' => 'application/json',
                        'Content-Type' => 'application/json',
                    ])
                    ->post($apiUrl, $apiData);

                $responseData = $response->json();
                $statusCode = $response->status();

                // Log the API test
                Log::info('API Test - External Call Success', [
                    'api_url' => $apiUrl,
                    'request_data' => $apiData,
                    'response' => $responseData,
                    'status_code' => $statusCode
                ]);

                if ($statusCode === 201 && isset($responseData['success']) && $responseData['success']) {
                    return redirect()->back()->with('success', 
                        '✅ API Test Successful! ' . 
                        'Status: ' . $statusCode . ' | ' .
                        'Response: ' . $responseData['message'] . ' | ' .
                        'API URL: ' . $apiUrl
                    );
                } else {
                    return redirect()->back()->with('error', 
                        '❌ API Test Failed! ' .
                        'Status: ' . $statusCode . ' | ' .
                        'Response: ' . json_encode($responseData) . ' | ' .
                        'API URL: ' . $apiUrl
                    );
                }

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                // If external call fails, fall back to internal testing
                Log::info('External API call failed, using internal test', [
                    'error' => $e->getMessage(),
                    'api_url' => $apiUrl
                ]);

                // Use internal API testing as fallback
                return $this->testInternalApi($apiData);
            }

        } catch (\Exception $e) {
            Log::error('API Test Error', [
                'error' => $e->getMessage(),
                'request_data' => $request->all()
            ]);

            return redirect()->back()->with('error', 
                '❌ API Test Failed! ' . 
                'Error: ' . $e->getMessage()
            );
        }
    }

    /**
     * Internal API testing fallback
     */
    private function testInternalApi($apiData)
    {
        try {
            // Create a mock request object with the API data
            $apiRequest = new Request($apiData);

            // Call the actual API controller method directly
            $apiController = new \App\Http\Controllers\Api\LeadApiController();
            $response = $apiController->store($apiRequest);
            
            // Get the response data
            $responseData = $response->getData(true);
            $statusCode = $response->getStatusCode();

            // Log the API test
            Log::info('API Test - Internal Call', [
                'request_data' => $apiData,
                'response' => $responseData,
                'status_code' => $statusCode
            ]);

            if ($statusCode === 201 && isset($responseData['success']) && $responseData['success']) {
                return redirect()->back()->with('success', 
                    '✅ API Test Successful! (Internal) ' . 
                    'Status: ' . $statusCode . ' | ' .
                    'Response: ' . $responseData['message'] . ' | ' .
                    'Note: Used internal testing due to connection issue'
                );
            } else {
                return redirect()->back()->with('error', 
                    '❌ API Test Failed! (Internal) ' .
                    'Status: ' . $statusCode . ' | ' .
                    'Response: ' . json_encode($responseData)
                );
            }

        } catch (\Exception $e) {
            Log::error('Internal API Test Error', [
                'error' => $e->getMessage(),
                'request_data' => $apiData
            ]);

            return redirect()->back()->with('error', 
                '❌ Internal API Test Failed! ' . 
                'Error: ' . $e->getMessage()
            );
        }
    }
}
