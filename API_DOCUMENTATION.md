# Lead Assignment API Documentation

## Base URL
```
http://localhost:8000/api/v1
```

## Authentication
This API is currently **public** and does not require authentication. In production, consider adding API keys or authentication tokens.

## Endpoints

### 1. Create Lead
**POST** `/api/v1/leads`

Creates a new lead and automatically assigns it to a channel partner using round-robin distribution.

#### Request Body
```json
{
    "name": "John Doe",
    "email": "john@example.com",
    "phone": "+1234567890",
    "source": "Website",
    "message": "Interested in your services",
    "developer_alt_name": "SAMGYSSSZ",
    "project_alt_name": "SAMLYHBR0"
}
```

#### Required Fields
- `name` (string, max 255) - Lead's full name
- `email` (string, email format, max 255) - Lead's email address
- `developer_alt_name` (string) - Alternative name of the developer (auto-generated)
- `project_alt_name` (string) - Alternative name of the project (auto-generated)

#### Optional Fields
- `phone` (string, max 20) - Lead's phone number
- `source` (string, max 255) - Lead source (e.g., "Website", "Facebook", "Google")
- `message` (string) - Additional message from the lead

#### Success Response (201)
```json
{
    "success": true,
    "message": "Lead created successfully"
}
```

#### Error Response (422 - Validation Error)
```json
{
    "success": false,
    "message": "Validation failed",
    "errors": {
        "email": ["The email field is required."],
        "developer_alt_name": ["The developer alt name field is required."]
    }
}
```

#### Error Response (404 - Project Not Found)
```json
{
    "success": false,
    "message": "Project not found or does not belong to the specified developer"
}
```

#### Error Response (500 - Server Error)
```json
{
    "success": false,
    "message": "Failed to create lead: [error details]"
}
```

### 2. Get Developers and Projects
**GET** `/api/v1/developers-projects`

Retrieves all active developers and their associated projects.

#### Success Response (200)
```json
{
    "success": true,
    "data": [
        {
            "alt_name": "SAMGYSSSZ",
            "name": "Sample Developer",
            "projects": [
                {
                    "alt_name": "SAMLYHBR0",
                    "name": "Sample Project",
                    "description": "A sample project description"
                }
            ]
        }
    ]
}
```

### 3. Health Check
**GET** `/api/health`

Checks if the API is running.

#### Success Response (200)
```json
{
    "status": "ok",
    "timestamp": "2025-10-24T12:00:00.000000Z",
    "service": "Lead Assignment API"
}
```

## Usage Examples

### JavaScript (Fetch API)
```javascript
// Create a new lead
const createLead = async (leadData) => {
    try {
        const response = await fetch('http://localhost:8000/api/v1/leads', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(leadData)
        });
        
        const result = await response.json();
        
        if (result.success) {
            console.log('Lead created successfully:', result.message);
            return result;
        } else {
            console.error('Error creating lead:', result.message);
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Network error:', error);
        throw error;
    }
};

// Usage
const leadData = {
    name: "Jane Smith",
    email: "jane@example.com",
    phone: "+1234567890",
    source: "Website",
    message: "Interested in your services",
    developer_alt_name: "SAMGYSSSZ",
    project_alt_name: "SAMLYHBR0"
};

createLead(leadData)
    .then(result => {
        console.log('Lead created:', result.message);
        // Handle success (e.g., show success message, redirect)
    })
    .catch(error => {
        console.error('Failed to create lead:', error);
        // Handle error (e.g., show error message)
    });
```

### PHP (cURL)
```php
<?php
function createLead($leadData) {
    $url = 'http://localhost:8000/api/v1/leads';
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($leadData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Accept: application/json'
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    $result = json_decode($response, true);
    
    if ($httpCode === 201 && $result['success']) {
        return $result;
    } else {
        throw new Exception($result['message'] ?? 'Unknown error');
    }
}

// Usage
$leadData = [
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'phone' => '+1234567890',
    'source' => 'Website',
    'message' => 'Interested in your services',
    'developer_alt_name' => 'SAMGYSSSZ',
    'project_alt_name' => 'SAMLYHBR0'
];

try {
    $result = createLead($leadData);
    echo "Lead created successfully: " . $result['message'];
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
```

### Python (requests)
```python
import requests
import json

def create_lead(lead_data):
    url = 'http://localhost:8000/api/v1/leads'
    
    headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    }
    
    response = requests.post(url, json=lead_data, headers=headers)
    
    if response.status_code == 201:
        result = response.json()
        if result['success']:
            return result
        else:
            raise Exception(result['message'])
    else:
        response.raise_for_status()

# Usage
lead_data = {
    'name': 'John Doe',
    'email': 'john@example.com',
    'phone': '+1234567890',
    'source': 'Website',
    'message': 'Interested in your services',
    'developer_alt_name': 'SAMGYSSSZ',
    'project_alt_name': 'SAMLYHBR0'
}

try:
    result = create_lead(lead_data)
    print(f"Lead created successfully: {result['message']}")
except Exception as e:
    print(f"Error: {e}")
```

## Integration Steps

### Step 1: Get Available Developers and Projects
First, fetch the available developers and their projects to populate your form:

```javascript
// Get developers and projects
const getDevelopersAndProjects = async () => {
    const response = await fetch('http://localhost:8000/api/v1/developers-projects');
    const result = await response.json();
    return result.data;
};
```

### Step 2: Create Your Landing Page Form
Create an HTML form that collects lead information:

```html
<form id="leadForm">
    <input type="text" name="name" placeholder="Full Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="tel" name="phone" placeholder="Phone">
    <input type="text" name="source" placeholder="Source">
    <textarea name="message" placeholder="Message"></textarea>
    <select name="developer_alt_name" required>
        <option value="">Select Developer</option>
        <!-- Populate with API data -->
    </select>
    <select name="project_alt_name" required>
        <option value="">Select Project</option>
        <!-- Populate with API data -->
    </select>
    <button type="submit">Submit Lead</button>
</form>
```

### Step 3: Handle Form Submission
```javascript
document.getElementById('leadForm').addEventListener('submit', async (e) => {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const leadData = Object.fromEntries(formData.entries());
    
    try {
        const result = await createLead(leadData);
        alert('Lead submitted successfully!');
        e.target.reset();
    } catch (error) {
        alert('Error submitting lead: ' + error.message);
    }
});
```

## Error Handling

The API returns appropriate HTTP status codes:
- `200` - Success
- `201` - Created
- `422` - Validation Error
- `404` - Not Found
- `500` - Server Error

Always check the `success` field in the response to determine if the operation was successful.

## Rate Limiting
Currently, there are no rate limits implemented. In production, consider implementing rate limiting to prevent abuse.

## Security Considerations
- The API is currently public. Consider adding authentication for production use.
- Validate all input data on your landing page before sending to the API.
- Use HTTPS in production.
- Consider implementing CORS policies if needed.
