# StudentHub Portal — Practical 1 & 2 Comprehensive Laboratory Report & Viva Guide

---

## Practical 1: Project Initiation, Requirement Analysis, Sitemap, Wireframe, and GitHub Setup

### 1. Aim & Problem Scope
**Aim**: Initiate the semester-long StudentHub web portal by identifying the problem scope, user roles, key academic modules, navigation architecture, and minimum 10 pages. Create a complete sitemap, low-fidelity wireframes, standard project directory structure, README file, and Git/GitHub version control setup.

**Problem Definition**:
Campus information and student services are currently fragmented across disconnected systems: attendance is maintained on departmental registers or legacy portals, workshop registrations occur through unlinked forms, timetables are distributed via PDFs, and student records are maintained across departmental silos. StudentHub resolves this by delivering a centralized, role-based, accessible, and responsive portal for students, faculty, and administrators.

---

### 2. Key Analysis & Viva Question Solutions (Practical 1)

#### Question 1: What is a URL and what are the parts of a URL?
**Answer**:
A **Uniform Resource Locator (URL)** is an address mechanism defined in **RFC 3986** that identifies a resource on the World Wide Web and specifies the protocol used to access it.

A standard URL consists of the following components:

$$\text{URL} = \text{scheme} \,:\,//\, [\text{userinfo}@]\,\text{host}\,[:\text{port}]\,/\,\text{path}\,[\text{?query}]\,[\text{\#fragment}]$$

```
  https://student:pass@charusat.edu:8080/pages/events.html?category=Workshop&sort=date#passes
  └─┬─┘   └─────┬────┘ └──────┬─────┘ └─┬──┘ └───────┬────────┘ └──────────────┬───────────────┘ └───┬───┘
Protocol   Userinfo       Host        Port      File Path             Query String             Fragment
```

1. **Protocol / Scheme (`https://`)**: Specifies the communication protocol. `https` enforces encrypted Transport Layer Security (TLS/SSL) over port 443; `http` communicates unencrypted over port 80.
2. **Userinfo (`student:pass@`)**: Optional credentials preceding the host used in basic authentication.
3. **Host / Domain Name (`charusat.edu`)**: The human-readable domain name resolved by Domain Name System (DNS) servers to the destination server's IP address (e.g., `172.217.16.206`).
4. **Port Number (`:8080`)**: Dedicated TCP endpoint port on the server. If omitted, standard defaults are assumed (80 for HTTP, 443 for HTTPS).
5. **Path (`/pages/events.html` or `/php/api.php`)**: The hierarchical server-side filesystem directory path or virtual router path pointing to the requested resource.
6. **Query String (`?category=Workshop&sort=date`)**: Key-value query parameters preceded by `?` and separated by `&`. Used for dynamic data queries, search parameters, and pagination.
7. **Fragment / Anchor (`#passes`)**: Denotes a specific element ID or bookmark on the client DOM. The browser scrolls to the element without re-requesting the page from the server.

---

#### Question 2: How is an HTML file processed in a web browser?
**Answer**:
When an HTML file is requested, the web browser's rendering engine (e.g., Blink in Chrome, Gecko in Firefox, WebKit in Safari) executes the **Critical Rendering Path (CRP)** in 7 distinct sequential phases:

```mermaid
graph LR
    A[1. Byte Stream] --> B[2. Tokenizer]
    B --> C[3. DOM Tree]
    D[CSS Files] --> E[CSSOM Tree]
    C --> F[4. Render Tree]
    E --> F
    F --> G[5. Layout/Reflow]
    G --> H[6. Painting]
    H --> I[7. Compositing]
```

1. **Byte Stream Conversion & Decoding**: The browser receives raw bytes of HTML from the network or disk and decodes them into characters based on the specified character encoding (e.g., `UTF-8` declared in `<meta charset="UTF-8">`).
2. **Tokenization**: The browser's lexical analyzer parses characters into discrete tokens representing start tags (`<html>`, `<body>`), attribute names/values (`class="active"`), text nodes, and closing tags (`</html>`).
3. **DOM Tree Construction**: Tokens are converted into Node objects and assembled into a parent-child hierarchical tree called the **Document Object Model (DOM)** tree.
4. **CSSOM Tree Construction**: As the HTML parser encounters `<link rel="stylesheet" href="style.css">`, it suspends DOM construction or requests the CSS asynchronously. CSS rules are tokenized and assembled into the **CSS Object Model (CSSOM)** tree, calculating cascade and inheritance rules.
5. **Render Tree Synthesis**: The browser combines the DOM and CSSOM trees into the **Render Tree**. Non-rendered nodes (e.g., `<head>`, `<script>`, `<meta>`, elements with `display: none`) are pruned away. Nodes with `visibility: hidden` are retained because they occupy physical space.
6. **Layout (Reflow)**: The rendering engine calculates the exact geometric coordinates, bounding box dimensions, margins, paddings, and positions of each render tree node relative to the viewport.
7. **Painting & Rasterization**: Vector nodes from the layout stage are converted into actual colored pixels across raster memory buffers.
8. **Compositing**: Multiple paint layers (e.g., sticky headers, modals, CSS 3D transforms) are flattened into a single image buffer by the GPU and drawn on screen.

---

#### Question 3: How will page navigation flow be managed among all HTML pages?
**Answer**:
Page navigation across all 15 StudentHub pages is managed through a multi-tiered architecture:
1. **Shared Semantic Navigation Bar**:
   Each page includes a `<header>` containing `<nav aria-label="Primary Navigation">` using relative file paths (`index.html`, `dashboard.html`, `events.html`). Relative linking ensures portal functionality regardless of the deployment host port or subfolder.
2. **Active State Indicators**:
   The active page is visually and programmatically marked with `class="active"` and `aria-current="page"` to inform sighted students and screen reader users of their location.
3. **Hierarchical Breadcrumbs**:
   Sub-pages include an accessible `<nav aria-label="Breadcrumb">` containing an ordered list (`<ol>`) linking back to `Home`, facilitating effortless backtracking.
4. **Role-Based Redirection Guard**:
   Upon authentication via `php/login.php`, students are directed to `dashboard.html`, while administrators are directed to `admin.html`. JavaScript checks session existence in `localStorage` / cookies to restrict unauthorized access to admin pages.
5. **Skip-to-Content Bypass Link**:
   An accessible `<a href="#main-content" class="skip-link">Skip to main content</a>` enables keyboard users to bypass repetitive navigation items directly to the page's core content area.

---

#### Question 4: How will GitHub commits be maintained after each practical?
**Answer**:
GitHub commits are maintained using **Git version control** following the **Conventional Commits Specification** and an atomic practical-branching strategy:
1. **Atomic Practical Commits**: Every laboratory task is committed in an isolated, logically complete state containing both implementation source code and documentation.
2. **Conventional Commit Syntax**:
   ```
   feat(practical-N): <brief title>
   
   - Detailed change 1
   - Detailed change 2
   Refs: Practical N
   ```
3. **Branching Model**:
   - `main`: Production-ready, fully verified stable codebase.
   - `practical-01-setup`, `practical-02-semantic-html`, `practical-03-responsive-css`, etc.: Feature branches merged via Pull Requests after testing.
4. **Git Commands Sequence per Practical**:
   ```bash
   git status
   git add pages/ docs/ css/ js/
   git commit -m "feat(practical-2): build accessible semantic HTML5 skeletons with skip links"
   git push origin main
   ```

---

## Practical 2: Semantic HTML5 Pages with Accessibility-Ready Structure

### 1. Aim & Problem Scope
**Aim**: Develop clean, static HTML5 skeletons for at least 10 StudentHub pages (Home, About, Register, Login, Dashboard, Events, Profile, Contact, Admin, FAQ, and Feedback). Use proper semantic tags, accessible form controls, skip-to-content links, breadcrumb navigation, and consistent content hierarchy.

---

### 2. Key Analysis & Viva Question Solutions (Practical 2)

#### Question 1: Are semantic tags such as header, nav, main, section, article, aside, and footer used properly?
**Answer**:
Yes. Rather than utilizing generic, non-semantic `<div>` tags, StudentHub uses specific HTML5 landmark elements:
- `<header role="banner">`: Houses the university title, branding subtitle, portal logo, and student profile widget.
- `<nav aria-label="Primary Navigation">`: Encloses primary navigational links across the 15 portal pages.
- `<main id="main-content" role="main">`: Houses the dominant, unique content of each page.
- `<section class="admin-container">`: Groups thematic content areas (e.g., student statistics, filtering tools, registration forms).
- `<article class="faq-item">` / `<article class="dashboard-card">`: Encapsulates self-contained, independently distributable units of content (e.g., individual FAQ questions, circular announcements, event cards).
- `<aside>`: Contains supplementary content, such as helpdesk support callout boxes, sidebar links, and related advice.
- `<footer role="contentinfo">`: Encloses copyright information, accessibility compliance statements, and university credits.

**Semantic vs Non-Semantic Comparison**:
```html
<!-- Non-Semantic (Anti-pattern) -->
<div id="header"><div class="menu">...</div></div>
<div id="content">...</div>
<div id="footer">...</div>

<!-- Semantic HTML5 (StudentHub Implementation) -->
<header role="banner">
    <nav aria-label="Primary Navigation">...</nav>
</header>
<main id="main-content" role="main">
    <section>
        <article>...</article>
    </section>
</main>
<footer role="contentinfo">...</footer>
```

---

#### Question 2: Are form labels, headings, image alt text, and navigation links accessible?
**Answer**:
Yes, StudentHub achieves full compliance with WCAG 2.1 AA standards:
1. **Form Labels**: Every input, select, and textarea is explicitly linked with a `<label for="...">` matching the input's `id`:
   ```html
   <label for="feedbackEmail">Email Address <span aria-hidden="true">*</span></label>
   <input type="email" id="feedbackEmail" name="email" required aria-required="true">
   ```
2. **Heading Hierarchy**: Each page enforces exactly **one `<h1>`** tag for the portal identity, followed by **`<h2>`** for the specific page view, and **`<h3>`** for nested sections/cards. Heading levels are never skipped.
3. **Image Alt Text**: Images include meaningful descriptions for assistive technology rather than empty or generic tags:
   - `alt="StudentHub Official Portal Logo"`
   - `alt="CHARUSAT Campus Gallery - Computer Engineering Building"`
4. **Accessible Navigation Links**: Links contain explicit descriptive text (`Browse Events &rarr;`, `View Attendance &rarr;`) avoiding non-descriptive phrases such as "Click Here".

---

#### Question 3: Is the page structure consistent across all pages?
**Answer**:
Yes. All 15 portal pages share an identical outer shell:
1. Identical `<head>` metadata with UTF-8 character encoding, mobile viewport tags, and unified stylesheet link `../css/style.css`.
2. Skip-to-content accessibility link at the very top of `<body>`.
3. Standardized `<header>` and `<nav>` layout matching color tokens `#1e3a5f`.
4. Consistent `<main id="main-content">` wrapper constrained to a maximum width of `1200px` centered with auto margins.
5. Standardized `<section class="admin-container">` card styling.
6. Unified `<footer>` with copyright and accessibility metadata.

---

### 3. Intermediate & Advanced Extensions (Practical 2)

#### Intermediate Extension: Breadcrumb Navigation
Implemented using semantic `<nav aria-label="Breadcrumb" class="breadcrumb-nav">` with an ordered list `<ol class="breadcrumb">` and `aria-current="page"` indicating current location:
```html
<nav aria-label="Breadcrumb" class="breadcrumb-nav">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
        <li class="breadcrumb-item" aria-current="page">Frequently Asked Questions</li>
    </ol>
</nav>
```

#### Advanced Extension: Skip-to-Content Link & Keyboard Accessibility
An accessible skip link placed as the first child of `<body>` remains visually offscreen until focused via `Tab`, then slides into view with a high-contrast yellow focus ring:
```html
<a href="#main-content" class="skip-link">Skip to main content</a>
```
```css
.skip-link {
    position: absolute;
    top: -60px;
    left: 20px;
    background: #2563eb;
    color: #ffffff;
    padding: 10px 18px;
    z-index: 99999;
    transition: top 0.2s ease;
}
.skip-link:focus {
    top: 0;
    outline: 3px solid #facc15;
}
```

---

## 4. Viva Examination Question Bank (Practicals 1 & 2)

| # | Question | Short Model Answer |
|---|---|---|
| **V-01** | What is the difference between HTML and HTML5? | HTML5 introduced native audio/video tags, canvas graphics, semantic tags (`header`, `nav`, `main`, `article`), local storage (`localStorage`), and responsive form input types (`email`, `date`, `number`). |
| **V-02** | What is the purpose of `<meta name="viewport" ...>`? | It instructs mobile browsers to render the page at the device's physical screen width with a 1:1 initial zoom scale, preventing mobile devices from defaulting to a zoomed-out 980px desktop view. |
| **V-03** | Why should `display: none` be used cautiously for accessibility? | Elements with `display: none` are completely removed from the accessibility tree, meaning screen readers will not announce them. For visually-hidden accessible text, use CSS clipping (`clip: rect(0,0,0,0)`). |
| **V-04** | What is the DOM? | The Document Object Model is an in-memory, language-neutral, object-oriented representation of the HTML document structured as a tree of nodes modifiable via JavaScript. |
| **V-05** | Explain the difference between `<section>` and `<article>`. | `<section>` groups related thematic content under a heading; `<article>` represents an independent, self-contained entity that makes sense on its own (e.g., a blog post, circular notice, or user review). |
| **V-06** | What is the difference between relative and absolute URLs? | An absolute URL includes the full scheme and domain (`https://site.com/pages/about.html`); a relative URL specifies the path relative to the current directory (`about.html` or `../css/style.css`). |
| **V-07** | What is reflow and repaint? | **Reflow (Layout)** calculates element positions and geometric dimensions. **Repaint** paints element colors, borders, shadows, and text onto the screen pixels. Reflow always triggers repaint, but repaint does not trigger reflow. |
| **V-08** | What does ARIA stand for, and why is it needed? | Accessible Rich Internet Applications (WAI-ARIA) is a W3C specification of attributes (`aria-expanded`, `aria-label`, `role`) that convey interactive widget roles, states, and properties to assistive technologies. |
| **V-09** | What are the standard HTTP status code ranges? | 1xx (Informational), 2xx (Success e.g. 200 OK), 3xx (Redirection e.g. 301/302), 4xx (Client Errors e.g. 400 Bad Request, 404 Not Found), 5xx (Server Errors e.g. 500 Internal Error). |
| **V-10** | Why is `password_hash()` superior to `md5()` or `sha256()`? | `password_hash()` utilizes bcrypt or Argon2 with built-in per-user salting and a configurable computational cost factor, protecting against rainbow table attacks and GPU-accelerated brute force. |
