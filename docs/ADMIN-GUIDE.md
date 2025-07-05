# 🔨 Admin Guide - SkillLearn

## 👑 Panduan Administrasi Platform

Panduan lengkap untuk administrator dalam mengelola platform SkillLearn, mulai dari manajemen konten hingga monitoring pengguna.

## 📋 Daftar Isi

- [Dashboard Admin](#-dashboard-admin)
- [Manajemen Pengguna](#-manajemen-pengguna)
- [Manajemen Video](#-manajemen-video)
- [Manajemen Kursus](#-manajemen-kursus)
- [Quiz Builder](#-quiz-builder)
- [Analytics & Reporting](#-analytics--reporting)
- [Content Moderation](#-content-moderation)
- [System Maintenance](#-system-maintenance)
- [Security Management](#-security-management)

## 📊 Dashboard Admin

### Mengakses Dashboard
1. Login dengan akun admin
2. Klik **"Admin Dashboard"** di menu
3. Overview statistik platform akan tampil

### Fitur Dashboard
- **📈 Analytics Overview**: Statistik harian, mingguan, bulanan
- **👥 User Statistics**: Total users, active users, new registrations
- **🎥 Content Stats**: Total videos, courses, completion rates
- **🏆 Performance Metrics**: Popular content, engagement rates

### Key Metrics
- **Daily Active Users (DAU)**
- **Monthly Active Users (MAU)**
- **Content Consumption Rate**
- **User Retention Rate**
- **Platform Growth Metrics**

## 👥 Manajemen Pengguna

### Melihat Daftar Pengguna
1. Masuk ke **"User Management"**
2. Filter berdasarkan:
   - Status akun (Active/Inactive)
   - Role (Student/Admin)
   - Tanggal registrasi
   - Activity level

### Aksi Pengguna
```php
// Fitur yang tersedia:
- View Profile: Lihat detail profil pengguna
- Edit User: Ubah informasi pengguna
- Change Role: Ubah role user (Student ↔ Admin)
- Suspend Account: Suspend akun sementara
- Delete Account: Hapus akun permanen
- Reset Password: Reset password pengguna
```

### Monitoring Aktivitas
- **Login History**: Riwayat login pengguna
- **Learning Progress**: Progress pembelajaran per user
- **Engagement Metrics**: Tingkat keterlibatan
- **Content Interaction**: Interaksi dengan konten

### User Analytics
- **Registration Trends**: Tren pendaftaran pengguna baru
- **Activity Patterns**: Pola aktivitas pengguna
- **Retention Analysis**: Analisis retensi pengguna
- **Demographics**: Data demografis pengguna

## 🎥 Manajemen Video

### Menambah Video Baru
1. Klik **"Add New Video"** di Video Management
2. Isi form video:
   ```
   - Title: Judul video
   - Description: Deskripsi lengkap
   - Category: Pilih kategori
   - Difficulty: Level kesulitan
   - Video URL: Link YouTube/Vimeo
   - Thumbnail: Upload gambar thumbnail
   - Duration: Durasi video
   - Tags: Tag untuk pencarian
   ```
3. **Preview** sebelum publish
4. Klik **"Publish Video"**

### Mengedit Video
1. Cari video di daftar
2. Klik **"Edit"** pada video yang dipilih
3. Update informasi yang diperlukan
4. **Save Changes**

### Manajemen Kategori
```php
// CRUD Kategori:
- Create: Tambah kategori baru
- Read: Lihat daftar kategori
- Update: Edit nama dan deskripsi kategori
- Delete: Hapus kategori (jika tidak ada video)
```

### Video Analytics
- **View Statistics**: Statistik penayangan
- **Engagement Rate**: Tingkat engagement
- **Completion Rate**: Tingkat penyelesaian
- **Popular Videos**: Video paling populer
- **Performance Trends**: Tren performa video

### Content Guidelines
- **Quality Standards**: Video harus berkualitas tinggi
- **Content Appropriateness**: Konten harus sesuai
- **Copyright Compliance**: Tidak melanggar hak cipta
- **Educational Value**: Harus memiliki nilai edukasi

## 📚 Manajemen Kursus

### Membuat Kursus Baru
1. **Course Structure Planning**:
   ```
   Course Title
   ├── Section 1: Fundamentals
   │   ├── Video 1: Introduction
   │   ├── Video 2: Basic Concepts
   │   └── Quiz 1: Fundamentals Quiz
   ├── Section 2: Intermediate
   │   ├── Video 3: Advanced Topics
   │   └── Quiz 2: Intermediate Quiz
   └── Section 3: Advanced
       ├── Video 4: Expert Level
       └── Final Quiz
   ```

2. **Course Creation Process**:
   - Set course title and description
   - Create course sections
   - Add videos to sections
   - Create quizzes for assessment
   - Set course prerequisites
   - Configure completion criteria

### Course Management Features
- **Section Reordering**: Drag & drop section order
- **Video Assignment**: Assign videos to sections
- **Progress Tracking**: Monitor student progress
- **Completion Certificates**: Auto-generate certificates
- **Course Analytics**: Detailed course performance

### Best Practices
- **Logical Progression**: Arrange content logically
- **Clear Objectives**: Define learning objectives
- **Regular Assessment**: Include quizzes and checkpoints
- **Engaging Content**: Mix video types and durations
- **Prerequisites**: Set clear prerequisites

## 🧪 Quiz Builder

### Membuat Quiz Baru
1. **Quiz Setup**:
   ```
   Quiz Information:
   - Title: Quiz title
   - Description: Quiz description
   - Time Limit: Duration in minutes
   - Attempts Allowed: Number of retries
   - Passing Score: Minimum score to pass
   ```

2. **Adding Questions**:
   ```php
   Question Types:
   - Multiple Choice: Single correct answer
   - Multiple Select: Multiple correct answers
   - True/False: Binary choice
   - Fill in the Blank: Text input
   ```

### Question Management
```javascript
// Question Structure:
{
    "question": "What is Laravel?",
    "type": "multiple_choice",
    "options": [
        {"text": "PHP Framework", "correct": true},
        {"text": "Database", "correct": false},
        {"text": "Web Server", "correct": false},
        {"text": "Programming Language", "correct": false}
    ],
    "explanation": "Laravel is a PHP web framework..."
}
```

### Quiz Configuration
- **Randomize Questions**: Shuffle question order
- **Randomize Options**: Shuffle answer options
- **Show Results**: Immediate vs delayed results
- **Feedback Mode**: Show explanations after submission
- **Retake Policy**: Allow retakes with different questions

### Quiz Analytics
- **Attempt Statistics**: Number of attempts per quiz
- **Score Distribution**: Distribution of scores
- **Question Analysis**: Which questions are most difficult
- **Time Analysis**: Average completion time

## 📈 Analytics & Reporting

### Platform Analytics
```sql
-- Key Metrics Dashboard:
SELECT 
    COUNT(*) as total_users,
    COUNT(CASE WHEN last_login >= DATE_SUB(NOW(), INTERVAL 30 DAY) THEN 1 END) as active_users,
    AVG(completion_rate) as avg_completion_rate
FROM users;
```

### Content Performance
- **Video Metrics**:
  - View count and duration
  - Engagement rate
  - Drop-off points
  - User ratings and reviews

- **Course Metrics**:
  - Enrollment numbers
  - Completion rates
  - Time to completion
  - Student satisfaction

### User Engagement
- **Activity Patterns**: When users are most active
- **Learning Paths**: Popular learning sequences
- **Retention Rates**: User retention over time
- **Feature Usage**: Which features are used most

### Custom Reports
1. **Create Custom Report**:
   - Select metrics to include
   - Set date ranges
   - Choose visualization type
   - Schedule automated delivery

2. **Export Options**:
   - PDF reports
   - Excel spreadsheets
   - CSV data export
   - Real-time dashboard URLs

## 🛡️ Content Moderation

### Review Process
1. **Content Approval Workflow**:
   ```
   Submission → Review → Approve/Reject → Publish
   ```

2. **Review Criteria**:
   - Content quality and accuracy
   - Appropriate difficulty level
   - Copyright compliance
   - Educational value

### User-Generated Content
- **Reviews & Ratings**: Moderate user reviews
- **Comments**: Monitor and moderate comments
- **Reports**: Handle user reports on content
- **Spam Detection**: Identify and remove spam

### Moderation Tools
- **Bulk Actions**: Approve/reject multiple items
- **Automated Filters**: Filter inappropriate content
- **Escalation System**: Escalate complex issues
- **Appeals Process**: Handle content appeals

## 🔧 System Maintenance

### Regular Maintenance Tasks
```bash
# Daily Tasks:
php artisan queue:work --daemon
php artisan cache:clear
php artisan view:clear

# Weekly Tasks:
php artisan backup:run
composer update --dry-run
npm audit

# Monthly Tasks:
php artisan telescope:prune
php artisan horizon:snapshot
Database optimization
```

### Performance Monitoring
- **Server Resources**: CPU, Memory, Disk usage
- **Database Performance**: Query optimization
- **Response Times**: Page load speeds
- **Error Rates**: Monitor application errors

### Backup & Recovery
1. **Automated Backups**:
   - Daily database backups
   - Weekly full system backups
   - Monthly archive backups

2. **Recovery Procedures**:
   - Database restoration
   - File system recovery
   - Application rollback

### Updates & Upgrades
- **Security Updates**: Apply critical security patches
- **Framework Updates**: Keep Laravel updated
- **Dependency Updates**: Update NPM and Composer packages
- **Feature Releases**: Deploy new features

## 🔒 Security Management

### User Security
```php
// Security Features:
- Password strength requirements
- Account lockout after failed attempts
- Session timeout configuration
- Two-factor authentication (planned)
```

### Content Security
- **Input Validation**: Validate all user inputs
- **File Upload Security**: Secure file handling
- **XSS Prevention**: Prevent cross-site scripting
- **CSRF Protection**: Token-based protection

### System Security
- **Regular Security Audits**: Periodic security reviews
- **Vulnerability Scanning**: Automated vulnerability detection
- **Access Control**: Role-based access control
- **Audit Logging**: Comprehensive audit trails

### Incident Response
1. **Incident Detection**: Monitor for security incidents
2. **Response Plan**: Predefined response procedures
3. **Investigation**: Forensic analysis of incidents
4. **Recovery**: System and data recovery
5. **Post-Incident Review**: Learn from incidents

## 📞 Support & Troubleshooting

### Common Issues

#### User Issues
```
Problem: User can't login
Solution: 
1. Check account status
2. Verify email/password
3. Reset password if needed
4. Check for account suspension
```

#### Content Issues
```
Problem: Video won't play
Solution:
1. Check video URL validity
2. Verify embedding permissions
3. Test on different browsers
4. Check server connectivity
```

#### Performance Issues
```
Problem: Slow page loading
Solution:
1. Check server resources
2. Optimize database queries
3. Clear application cache
4. Review recent code changes
```

### Emergency Procedures
- **Site Down**: Steps to restore service
- **Data Loss**: Backup restoration procedures
- **Security Breach**: Incident response plan
- **Performance Crisis**: Load balancing and scaling

## 📋 Admin Checklist

### Daily Tasks
- [ ] Check system health dashboard
- [ ] Review new user registrations
- [ ] Monitor content reports
- [ ] Check error logs
- [ ] Review platform metrics

### Weekly Tasks
- [ ] Content moderation review
- [ ] User feedback analysis
- [ ] Performance optimization
- [ ] Security audit
- [ ] Backup verification

### Monthly Tasks
- [ ] Comprehensive analytics review
- [ ] System updates and patches
- [ ] Database optimization
- [ ] User engagement analysis
- [ ] Platform roadmap review

---

## 📚 Resources

### Documentation Links
- [API Documentation](api.md)
- [User Guide](USER-GUIDE.md)
- [Security Policy](../SECURITY.md)
- [Contributing Guide](../CONTRIBUTING.md)

### External Resources
- [Laravel Documentation](https://laravel.com/docs)
- [TailwindCSS Guide](https://tailwindcss.com/docs)
- [Web Security Best Practices](https://owasp.org/www-project-top-ten/)

---

**Happy Administrating! 👑**

*Panduan ini akan terus diperbarui seiring dengan penambahan fitur administrasi baru.*
