# PROJECT CONTEXT

## Project Name
Purchase Order Management

## Tech Stack
- Backend: Laravel 13 (PHP)
- Database: MySQL (MySQL Workbench)
- OCR: Tesseract OCR
- Frontend: Tailwind CSS + Alpine.js

## Core Features

### 1. OCR Processing
- Upload file (JPG, JPEG, PNG, PDF)
- Extract :
    - po_number
    - order_date
    - customer_name
- Extract items :
    - product_name
    - quantity
    - price

### 2. Workflow
- Step 1 : Upload file
- Step 2 : OCR Processing
- Step 3 : Confirmation (edit data)
- Step 4 : save to database

### 3. Database Structure

#### customers
- id
- name
- address
- phone
- created_at
- updated_at

#### products
- id
- name
- price
- created_at
- updated_at

#### purchase_orders
- id
- po_number
- customer_id
- order_date
- total_amount
- status (draft, sent, delivered, completed)
- is_paid (yes, no)
- file_path
- created_at
- updated_at

#### po_items
- id
- purchase_order_id
- product_id
- quantity
- price_at_time
- created_at
- updated_at

#### po_attachments
- id
- purchase_order_id
- file_path
- file_type
- created_at
- updated_at

#### users
- id
- name
- email
- email_verified_at
- password
- remember_token
- created_at
- updated_at

Relasi:
- purchase_orders hasMany po_items
- purchase_orders hasMany po_attachments
- purchase_orders belongsTo customers

### Business Logic
- jika customer belum ada -> auto create
- jika item belum ada -> auto create
- Simpan PO + items menggunakan DB transaction
- Validasi sebelum save

### 5. Goals
- Mengurangi input manual
- Meningkatkan akurasi data
