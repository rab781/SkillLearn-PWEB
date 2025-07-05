# 🤝 Contributing to SkillLearn

Thank you for your interest in contributing to SkillLearn! This document provides guidelines and instructions for contributing to this project.

## 📋 Table of Contents

- [Code of Conduct](#code-of-conduct)
- [Getting Started](#getting-started)
- [Development Setup](#development-setup)
- [Making Changes](#making-changes)
- [Coding Standards](#coding-standards)
- [Testing](#testing)
- [Submitting Changes](#submitting-changes)
- [Bug Reports](#bug-reports)
- [Feature Requests](#feature-requests)

## 📜 Code of Conduct

By participating in this project, you agree to abide by our Code of Conduct:

- Be respectful and inclusive
- Use welcoming and inclusive language
- Be collaborative and constructive
- Focus on what is best for the community
- Show empathy towards other community members

## 🚀 Getting Started

### Prerequisites

- PHP 8.1 or higher
- Composer
- Node.js 16+ and NPM
- MySQL/PostgreSQL
- Git

### Development Setup

1. **Fork the repository**
   ```bash
   # Click "Fork" on GitHub, then clone your fork
   git clone https://github.com/YOUR_USERNAME/SkillLearn-PWEB.git
   cd SkillLearn-PWEB
   ```

2. **Add upstream remote**
   ```bash
   git remote add upstream https://github.com/rab781/SkillLearn-PWEB.git
   ```

3. **Install dependencies**
   ```bash
   composer install
   npm install
   ```

4. **Setup environment**
   ```bash
   cp .env.example .env
   php artisan key:generate
   # Configure your database in .env
   php artisan migrate --seed
   php artisan storage:link
   ```

5. **Start development servers**
   ```bash
   # Terminal 1
   npm run dev
   
   # Terminal 2
   php artisan serve
   ```

## ✏️ Making Changes

### Branch Naming Convention

- `feature/feature-name` - New features
- `fix/bug-description` - Bug fixes
- `docs/description` - Documentation updates
- `refactor/component-name` - Code refactoring
- `test/test-description` - Test additions/updates

### Workflow

1. **Create a new branch**
   ```bash
   git checkout -b feature/your-feature-name
   ```

2. **Keep your branch up to date**
   ```bash
   git fetch upstream
   git rebase upstream/main
   ```

3. **Make your changes**
   - Write clear, concise code
   - Follow coding standards
   - Add tests for new functionality
   - Update documentation as needed

4. **Commit your changes**
   ```bash
   git add .
   git commit -m "feat: add new user authentication system"
   ```

## 📏 Coding Standards

### PHP Standards (Laravel)

- Follow **PSR-12** coding standards
- Use **Laravel best practices**
- Use **Eloquent ORM** for database operations
- Follow **Repository pattern** for data access
- Use **Form Requests** for validation
- Use **Resources** for API responses

### Frontend Standards

- Use **semantic HTML5** elements
- Follow **TailwindCSS** utility classes
- Use **vanilla JavaScript** or **Alpine.js**
- Implement **responsive design** principles
- Follow **accessibility (a11y)** guidelines

### Database Standards

- Use **descriptive table names** (plural, snake_case)
- Use **descriptive column names** (snake_case)
- Add **proper indexes** for performance
- Use **foreign key constraints**
- Add **timestamps** to all tables

### Naming Conventions

```php
// Controllers
class UserController extends Controller

// Models
class User extends Model

// Migrations
2025_01_01_000000_create_users_table.php

// Views
resources/views/users/index.blade.php

// Routes
Route::get('/users', [UserController::class, 'index'])->name('users.index');

// CSS Classes (TailwindCSS)
<div class="flex items-center justify-between p-4 bg-white rounded-lg shadow">
```

## 🧪 Testing

### Running Tests

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test tests/Feature/UserTest.php

# Run tests with coverage
php artisan test --coverage

# Run tests for specific feature
php artisan test --filter=UserAuthentication
```

### Writing Tests

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123')
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }
}
```

## 📤 Submitting Changes

### Pull Request Process

1. **Ensure your branch is up to date**
   ```bash
   git fetch upstream
   git rebase upstream/main
   ```

2. **Push your changes**
   ```bash
   git push origin feature/your-feature-name
   ```

3. **Create Pull Request**
   - Go to GitHub and create a new Pull Request
   - Use the PR template
   - Provide clear description of changes
   - Link related issues
   - Add screenshots if UI changes

### Pull Request Template

```markdown
## Description
Brief description of changes made.

## Type of Change
- [ ] Bug fix
- [ ] New feature
- [ ] Breaking change
- [ ] Documentation update

## Related Issues
Fixes #(issue number)

## Testing
- [ ] Tests pass locally
- [ ] New tests added (if applicable)
- [ ] Manual testing completed

## Screenshots (if applicable)
Add screenshots of UI changes.

## Checklist
- [ ] Code follows project standards
- [ ] Self-review completed
- [ ] Comments added for complex code
- [ ] Documentation updated
- [ ] No breaking changes (or documented)
```

## 🐛 Bug Reports

Use [GitHub Issues](https://github.com/rab781/SkillLearn-PWEB/issues) with the bug report template:

```markdown
**Environment**
- OS: [e.g., Windows 11, macOS Big Sur, Ubuntu 20.04]
- PHP Version: [e.g., 8.1.0]
- Laravel Version: [e.g., 10.x]
- Browser: [e.g., Chrome 96, Firefox 95]

**Describe the Bug**
A clear and concise description of what the bug is.

**Steps to Reproduce**
1. Go to '...'
2. Click on '....'
3. Scroll down to '....'
4. See error

**Expected Behavior**
A clear and concise description of what you expected to happen.

**Actual Behavior**
A clear and concise description of what actually happened.

**Screenshots**
If applicable, add screenshots to help explain your problem.

**Additional Context**
Add any other context about the problem here.
```

## 💡 Feature Requests

Use [GitHub Discussions](https://github.com/rab781/SkillLearn-PWEB/discussions) for feature requests:

```markdown
**Problem Description**
Is your feature request related to a problem? Please describe.

**Proposed Solution**
Describe the solution you'd like to see implemented.

**Alternative Solutions**
Describe any alternative solutions or features you've considered.

**Use Cases**
Provide specific examples of how this feature would be used.

**Additional Context**
Add any other context, mockups, or examples about the feature request.
```

## 📝 Commit Message Guidelines

### Conventional Commits

We use [Conventional Commits](https://www.conventionalcommits.org/) specification:

```
<type>[optional scope]: <description>

[optional body]

[optional footer(s)]
```

### Types

- `feat` - New features
- `fix` - Bug fixes
- `docs` - Documentation changes
- `style` - Code style changes (formatting, etc.)
- `refactor` - Code refactoring
- `test` - Test additions or updates
- `chore` - Maintenance tasks
- `perf` - Performance improvements
- `ci` - CI/CD changes

### Examples

```bash
feat: add user profile edit functionality
fix: resolve video upload timeout issue
docs: update API documentation for quiz endpoints
style: format code according to PSR-12 standards
refactor: restructure course controller methods
test: add unit tests for bookmark system
chore: update Laravel to version 10.x
perf: optimize database queries for dashboard
```

## 🏷️ Release Process

1. Version follows [Semantic Versioning](https://semver.org/)
2. Create release branch: `release/v1.1.0`
3. Update CHANGELOG.md
4. Update version numbers
5. Create Pull Request to main
6. Tag release after merge
7. Deploy to production

## 📞 Getting Help

- 📧 Email: [skilllearn.dev@gmail.com](mailto:skilllearn.dev@gmail.com)
- 💬 Discussions: [GitHub Discussions](https://github.com/rab781/SkillLearn-PWEB/discussions)
- 📚 Documentation: [docs/](docs/)
- 🐛 Issues: [GitHub Issues](https://github.com/rab781/SkillLearn-PWEB/issues)

## 🙏 Thank You

Thank you for contributing to SkillLearn! Your contributions help make this project better for everyone.

---

**Happy Contributing! 🚀**
