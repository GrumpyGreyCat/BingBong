# HEXAGONE_CTF

* A specialized Capture The Flag (CTF) platform featuring an immersive, high-performance Matrix terminal interface.

---

## PROJECT OVERVIEW
* Hexagone_CTF is a security competition framework built on the Symfony PHP ecosystem. It transitions away from standard web aesthetics to provide a professional  dark-mode operator experience. The platform manages the full lifecycle of a CTF, from user enrollment and clearance level management to challenge deployment and real-time rank calculation.

---

## CORE CAPABILITIES

### 1. Terminal Interface
* **Aesthetic**: Implements a deep charcoal and neon green color palette with glassmorphism and scanline overlays.
* **Typography**: Standardized on JetBrains Mono for all technical data and Inter for high-readability prose.
* **Responsiveness**: Fully adaptive navbar and grid systems that collapse into terminal-style lists on mobile devices.

### 2. Challenge Infrastructure
* **Dynamic Nodes**: Challenges are represented as network nodes with three difficulty tiers: Easy, Medium, and Hard.
* **Component Injection**: Support for custom HTML/JS challenge payloads delivered via secure sandboxed iframes.
* **Validation**: Integrated flag submission system with immediate feedback and flash message logging.

### 3. Administrative Suite
* **User Manifest**: A centralized database for administrators to monitor operators, adjust clearance levels, or terminate accounts.
* **Room Database**: A management interface for the creation and configuration of challenge sectors and associated payloads.
* **Real-time Search**: Optimized JavaScript filtering to query large datasets of users without page reloads.

---

## TECHNICAL SPECIFICATIONS

### Backend
* **Framework**: Symfony CLI 5.16.1
* **Language**: PHP 8.4.1
* **Database**: Doctrine ORM - Connected to a Docker image of _postgres:16-alpine_

### Frontend
* **Templating Engine**: Twig
* **CSS Framework**: Bootstrap 5.3 (heavily customized)
* **Iconography**: FontAwesome 6

---

## INSTALLATION AND DEPLOYMENT

### Local Environment Setup
1. **Clone Source**
   ```bash 
   git clone https://github.com/GrumpyGreyCat/BingBong.git
   ```

2. **Initialize Dependencies**
   ```bash
   composer install
   ```

3. **Database Migration**
   ```bash
   php bin/console doctrine:migrations:migrate
    ```

4. **Execute Platform**
   ```bash
   symfony serve
   ```
   

---

## SYSTEM ARCHITECTURE

* **Security Model**: Utilizes Symfony Security components to differentiate between standard Operators and Root Administrators.
* **Styling Architecture**: Custom CSS variables allow for rapid global color scheme adjustments across the entire platform.
* **Error Handling**: Custom event listeners intercept 403, 404, and 500 status codes to render themed terminal error screens.

---

## DIRECTORY MAPPING
* **src/Controller/**: Core routing and logic for CTF operations.
* **templates/**: Immersive Twig templates and base layouts.
* **public/css/**: The primary design engine for the Matrix aesthetic.
* **public/js/**: Logic for terminal-style data sorting and filtering.
