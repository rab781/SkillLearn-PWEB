# 📡 SkillLearn API Documentation

## 📋 Table of Contents

- [Overview](#overview)
- [Authentication](#authentication)
- [Rate Limiting](#rate-limiting)
- [Response Format](#response-format)
- [Error Handling](#error-handling)
- [Endpoints](#endpoints)
  - [Authentication](#authentication-endpoints)
  - [Videos](#video-endpoints)
  - [Courses](#course-endpoints)
  - [Bookmarks](#bookmark-endpoints)
  - [Quizzes](#quiz-endpoints)
  - [Progress](#progress-endpoints)
  - [Admin](#admin-endpoints)

## 🌟 Overview

The SkillLearn API provides RESTful endpoints for managing educational content, user progress, and platform interactions. All API responses are in JSON format.

**Base URL**: `https://api.skillearn.com/api/v1`  
**Content-Type**: `application/json`  
**API Version**: `v1`

## 🔐 Authentication

### Session-based Authentication

Most endpoints require user authentication through Laravel's session system.

```javascript
// Login request
POST /auth/login
{
    "email": "user@example.com",
    "password": "password123"
}

// Include CSRF token in headers
X-CSRF-TOKEN: {{ csrf_token() }}
```

### API Token Authentication (Future)

```http
Authorization: Bearer YOUR_API_TOKEN
```

## ⏱️ Rate Limiting

- **Public endpoints**: 60 requests per minute
- **Authenticated endpoints**: 1000 requests per minute  
- **Admin endpoints**: 200 requests per minute

Rate limit headers are included in responses:
```http
X-RateLimit-Limit: 1000
X-RateLimit-Remaining: 999
X-RateLimit-Reset: 1640995200
```

## 📊 Response Format

### Success Response
```json
{
    "success": true,
    "data": {
        // Response data
    },
    "message": "Operation completed successfully",
    "meta": {
        "timestamp": "2025-01-05T10:30:00Z",
        "version": "1.0"
    }
}
```

### Paginated Response
```json
{
    "success": true,
    "data": [...],
    "pagination": {
        "current_page": 1,
        "per_page": 15,
        "total": 100,
        "last_page": 7,
        "next_page_url": "https://api.skillearn.com/api/videos?page=2",
        "prev_page_url": null
    }
}
```

## ❌ Error Handling

### Error Response Format
```json
{
    "success": false,
    "error": {
        "code": "VALIDATION_ERROR",
        "message": "The given data was invalid",
        "details": {
            "email": ["The email field is required."],
            "password": ["The password must be at least 8 characters."]
        }
    },
    "meta": {
        "timestamp": "2025-01-05T10:30:00Z",
        "request_id": "req_123456789"
    }
}
```

### HTTP Status Codes

| Code | Description |
|------|-------------|
| 200 | OK - Request successful |
| 201 | Created - Resource created successfully |
| 400 | Bad Request - Invalid request data |
| 401 | Unauthorized - Authentication required |
| 403 | Forbidden - Access denied |
| 404 | Not Found - Resource not found |
| 422 | Unprocessable Entity - Validation error |
| 429 | Too Many Requests - Rate limit exceeded |
| 500 | Internal Server Error - Server error |

## 🔗 Endpoints

### 🔐 Authentication Endpoints

#### POST /auth/login
Login user and create session.

**Request:**
```json
{
    "email": "user@example.com",
    "password": "password123",
    "remember": true
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "John Doe",
            "email": "user@example.com",
            "role": "student"
        },
        "redirect_url": "/dashboard"
    },
    "message": "Login successful"
}
```

#### POST /auth/register
Register new user account.

**Request:**
```json
{
    "name": "John Doe",
    "email": "user@example.com",
    "password": "password123",
    "password_confirmation": "password123"
}
```

#### POST /auth/logout
Logout current user session.

### 🎥 Video Endpoints

#### GET /videos
Get list of videos with filtering and pagination.

**Query Parameters:**
- `category` (string): Filter by category
- `difficulty` (string): Filter by difficulty level
- `search` (string): Search in title and description
- `page` (integer): Page number (default: 1)
- `per_page` (integer): Items per page (default: 15, max: 50)

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Introduction to Laravel",
            "description": "Learn the basics of Laravel framework",
            "thumbnail": "/images/thumbnails/laravel-intro.jpg",
            "video_url": "https://youtube.com/watch?v=abc123",
            "duration": "15:30",
            "difficulty": "beginner",
            "category": {
                "id": 1,
                "name": "Web Development"
            },
            "created_at": "2025-01-01T00:00:00Z",
            "view_count": 1250,
            "is_bookmarked": false
        }
    ],
    "pagination": {...}
}
```

#### GET /videos/{id}
Get specific video details.

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Introduction to Laravel",
        "description": "Learn the basics of Laravel framework",
        "thumbnail": "/images/thumbnails/laravel-intro.jpg",
        "video_url": "https://youtube.com/watch?v=abc123",
        "duration": "15:30",
        "difficulty": "beginner",
        "category": {
            "id": 1,
            "name": "Web Development"
        },
        "instructor": "John Doe",
        "created_at": "2025-01-01T00:00:00Z",
        "view_count": 1250,
        "is_bookmarked": false,
        "user_progress": {
            "watched": true,
            "progress_percentage": 75,
            "last_watched_at": "2025-01-05T10:00:00Z"
        },
        "feedbacks": [
            {
                "id": 1,
                "user_name": "Jane Smith",
                "rating": 5,
                "comment": "Excellent tutorial!",
                "created_at": "2025-01-04T15:30:00Z"
            }
        ]
    }
}
```

#### POST /videos/{id}/view
Track video view for analytics.

**Request:**
```json
{
    "progress_percentage": 25,
    "watch_duration": 180
}
```

### 📚 Course Endpoints

#### GET /courses
Get list of courses.

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "title": "Complete Web Development Course",
            "description": "Master web development from basics to advanced",
            "thumbnail": "/images/courses/web-dev.jpg",
            "difficulty": "intermediate",
            "total_videos": 25,
            "total_duration": "12:45:30",
            "category": {
                "id": 1,
                "name": "Web Development"
            },
            "enrollment_count": 1500,
            "is_enrolled": false,
            "progress_percentage": 0
        }
    ]
}
```

#### GET /courses/{id}
Get course details with sections and videos.

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Complete Web Development Course",
        "description": "Master web development from basics to advanced",
        "thumbnail": "/images/courses/web-dev.jpg",
        "difficulty": "intermediate",
        "total_videos": 25,
        "total_duration": "12:45:30",
        "is_enrolled": true,
        "progress_percentage": 35,
        "sections": [
            {
                "id": 1,
                "title": "HTML Fundamentals",
                "order": 1,
                "videos": [
                    {
                        "id": 1,
                        "title": "HTML Basics",
                        "duration": "20:15",
                        "is_completed": true
                    }
                ]
            }
        ]
    }
}
```

#### POST /courses/{id}/enroll
Enroll user in a course.

### 🔖 Bookmark Endpoints

#### GET /user/bookmarks
Get user's bookmarked videos.

**Response:**
```json
{
    "success": true,
    "data": [
        {
            "id": 1,
            "video": {
                "id": 5,
                "title": "Advanced PHP Concepts",
                "thumbnail": "/images/php-advanced.jpg",
                "duration": "25:45"
            },
            "created_at": "2025-01-03T14:20:00Z"
        }
    ]
}
```

#### POST /bookmarks
Toggle bookmark for a video.

**Request:**
```json
{
    "video_id": 5
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "is_bookmarked": true,
        "bookmark_id": 15
    },
    "message": "Video bookmarked successfully"
}
```

#### DELETE /bookmarks/{id}
Remove bookmark.

### 🧪 Quiz Endpoints

#### GET /quiz/{id}
Get quiz questions and structure.

**Response:**
```json
{
    "success": true,
    "data": {
        "id": 1,
        "title": "Laravel Basics Quiz",
        "description": "Test your Laravel knowledge",
        "time_limit": 1800,
        "total_questions": 10,
        "questions": [
            {
                "id": 1,
                "question": "What is Laravel?",
                "type": "multiple_choice",
                "options": [
                    {"id": 1, "text": "PHP Framework"},
                    {"id": 2, "text": "Database"},
                    {"id": 3, "text": "Web Server"},
                    {"id": 4, "text": "Programming Language"}
                ]
            }
        ]
    }
}
```

#### POST /quiz/{id}/submit
Submit quiz answers for grading.

**Request:**
```json
{
    "answers": [
        {"question_id": 1, "answer_id": 1},
        {"question_id": 2, "answer_id": 3}
    ]
}
```

**Response:**
```json
{
    "success": true,
    "data": {
        "quiz_result_id": 25,
        "score": 8,
        "total_questions": 10,
        "percentage": 80,
        "passed": true,
        "detailed_results": [
            {
                "question_id": 1,
                "correct": true,
                "user_answer": 1,
                "correct_answer": 1
            }
        ]
    }
}
```

### 📊 Progress Endpoints

#### GET /user/progress
Get user's overall learning progress.

**Response:**
```json
{
    "success": true,
    "data": {
        "total_videos_watched": 45,
        "total_courses_enrolled": 5,
        "total_courses_completed": 2,
        "total_quizzes_taken": 12,
        "average_quiz_score": 85.5,
        "learning_streak": 7,
        "recent_activity": [
            {
                "type": "video_completed",
                "title": "JavaScript Promises",
                "date": "2025-01-05T09:30:00Z"
            }
        ]
    }
}
```

#### GET /courses/{id}/progress
Get progress for specific course.

### 👨‍💼 Admin Endpoints

#### GET /admin/analytics
Get platform analytics (Admin only).

**Response:**
```json
{
    "success": true,
    "data": {
        "users": {
            "total": 2500,
            "new_this_month": 150,
            "active_today": 320
        },
        "content": {
            "total_videos": 450,
            "total_courses": 25,
            "total_quizzes": 80
        },
        "engagement": {
            "total_views": 125000,
            "average_session_duration": "25:30",
            "completion_rate": 78.5
        }
    }
}
```

#### GET /admin/users
Get user list with management options (Admin only).

#### POST /admin/videos
Create new video (Admin only).

#### PUT /admin/videos/{id}
Update video details (Admin only).

#### DELETE /admin/videos/{id}
Delete video (Admin only).

## 📝 Examples

### JavaScript/Fetch API
```javascript
// Get videos with authentication
const response = await fetch('/api/videos', {
    method: 'GET',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    credentials: 'same-origin'
});

const data = await response.json();
console.log(data);
```

### cURL Examples
```bash
# Get videos
curl -X GET "https://skillearn.com/api/videos" \
  -H "Accept: application/json"

# Login
curl -X POST "https://skillearn.com/api/auth/login" \
  -H "Content-Type: application/json" \
  -d '{"email":"user@example.com","password":"password123"}'

# Toggle bookmark
curl -X POST "https://skillearn.com/api/bookmarks" \
  -H "Content-Type: application/json" \
  -H "X-CSRF-TOKEN: your-csrf-token" \
  -d '{"video_id":5}'
```

## 🔄 Versioning

The API uses semantic versioning. Current version is `v1`.

- **Major version**: Breaking changes
- **Minor version**: New features, backward compatible
- **Patch version**: Bug fixes

## 📞 Support

For API support:
- 📧 Email: [api-support@skillearn.com](mailto:api-support@skillearn.com)
- 📚 Documentation: [https://docs.skillearn.com](https://docs.skillearn.com)
- 🐛 Issues: [GitHub Issues](https://github.com/rab781/SkillLearn-PWEB/issues)

---

**Last Updated**: January 2025  
**API Version**: v1.0
