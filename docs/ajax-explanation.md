## 🔄 AJAX FLOW DIAGRAM SKILLEARN

### AJAX REQUEST LIFECYCLE

```
┌─────────────────────────────────────────────────────────────────┐
│                    AJAX LIFECYCLE DI SKILLEARN                 │
└─────────────────────────────────────────────────────────────────┘

1. USER ACTION (Click, Submit, Load)
   │
   ├─ onClick="toggleBookmark(123)"
   ├─ onSubmit="submitFeedback()"
   └─ onLoad="loadDashboard()"
   
2. JAVASCRIPT FUNCTION
   │
   ├─ Validate input
   ├─ Show loading spinner
   └─ Prepare request data
   
3. FETCH API CALL
   │
   ├─ Method: GET/POST/PUT/DELETE
   ├─ Headers: CSRF-TOKEN, Content-Type
   ├─ Body: JSON data (if POST/PUT)
   └─ Credentials: same-origin
   
4. LARAVEL BACKEND PROCESSING
   │
   ├─ Route matching (/api/bookmarks)
   ├─ Middleware (auth, CSRF)
   ├─ Controller method
   ├─ Database operation
   └─ JSON response
   
5. JAVASCRIPT RESPONSE HANDLING
   │
   ├─ Check response.status
   ├─ Parse JSON data
   ├─ Update DOM elements
   └─ Show notifications

6. UI UPDATE
   │
   ├─ Change button colors
   ├─ Update counters
   ├─ Show/hide elements
   └─ Animate changes
```

### CONCRETE EXAMPLES FROM SKILLEARN

#### Example 1: Bookmark Toggle
```
User clicks ❤️ → toggleBookmark(123) → POST /api/bookmarks → Database insert → 
JSON response → Button becomes ❤️ (red) → Toast notification
```

#### Example 2: Dashboard Load
```
Page loads → loadDashboard() → GET /api/dashboard → Database queries → 
JSON response → Update stats cards → Load video lists → Show categories
```

#### Example 3: Feedback Submit
```
User types comment → submitFeedback() → POST /api/feedbacks → Database insert → 
JSON response → Add comment to list → Clear form → Show success message
```
