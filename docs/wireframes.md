# StudentHub Portal — Low-Fidelity UI Wireframes & Layout Blueprints

This document specifies the structural wireframes, layout grids, visual hierarchies, and responsive breakpoints for the core StudentHub pages adhering to Practical 1 and Practical 3 requirements.

---

## 1. Responsive Grid & Breakpoint Specifications

| Viewport Category | Screen Width | Layout Grid Configuration | Target Devices |
|---|:---:|:---:|---|
| **Mobile Viewport** | `< 650px` | 1-Column Stack, Collapsed Nav, Full-width Forms | Smartphones (iPhone, Android) |
| **Tablet Viewport** | `650px – 900px` | 2-Column Responsive Grid, Horizontal Nav Scroll | iPad, Galaxy Tab, Surface |
| **Desktop Viewport** | `> 900px (Max 1200px)` | 4-Column Metric Cards, 2-Column Dashboard Cards | Laptops, Desktop Monitors, iMac |

---

## 2. Global Shell Wireframe (Header, Navigation & Footer)

```
+-------------------------------------------------------------------------------+
| [LOGO] StudentHub Portal             Academic & Campus Portal     [User / Theme] |
+-------------------------------------------------------------------------------+
| [Home] [About] [Dashboard] [Events] [Attendance] [Assignment] [FAQ] [Contact]   |
+-------------------------------------------------------------------------------+
| > Breadcrumb: Home > Current Page                                              |
+-------------------------------------------------------------------------------+
|                                                                               |
|                             PAGE MAIN CONTENT AREA                            |
|                                                                               |
+-------------------------------------------------------------------------------+
| (c) 2026 StudentHub Portal. Designed with Semantic HTML5 & Accessibility.     |
+-------------------------------------------------------------------------------+
```

---

## 3. Home Page Wireframe (`pages/index.html`)

```
+-------------------------------------------------------------------------------+
| [LOGO] StudentHub Portal                                         [Dark Mode]  |
+-------------------------------------------------------------------------------+
| [Home (Active)] [About] [Dashboard] [Events] [Notice] [FAQ] [Feedback] [Sign In]|
+-------------------------------------------------------------------------------+
| Welcome to StudentHub Portal                     [🔑 Sign In]  [+ Register]  |
| Streamlining academic workflows and campus communication in one place.        |
+-------------------------------------------------------------------------------+
| [ 10,000+ Enrolled ]  [ 4 Workshops ]  [ 500+ Resources ]  [ 5 Circulars ]    |
+-------------------------------------------------------------------------------+
|                           CHARUSAT CAMPUS GALLERY                             |
|  [ < Prev ]          +------------------------------+           [ Next > ]    |
|                      |  [ HD Campus Slider Image ]  |                         |
|                      +------------------------------+                         |
+-------------------------------------------------------------------------------+
| CORE PORTAL CAPABILITIES                                                      |
| +-------------------------+ +-------------------------+                       |
| | 🎯 Event & Workshops   | | 📊 Academic Dashboard   |                       |
| | Technical bootcamps...  | | Attendance & grades...  |                       |
| | [Browse Events ->]      | | [View Dashboard ->]     |                       |
| +-------------------------+ +-------------------------+                       |
| +-------------------------+ +-------------------------+                       |
| | ⏱️ Class Schedule       | | 📢 Circulars & Notices  |                       |
| | Real-time timetables... | | Mid-sem dates & news... |                       |
| | [Check Timetable ->]    | | [Read Notices ->]       |                       |
| +-------------------------+ +-------------------------+                       |
+-------------------------------------------------------------------------------+
```

---

## 4. Student Registration Wireframe (`pages/register.html`)

```
+-------------------------------------------------------------------------------+
| > Breadcrumb: Home > Student Registration                                     |
+-------------------------------------------------------------------------------+
| Create Student Account                         [🔑 Already Registered? Sign In]|
| Register as a new student to access attendance and workshop events.           |
+-------------------------------------------------------------------------------+
| [ 🎓 Student Profile ] [ 📊 Attendance Stats ] [ 📝 Assignments ] [ 🎟️ Pass ] |
+-------------------------------------------------------------------------------+
| NEW STUDENT REGISTRATION FORM                                                 |
|                                                                               |
| Full Name:                [ e.g. Rahul Verma                             ]   |
| Official Email:           [ e.g. rahul.verma@studenthub.com              ]   |
|                                                                               |
| Department:               [ Information Technology           v ]              |
| Semester:                 [ 4th Semester                     v ]              |
|                                                                               |
| Create Password:          [ ************                 ] (Strength: High)   |
| Confirm Password:         [ ************                 ]                    |
|                                                                               |
| [X] I agree to the Student Code of Conduct & Academic Terms                   |
|                                                                               |
|                      [ ✨ Complete Registration ]                             |
|                                                                               |
|                 Already have an account? [Sign in here]                       |
+-------------------------------------------------------------------------------+
```

---

## 5. Student Dashboard Wireframe (`pages/dashboard.html`)

```
+-------------------------------------------------------------------------------+
| > Breadcrumb: Home > Student Dashboard                                        |
+-------------------------------------------------------------------------------+
| Academic Dashboard                                              [👤 Profile]  |
| Welcome back, Mayank Shukal (23CE045) | Computer Engineering - Semester 4    |
+-------------------------------------------------------------------------------+
| [ 📊 86.4% Attendance ] [ 📚 2 Pending Labs ] [ ⏱️ 4 Classes ] [ 📢 5 Notices ] |
+-------------------------------------------------------------------------------+
| QUICK ACCESS MODULES                                                          |
| +-----------------------+ +-----------------------+ +-----------------------+ |
| | 📅 Attendance Tracker | | 📝 Course Assignments | | ⏱️ Class Timetable    | |
| | Subject breakdown...  | | Lab journal deadlines | | Daily room slots...   | |
| +-----------------------+ +-----------------------+ +-----------------------+ |
+-------------------------------------------------------------------------------+
| RECENT ACADEMIC ACTIVITIES                                                    |
| #  | Date         | Type                  | Course / Module        | Status   |
|----+--------------+-----------------------+------------------------+----------|
| 1  | Aug 10, 2026 | Assignment Submission | Web Technologies (PHP) | Pending  |
| 2  | Aug 08, 2026 | Lab Graded (28/30)    | Data Structures (C++)  | Done     |
| 3  | Aug 07, 2026 | Mid-Sem Exam Schedule | Examination Cell       | Urgent   |
+-------------------------------------------------------------------------------+
```

---

## 6. Events & Workshop Catalog Wireframe (`pages/events.html`)

```
+-------------------------------------------------------------------------------+
| > Breadcrumb: Home > Events & Workshops                                       |
+-------------------------------------------------------------------------------+
| Campus Events & Technical Workshops                    [🎟️ My Event Passes]  |
| Register for hackathons, expert guest seminars, and bootcamps.                |
+-------------------------------------------------------------------------------+
| [ 4 Total Events ]   [ 2 Upcoming ]   [ ₹50K Prizes ]   [ 2 My Passes ]       |
+-------------------------------------------------------------------------------+
| [ Search events by title, speaker... ]  [ All Types v ]  [ Clear Button ]     |
+-------------------------------------------------------------------------------+
| # | Event Name                   | Cat.     | Date & Venue     | Seats | Action|
|---+------------------------------+----------+------------------+-------+-------|
| 1 | Full-Stack Web Dev Bootcamp  | Workshop | Aug 20 / Lab 3   | 45/60 | [Reg] |
| 2 | AI & Machine Learning Seminar| Seminar  | Aug 28 / Audit.  |120/150| [Pass]|
| 3 | Hackathon Code 4.0 (24h)     | Comp.    | Sep 12 / Inno.   |140/200| [Reg] |
+-------------------------------------------------------------------------------+
```

---

## 7. Knowledgebase FAQ Wireframe (`pages/faq.html`)

```
+-------------------------------------------------------------------------------+
| > Breadcrumb: Home > Frequently Asked Questions                               |
+-------------------------------------------------------------------------------+
| Frequently Asked Questions                            [📞 Need Help? Contact] |
| Instant answers to academic, account, and campus policies.                    |
+-------------------------------------------------------------------------------+
| [ 16+ Topics ]       [ 24/7 Self-Service ]   [ Verified ]   [ CHARUSAT Norms ] |
+-------------------------------------------------------------------------------+
| [ Search questions or keywords...       ]  [ Filter by Category v ] [ Clear ] |
+-------------------------------------------------------------------------------+
| [v] 1. What is the StudentHub Portal?                                         |
|     StudentHub is the unified digital campus portal integrating attendance,   |
|     events, circulars, and student records into a single interface.           |
|-------------------------------------------------------------------------------|
| [>] 2. Who can access the StudentHub Portal?                                  |
|-------------------------------------------------------------------------------|
| [>] 3. How do I register for a new student account?                           |
|-------------------------------------------------------------------------------|
| [>] 4. How are passwords protected in StudentHub?                             |
+-------------------------------------------------------------------------------+
```

---

## 8. Feedback & Ratings Wireframe (`pages/feedback.html`)

```
+-------------------------------------------------------------------------------+
| > Breadcrumb: Home > Portal Feedback                                          |
+-------------------------------------------------------------------------------+
| Share Your Feedback                                            [❓ View FAQs] |
| Help us improve StudentHub by sharing your thoughts and feature requests.     |
+-------------------------------------------------------------------------------+
| [ 4.8 / 5.0 Rating ]   [ 500+ Reviews ]   [ Bi-Weekly Updates ] [ Confidential]|
+-------------------------------------------------------------------------------+
| SUBMIT YOUR FEEDBACK                                                          |
|                                                                               |
| Full Name:              [ Mayank Shukal                             ]         |
| Email Address:          [ mayank.shukal@studenthub.edu.in           ]         |
| Category:               [ Portal Usability & UI Design            v ]         |
| Overall Rating:         [ ★ ] [ ★ ] [ ★ ] [ ★ ] [ ★ ]  5/5 (Excellent)        |
|                                                                               |
| Feedback & Remarks:     +---------------------------------------------------+ |
|                         | The responsive layout and dark mode make checking | |
|                         | daily timetables very fast on mobile!             | |
|                         +---------------------------------------------------+ |
|                         (Character Count: 75 / 500)                           |
|                                                                               |
|                         [ 🚀 Send Feedback ]                                  |
+-------------------------------------------------------------------------------+
```

---

## 9. Administrator Panel Wireframe (`pages/admin.html`)

```
+-------------------------------------------------------------------------------+
| [LOGO] StudentHub Portal           Administrator Panel          [Sign Out]    |
+-------------------------------------------------------------------------------+
| All Students Management                                  [+ Add New Student]  |
| Manage and view all registered StudentHub student records.                    |
+-------------------------------------------------------------------------------+
| [ 15 Total Students ]  [ 8 IT Students ]  [ 5 CE Students ]  [ 14 Active ]    |
+-------------------------------------------------------------------------------+
| [ Search by name, roll no, email...   ]  [ Dept v ]  [ Sem v ]  [ Clear ]     |
+-------------------------------------------------------------------------------+
| # | Name         | Enrollment | Department | Sem | Email           | Actions  |
|---+--------------+------------+------------+-----+-----------------+----------|
| 1 | John Doe     | 23IT001    | IT         | 4th | john@hub.com    |[👁️][✏️][🗑️]|
| 2 | Mayank Shukal| 23CE045    | CE         | 4th | mayank@hub.edu  |[👁️][✏️][🗑️]|
| 3 | Aarav Sharma | 23IT012    | IT         | 4th | aarav@hub.com   |[👁️][✏️][🗑️]|
+-------------------------------------------------------------------------------+
```
