# StudentHub Portal — Centralized Academic & Campus Platform

[![HTML5](https://img.shields.io/badge/HTML5-Semantic%20%26%20Accessible-E34F26?logo=html5&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/HTML)
[![CSS3](https://img.shields.io/badge/CSS3-Flexbox%20%26%20Grid-1572B6?logo=css3&logoColor=white)](https://developer.mozilla.org/en-US/docs/Web/CSS)
[![JavaScript](https://img.shields.io/badge/JavaScript-ES6%2B-F7DF1E?logo=javascript&logoColor=black)](https://developer.mozilla.org/en-US/docs/Web/JavaScript)
[![PHP](https://img.shields.io/badge/PHP-8.x%20Backend-777BB4?logo=php&logoColor=white)](https://www.php.net/)
[![WCAG](https://img.shields.io/badge/Accessibility-WCAG%202.1%20AA-059669)](https://www.w3.org/WAI/standards-guidelines/wcag/)

> **Course**: Advanced Web Technologies / Full-Stack Web Development  
> **Institution**: Charotar University of Science and Technology (CHARUSAT)  
> **Student ID**: 25IT094 / 23CE045 | **Semester**: 4th Semester  
> **Course Outcomes Mapped**: CO1 (Web Foundation & Requirements), CO2 (Semantic UI & Responsive Design)

---

## Table of Contents
1. [Project Initiation & Problem Scope](#1-project-initiation--problem-scope)
2. [Objectives](#2-objectives)
3. [User Roles & Access Control Matrix](#3-user-roles--access-control-matrix)
4. [Functional & Non-Functional Requirements](#4-functional--non-functional-requirements)
5. [Complete Portal Sitemap & Page Hierarchy](#5-complete-portal-sitemap--page-hierarchy)
6. [URL Anatomy & Routing Structure](#6-url-anatomy--routing-structure)
7. [Browser HTML Processing Engine](#7-browser-html-processing-engine)
8. [Navigation Flow & State Management](#8-navigation-flow--state-management)
9. [Project Folder Architecture](#9-project-folder-architecture)
10. [Git Workflow & Commit Strategy](#10-git-workflow--commit-strategy)
11. [Setup & Running Locally](#11-setup--running-locally)

---

## 1. Project Initiation & Problem Scope

### 1.1 Problem Statement
In contemporary higher education institutions, academic workflows and student services are often fragmented across disparate platforms: attendance records are kept on departmental portals, workshop registrations occur through third-party Google Forms, exam circulars are pinned on physical notice boards, and student profiles require separate sign-ins. This causes:
- Information latency and missed academic deadlines.
- Inconsistent user experiences lacking mobile responsiveness and accessibility compliance.
- Absence of role-based security, audit tracking, and centralized reporting for campus administration.

### 1.2 Proposed Solution: StudentHub Portal
**StudentHub** is an all-in-one, responsive, accessible, and database-backed web portal engineered to centralize campus life. It serves students, faculty mentors, and academic administrators by offering unified dashboards for attendance analytics, workshop passes, circular notices, lab assignments, and student records.

---

## 2. Objectives
1. **Centralize Student Services**: Provide single-sign-on access to academic dashboards, attendance breakdowns, and official notices.
2. **Accessible by Design**: Guarantee full keyboard navigability, high color contrast, ARIA landmarks, and skip-to-content links following **WCAG 2.1 AA** standards.
3. **Mobile-First Responsiveness**: Ensure smooth layout adaptation across smartphone, tablet, laptop, and 4K desktop screens using CSS Flexbox and Grid.
4. **Interactive Experience**: Deliver zero-reload dynamic rendering, image sliders, collapsible FAQ accordions, and persistent light/dark themes.
5. **Secure Backend Architecture**: Implement server-side input sanitization, bcrypt password hashing, session regeneration, and structured JSON/MySQL data storage.

---

## 3. User Roles & Access Control Matrix

StudentHub enforces a strict **Role-Based Access Control (RBAC)** model comprising three distinct user roles:

| Module / Page | Public / Guest | Enrolled Student | Administrator |
|---|:---:|:---:|:---:|
| **Home (`index.html`)** | Read Only | Full Access | Full Access |
| **About Us (`about.html`)** | Read Only | Full Access | Full Access |
| **User Registration (`register.html`)** | Submit Account | Redirect to Dashboard | Add Students Directly |
| **Account Login (`login.html`)** | Authenticate | Redirect to Dashboard | Redirect to Admin Panel |
| **Student Dashboard (`dashboard.html`)** | Restricted | Personalized View | View as Super-admin |
| **Events & Workshops (`events.html`)** | Browse Events | Register & Get Passes | Add / Edit / Delete Events |
| **Attendance Tracker (`attendance.html`)** | Restricted | View Own Stats (85%+) | Edit Attendance Records |
| **Course Assignments (`assignment.html`)** | Restricted | Submit Labs & View Marks | Upload Assignments & Grade |
| **Class Timetable (`timetable.html`)** | Restricted | View Batch Schedule | Update Classroom Slots |
| **Notice Board (`notice.html`)** | Read Only | Read & Download Circulars | Publish & Pin Circulars |
| **Student Profile (`profile.html`)** | Restricted | View & Edit Profile | Manage User Accounts |
| **Helpdesk FAQ (`faq.html`)** | Full Access | Full Access | Update Knowledgebase |
| **Feedback Submission (`feedback.html`)** | Submit Feedback | Submit Feedback | Analyze Ratings & Reviews |
| **Admin Panel (`admin.html`)** | Restricted | Restricted | Full Student & System CRUD |

---

## 4. Functional & Non-Functional Requirements

### Functional Requirements (FR)
- **FR-01 (Student Onboarding)**: The portal shall allow prospective students to register with validated full name, institutional email, department, semester, and a secure password.
- **FR-02 (Authentication & Session)**: The system shall authenticate registered users using `password_verify()`, establish secure session cookies, and regenerate session IDs upon login to thwart fixation attacks.
- **FR-03 (Student Dashboard)**: Students shall access a personalized dashboard summarizing attendance percentages, pending assignments, daily timetable slots, and latest circulars.
- **FR-04 (Event Management & Passes)**: Students can search, filter, and register for technical workshops and hackathons, receiving an instant digital pass linked to their roll number.
- **FR-05 (Academic Attendance Tracking)**: The portal shall display subject-wise theory and practical attendance percentages, alerting students when attendance drops below the mandatory 80% threshold.
- **FR-06 (Assignment Repository)**: Students can view pending lab assignment deadlines and submit code repositories or lab reports.
- **FR-07 (Notice & Circular Distribution)**: Administrative announcements and examination schedules shall be dynamically rendered with category badges and publish timestamps.
- **FR-08 (Profile Self-Service)**: Students can view and update their contact phone number, emergency contacts, bio, and department information.
- **FR-09 (Administrative Student CRUD)**: Administrators can add new student records, filter by department/semester, view profile details in modal dialogues, and perform soft/hard deletions.
- **FR-10 (Event Administration)**: Administrators can create workshops with allocated seat limits, venues, dates, and track participant lists.
- **FR-11 (Knowledgebase FAQ)**: The portal provides a searchable, categorized accordion FAQ system with real-time keyword filtering.
- **FR-12 (Audited Feedback)**: Users can submit categorized feedback with a 5-star rating system and constructive remarks stored for administrative review.

### Non-Functional Requirements (NFR)
- **NFR-01 (Usability & Accessibility)**: All interactive controls must support keyboard navigation (`Tab`, `Shift+Tab`, `Enter`, `Space`) and include explicit `aria-*` tags and visible `:focus-visible` rings.
- **NFR-02 (Cross-Device Responsiveness)**: The interface shall fluidly scale from 320px mobile screens to large desktop monitors without horizontal clipping.
- **NFR-03 (Performance)**: First Contentful Paint (FCP) under 1.2s; assets optimized and scripts loaded asynchronously.
- **NFR-04 (Data Security)**: Plaintext passwords are never persisted. Passwords use `PASSWORD_BCRYPT` (cost factor 10). Input strings are sanitized with `htmlspecialchars()` to eliminate Cross-Site Scripting (XSS).
- **NFR-05 (Maintainability)**: Clear separation of concerns with dedicated directories for HTML views (`pages/`), styles (`css/`), client scripts (`js/`), backend endpoints (`php/`), data stores (`json/`), and project specifications (`docs/`).

---

## 5. Complete Portal Sitemap & Page Hierarchy

```
StudentHub Portal (Root: /)
│
├── Public & Informational Tier
│   ├── Home (pages/index.html)
│   │   ├── Campus Gallery Slider
│   │   ├── Metric Counters (10,000+ Enrolled, 4 Workshops)
│   │   └── Core Capabilities Directory
│   ├── About Us (pages/about.html)
│   ├── Frequently Asked Questions (pages/faq.html)
│   ├── Feedback & Ratings (pages/feedback.html)
│   └── Contact & Support Helpdesk (pages/contact.html)
│
├── Authentication & Onboarding
│   ├── Account Sign In (pages/login.html)
│   └── Student Registration (pages/register.html)
│
├── Authenticated Student Workspace
│   ├── Student Dashboard (pages/dashboard.html)
│   ├── Attendance Analytics (pages/attendance.html)
│   ├── Lab Assignments (pages/assignment.html)
│   ├── Weekly Timetable (pages/timetable.html)
│   ├── Campus Notices & Circulars (pages/notice.html)
│   ├── Events & Workshop Passes (pages/events.html)
│   └── Student Profile (pages/profile.html)
│
└── Administrative Management Console
    └── All Students Management & Analytics (pages/admin.html)
        ├── Dynamic Enrollment Filtering
        ├── Add Student Modal
        ├── Student Details Modal
        └── System Metrics Aggregation
```

---

## 6. URL Anatomy & Routing Structure

Every web resource accessed within StudentHub is addressed through a structured **Uniform Resource Locator (URL)**:

```
           userinfo       host          port         path               query string           fragment
          ┌───┴───┐ ┌──────┴──────┐ ┌───┴───┐ ┌────────┴────────┐ ┌───────────────┴───────────────┐ ┌────┴───┐
  https://john:pass@studenthub.edu:8080/pages/events.html?category=Workshop&sort=date#passes
  └─┬─┘             └───────┬──────┘
  scheme                authority
```

### Breakdown of URL Components:
1. **Scheme / Protocol (`https://`)**: Defines the communication protocol used to transfer data securely via Transport Layer Security (TLS/SSL).
2. **Authority / Host (`studenthub.edu`)**: Domain name resolved via Domain Name System (DNS) to the server's IP address.
3. **Port Number (`:8080`)**: Dedicated communication channel on the server (defaults to 80 for HTTP, 443 for HTTPS).
4. **Path (`/pages/events.html` or `/php/api.php`)**: The hierarchical file path or virtual route designating the exact server resource.
5. **Query String (`?category=Workshop&sort=date`)**: Key-value query parameters preceded by `?` and separated by `&` used for search, filtering, and pagination.
6. **Fragment Identifier / Anchor (`#passes`)**: Directs the client browser viewport to a specific DOM element ID without issuing a new network request.

---

## 7. Browser HTML Processing Engine

When a user opens any StudentHub HTML page, the web browser executes the **Critical Rendering Path (CRP)** in 7 sequential phases:

```mermaid
graph TD
    A[1. Network Fetch & Byte Stream] --> B[2. Tokenization & HTML Parsing]
    B --> C[3. DOM Tree Construction]
    D[CSS Files & Stylesheets] --> E[CSS Parsing & CSSOM Tree]
    C --> F[4. Render Tree Synthesis]
    E --> F
    F --> G[5. Layout & Reflow Phase]
    G --> H[6. Painting Phase - Rasterization]
    H --> I[7. Compositing & GPU Display]
```

1. **Bytes to Tokens**: The browser converts raw HTTP byte streams into characters based on `UTF-8` encoding and tokenizes HTML tags (`<html>`, `<head>`, `<body>`, etc.).
2. **DOM Tree Construction**: Tokens are converted into Node objects structured hierarchically into the Document Object Model (DOM) tree.
3. **CSSOM Tree Construction**: External CSS (`css/style.css`) is parsed into the CSS Object Model (CSSOM) tree containing cascade and inheritance rules.
4. **Render Tree Synthesis**: DOM and CSSOM are combined, filtering out non-rendered elements (`display: none`, `<head>`, `<meta>`).
5. **Layout (Reflow)**: The browser calculates exact coordinates, box models, widths, and heights for each visual node on the viewport.
6. **Paint (Rasterization)**: Pixels are filled with colors, borders, gradients, text, and images across layer buffers.
7. **Compositing**: Multiple GPU layers are flattened and drawn to the display screen.

---

## 8. Navigation Flow & State Management

```mermaid
flowchart LR
    Guest[Guest Visitor] --> Home[Home: index.html]
    Home --> Register[Register: register.html]
    Home --> Login[Login: login.html]
    Home --> FAQ[FAQ: faq.html]
    Home --> Feedback[Feedback: feedback.html]
    Home --> Contact[Contact: contact.html]
    
    Login -->|Role: Student| Dashboard[Dashboard: dashboard.html]
    Login -->|Role: Admin| AdminPanel[Admin Panel: admin.html]
    
    Dashboard --> Events[Events: events.html]
    Dashboard --> Attendance[Attendance: attendance.html]
    Dashboard --> Assignments[Assignments: assignment.html]
    Dashboard --> Timetable[Timetable: timetable.html]
    Dashboard --> Notices[Notices: notice.html]
    Dashboard --> Profile[Profile: profile.html]
    
    AdminPanel --> ManageStudents[Manage Students CRUD]
    AdminPanel --> ManageEvents[Manage Events CRUD]
```

---

## 9. Project Folder Architecture

```
Studenthub1/
│
├── pages/                                # Semantic HTML5 Static & Dynamic Skeletons
│   ├── index.html                        # Portal Landing Page & Campus Slider
│   ├── about.html                        # University & Portal Mission Statement
│   ├── register.html                     # Student Registration Form
│   ├── login.html                        # Secure Authentication & Role Dispatcher
│   ├── dashboard.html                    # Personalized Student Performance Dashboard
│   ├── events.html                       # Campus Workshops, Hackathons & Passes
│   ├── attendance.html                   # Subject-wise Attendance Breakdown
│   ├── assignment.html                   # Lab Journal Submissions & Deadlines
│   ├── timetable.html                    # Weekly Classroom & Lab Timetable
│   ├── notice.html                       # Official Campus Circulars
│   ├── profile.html                      # Student Profile & Contact Info
│   ├── faq.html                          # Accessible Collapsible Knowledgebase
│   ├── feedback.html                     # 5-Star Rating & Suggestions Form
│   ├── contact.html                      # Helpdesk & Campus Contacts
│   └── admin.html                        # Administrator Student CRUD & Analytics
│
├── css/
│   └── style.css                         # Unified Design System, CSS Grid, Flexbox, Dark Mode
│
├── js/
│   └── script.js                         # DOM Manipulation, Slider, Theme Switcher, Forms
│
├── json/                                 # Test Datasets & Local File Storage
│   ├── users.json                        # User Records & Credentials
│   ├── events.json                       # Campus Event Catalog
│   ├── notices.json                      # University Circulars
│   ├── assignments.json                  # Course Assignments & Status
│   ├── attendance.json                   # Attendance Records
│   ├── timetable.json                    # Weekly Schedule
│   ├── faqs.json                         # 16+ Curated Q&A Records
│   ├── feedback.json                     # User Reviews & Ratings
│   ├── contacts.json                     # Helpdesk Inquiries
│   └── studenthub.json                   # Global Site Metadata
│
├── php/                                  # Backend Service Endpoints
│   ├── config.php                        # Session Init, CORS, JSON IO Helpers
│   ├── api.php                           # Unified Gateway Dispatcher
│   ├── login.php                         # Credential Verification & Session Manager
│   ├── register.php                      # Registration Validator & Password Hasher
│   ├── logout.php                        # Session Termination
│   ├── events.php                        # Event Listing & Pass Registration API
│   ├── notices.php                       # Circulars Fetcher
│   ├── dashboard.php                     # Student Metrics Aggregator
│   ├── admin.php                         # Administrative Operations API
│   ├── contact.php                       # Contact Form Processor
│   ├── feedback.php                      # Feedback Processor
│   └── profile.php                       # Profile Updater
│
├── docs/                                 # Engineering Specifications & Lab Manual Deliverables
│   ├── sitemap.md                        # Formal Sitemap & Role Hierarchy
│   ├── sitemap.jpg                       # Visual Sitemap Diagram
│   ├── wireframes.md                     # Responsive Low-Fidelity UI Wireframes
│   ├── accessibility_checklist.md        # WCAG 2.1 AA Compliance Checklist
│   └── practical_1_2_report.md           # Lab Submission Report & Viva Guide
│
└── readme.md                             # Project Initiation & Architecture Guide
```

---

## 10. Git Workflow & Commit Strategy

To ensure reproducible academic milestones and maintainable version history, all commits follow the **Conventional Commits Specification**:

```
<type>(<scope>): <concise subject description>

[optional body providing technical details]
[optional footer referencing practical or issue ID]
```

### Commit Type Nomenclature:
- `feat`: Addition of a new user-facing page, component, or endpoint.
- `fix`: Bug resolution in script logic, CSS styling, or validation.
- `docs`: Documentation updates (README, sitemap, wireframes, checklists).
- `style`: Formatting, spacing, whitespace cleanup without functional change.
- `refactor`: Structural code improvement preserving behavior.
- `test`: Addition of test cases or sample JSON datasets.

### Practical Milestone Commit History:
- `feat(practical-1): initialize project structure, sitemap, wireframes, and readme documentation`
- `feat(practical-2): develop semantic accessible HTML5 pages with skip links and breadcrumbs`
- `style(practical-3): implement mobile-first responsive layout with CSS Grid and Flexbox`
- `feat(practical-4): add image slider, collapsible FAQ accordions, and persistent dark mode`
- `feat(practical-5): add regex form validation and visual password strength meter`
- `feat(practical-6): render dynamic JSON data using Fetch API with search and pagination`
- `feat(practical-7): implement server-side PHP form processing with JSON storage`
- `feat(practical-8): design normalized MySQL database schema, ER model, and PDO connection`
- `feat(practical-9): secure student registration with password_hash and duplicate email checks`
- `feat(practical-10): implement role-based session authentication, timeout, and remember-me`
- `feat(practical-11): create complete student management CRUD with search and confirmation`
- `feat(practical-12): build event management CRUD with poster upload and MIME validation`
- `feat(practical-13): develop asynchronous PHP JSON REST API with zero-reload Fetch CRUD`
- `feat(practical-14): build admin dashboard with RBAC, dynamic menus, and audit logging`

---

## 11. Setup & Running Locally

### Prerequisites
- Modern Web Browser (Google Chrome 110+, Mozilla Firefox 110+, or Microsoft Edge)
- PHP 8.0+ / XAMPP / WAMP / Built-in PHP Development Server
- Git 2.30+

### Launching with Built-in PHP Server:
```bash
# Clone the repository
git clone https://github.com/25it094/Studenthub.git
cd Studenthub

# Launch the development server from project root
php -S localhost:8000

# Open in browser
http://localhost:8000/pages/index.html
```

---

## 12. Authors & Academic Integrity
- **Lead Developer**: Mayank Shukal (Student ID: 25IT094 / 23CE045)
- **Institution**: Charotar University of Science and Technology (CHARUSAT)
- **Academic Year**: 2026
- **License**: MIT Academic License
