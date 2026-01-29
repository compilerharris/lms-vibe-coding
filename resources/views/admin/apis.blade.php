@extends('layouts.app')

@section('title', 'API Testing - Lead Management System')

@section('content')
            <!-- Sidebar -->
            <x-sidebar active="apis" />

            <!-- Main Content -->
            <div class="main-content">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2><i class="fas fa-code me-2"></i>API Testing</h2>
                    <div class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>Test all available APIs
                    </div>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <!-- Create Lead API Form -->
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-plus-circle me-2"></i>Create Lead API</h6>
                        <small class="text-muted">Test the lead creation API endpoint</small>
                    </div>
                    <div class="card-body">
                        <form id="createLeadForm" action="{{ route('apis.test-create-lead') }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                        <input type="email" class="form-control" id="email" name="email" 
                                               value="{{ old('email') }}" required>
                                        @error('email')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Phone <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="phone" name="phone" 
                                               value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="source" class="form-label">Source</label>
                                        <select class="form-select" id="source" name="source">
                                            <option value="">Select Source</option>
                                            @foreach($sources as $source)
                                                <option value="{{ $source }}" {{ old('source') == $source ? 'selected' : '' }}>
                                                    {{ $source }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('source')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="subsource" class="form-label">Subsource</label>
                                        <input type="text" class="form-control" id="subsource" name="subsource" 
                                               value="{{ old('subsource') }}" 
                                               placeholder="e.g., Homepage Banner, Social Media Ad">
                                        @error('subsource')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="developer_alt_name" class="form-label">Developer Alt Name <span class="text-danger">*</span></label>
                                        <select class="form-select" id="developer_alt_name" name="developer_alt_name" required>
                                            <option value="">Select Developer</option>
                                            @foreach($developers as $developer)
                                                <option value="{{ $developer['alt_name'] }}" 
                                                        data-projects="{{ json_encode($developer['projects']) }}"
                                                        {{ old('developer_alt_name') == $developer['alt_name'] ? 'selected' : '' }}>
                                                    {{ $developer['alt_name'] }} - {{ $developer['name'] }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('developer_alt_name')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="project_alt_name" class="form-label">Project Alt Name <span class="text-danger">*</span></label>
                                        <select class="form-select" id="project_alt_name" name="project_alt_name" required disabled>
                                            <option value="">Select Developer First</option>
                                        </select>
                                        @error('project_alt_name')
                                            <div class="text-danger small">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message</label>
                                <textarea class="form-control" id="message" name="message" rows="3" 
                                          placeholder="Enter your message here...">{{ old('message') }}</textarea>
                                @error('message')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    <i class="fas fa-info-circle me-1"></i>
                                    This will make a real HTTP request to the API endpoint
                                </div>
                                <button type="submit" class="btn btn-primary" id="submitBtn">
                                    <i class="fas fa-rocket me-2"></i>Test Real API
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- API Information -->
                <div class="card mt-4">
                    <div class="card-header">
                        <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>API Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6>Create Lead API</h6>
                                <p><strong>Endpoint:</strong> <code>POST /api/leads</code></p>
                                <p><strong>Base URL:</strong> <code>{{ url('/') }}</code></p>
                                <p><strong>Full URL:</strong> <code>{{ url('/api/leads') }}</code></p>
                                <p><strong>Content-Type:</strong> <code>application/json</code></p>
                                <p><strong>Timeout:</strong> <code>30 seconds</code></p>
                            </div>
                            <div class="col-md-6">
                                <h6>Required Fields</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-check text-success me-2"></i>name</li>
                                    <li><i class="fas fa-check text-success me-2"></i>email</li>
                                    <li><i class="fas fa-check text-success me-2"></i>phone</li>
                                    <li><i class="fas fa-check text-success me-2"></i>developer_alt_name</li>
                                    <li><i class="fas fa-check text-success me-2"></i>project_alt_name</li>
                                </ul>
                                <h6 class="mt-3">Optional Fields</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-info text-info me-2"></i>source</li>
                                    <li><i class="fas fa-info text-info me-2"></i>subsource</li>
                                    <li><i class="fas fa-info text-info me-2"></i>message</li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Test Features</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span>Direct API Logic Test</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span>No HTTP Overhead</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span>Response Validation</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span>Error Handling</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span>Detailed Logging</span>
                                        </div>
                                        <div class="d-flex align-items-center mb-2">
                                            <i class="fas fa-check-circle text-success me-2"></i>
                                            <span>Status Reporting</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sample JavaScript Code -->
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0"><i class="fas fa-code me-2"></i>Sample JavaScript Code for Landing Page Integration</h6>
                        <button type="button" class="btn btn-sm btn-outline-primary" onclick="copySampleCode()" id="copyCodeBtn">
                            <i class="fas fa-copy me-1"></i>Copy Code
                        </button>
                    </div>
                    <div class="card-body">
                        <p class="text-muted small mb-3">
                            <i class="fas fa-info-circle me-1"></i>
                            Copy and paste this code into your landing page to integrate the Lead Management API.
                        </p>
                        <pre id="sampleCode" class="bg-light p-3 rounded" style="max-height: 500px; overflow-y: auto; font-size: 0.75rem; line-height: 1.5;"><code>
&lt;!-- Lead Management API Integration Script --&gt;
&lt;script&gt;

    const API_URL = '{{ url('/api/v1/leads') }}'; // Update this with your actual API endpoint
    const DEVELOPER_ALT_NAME = 'YOUR_DEVELOPER_ALT_NAME'; // Replace with your developer alt name
    const PROJECT_ALT_NAME = 'YOUR_PROJECT_ALT_NAME'; // Replace with your project alt name

    /**
    * Function to get URL query parameters
    * @param {string} param - Parameter name to get from URL
    * @returns {string|null} Parameter value or null if not found
    */
    function getURLParameter(param) {
        const urlParams = new URLSearchParams(window.location.search);
        return urlParams.get(param);
    }

    /**
    * Function to create a lead via API
    * @param {Object} leadData - Lead information object
    * @returns {Promise} Promise that resolves with the API response
    */
    async function createLead(leadData) {
        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(leadData)
            });

            const result = await response.json();

            if (response.ok && result.success) {
                return {
                    success: true,
                    message: result.message || 'Lead created successfully',
                    data: result
                };
            } else {
                return {
                    success: false,
                    message: result.message || 'Failed to create lead',
                    errors: result.errors || {}
                };
            }
        } catch (error) {
            return {
                success: false,
                message: 'Network error: ' + error.message,
                errors: {}
            };
        }
    }

    /**
    * Handle form submission
    * Replace 'yourFormId' with your actual form ID
    */
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('yourFormId'); // Replace with your form ID
        
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(form);
                
                // Get source and subsource from URL parameters
                const source = getURLParameter('source') || getURLParameter('utm_source') || '';
                const subsource = getURLParameter('subsource') || getURLParameter('utm_medium') || '';
                
                // Build lead data object
                const leadData = {
                    name: formData.get('name'),
                    email: formData.get('email'),
                    phone: formData.get('phone'),
                    source: source, // Automatically extracted from URL
                    subsource: subsource, // Automatically extracted from URL
                    message: formData.get('message') || '',
                    developer_alt_name: DEVELOPER_ALT_NAME, // Set by developer
                    project_alt_name: PROJECT_ALT_NAME // Set by developer
                };

                // Show loading state
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalBtnText = submitBtn ? submitBtn.innerHTML : '';
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = 'Submitting...';
                }

                // Submit lead
                const result = await createLead(leadData);

                // Handle response
                if (result.success) {
                    alert('Thank you! Your inquiry has been submitted successfully.');
                    form.reset(); // Reset form on success
                } else {
                    let errorMessage = result.message;
                    if (result.errors && Object.keys(result.errors).length > 0) {
                        errorMessage += '\n\nErrors:\n' + Object.values(result.errors).flat().join('\n');
                    }
                    alert('Error: ' + errorMessage);
                }

                // Restore button state
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });
        }
    });
&lt;/script&gt;</code></pre>
                        <div id="copySuccess" class="alert alert-success mt-3" style="display: none;">
                            <i class="fas fa-check-circle me-2"></i>Code copied to clipboard!
                        </div>
                    </div>
                </div>
            </div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Handle developer selection change
    $('#developer_alt_name').on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const projects = selectedOption.data('projects');
        const projectSelect = $('#project_alt_name');
        
        // Clear existing options
        projectSelect.empty();
        
        if (projects && projects.length > 0) {
            // Add projects for selected developer
            projectSelect.append('<option value="">Select Project</option>');
            projects.forEach(function(project) {
                projectSelect.append(
                    '<option value="' + project.alt_name + '">' + 
                    project.alt_name + ' - ' + project.name + 
                    '</option>'
                );
            });
            projectSelect.prop('disabled', false);
        } else {
            projectSelect.append('<option value="">No projects available</option>');
            projectSelect.prop('disabled', true);
        }
    });

    // Handle form submission with loading state
    $('#createLeadForm').on('submit', function() {
        const submitBtn = $('#submitBtn');
        submitBtn.prop('disabled', true);
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Testing API...');
    });

    // Pre-populate project dropdown if developer is already selected (for validation errors)
    @if(old('developer_alt_name'))
        $('#developer_alt_name').trigger('change');
        @if(old('project_alt_name'))
            setTimeout(function() {
                $('#project_alt_name').val('{{ old("project_alt_name") }}');
            }, 100);
        @endif
    @endif
});

// Copy sample code to clipboard
function copySampleCode() {
    const codeElement = document.getElementById('sampleCode');
    const codeText = codeElement.textContent || codeElement.innerText;
    
    // Create a temporary textarea to copy text
    const textarea = document.createElement('textarea');
    textarea.value = codeText;
    textarea.style.position = 'fixed';
    textarea.style.opacity = '0';
    document.body.appendChild(textarea);
    textarea.select();
    
    try {
        document.execCommand('copy');
        // Show success message
        const copyBtn = document.getElementById('copyCodeBtn');
        const originalText = copyBtn.innerHTML;
        copyBtn.innerHTML = '<i class="fas fa-check me-1"></i>Copied!';
        copyBtn.classList.remove('btn-outline-primary');
        copyBtn.classList.add('btn-success');
        
        setTimeout(function() {
            copyBtn.innerHTML = originalText;
            copyBtn.classList.remove('btn-success');
            copyBtn.classList.add('btn-outline-primary');
        }, 2000);
    } catch (err) {
        alert('Failed to copy code. Please select and copy manually.');
    }
    
    document.body.removeChild(textarea);
}
</script>
@endsection

@section('styles')
<style>
    .form-control:focus, .form-select:focus {
        border-color: #a136aa;
        box-shadow: 0 0 0 0.2rem rgba(161, 54, 170, 0.25);
    }
    
    .btn-primary {
        background-color: #a136aa;
        border-color: #a136aa;
    }
    
    .btn-primary:hover {
        background-color: #8a2d96;
        border-color: #8a2d96;
    }
    
    code {
        background-color: #f8f9fa;
        padding: 2px 4px;
        border-radius: 3px;
        font-size: 0.9em;
    }
    
    .card-header h6 {
        color: #a136aa;
    }
</style>
@endsection
