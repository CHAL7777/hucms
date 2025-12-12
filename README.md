# hucms
Haramaya University Cafeteria Management System (HUCMS)
# Haramaya University Cafeteria Management System (HUCMS)

## Overview
HUCMS is a **Command-Line Interface (CLI) based Cafeteria Management System** developed in **PHP** with **MariaDB**.  
It automates **student meal verification, logging, and reporting** in the university cafeteria. The system ensures **accuracy, accountability, and efficiency** for both staff and students.

---

## Features

### Student Management
- Register new students with full profile (ID, name, department, program)
- View student meal history
- Enforce unique student IDs

### Meal Period Management
- Admin can add meal periods (Breakfast, Lunch, Dinner)
- Define start and end times for each meal period
- System validates meal requests based on active meal period

### Meal Verification
- Staff can verify student meals using **student ID**
- Prevent duplicate meals within the same period
- Logs each verification with **staff ID**, **student ID**, **meal period**, and **status**

### Reporting
- Daily and weekly meal logs
- Student-specific meal history
- Duplicate meal attempt tracking

### CLI Interface
- Fully interactive **command-line interface**
- Works in **terminal/command prompt**
- Simple text input and feedback for staff/admin actions

---

## Requirements

- PHP 7.4+ / PHP 8.x
- MariaDB or MySQL
- Apache/Nginx (optional if testing via CLI only)
- Composer (optional, for future dependencies)

---

## Ins
