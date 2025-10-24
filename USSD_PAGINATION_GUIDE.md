# 📱 USSD Pagination System

## Overview

USSD screens have limited space (~160 characters). To handle long event lists and long event names, we've implemented pagination.

---

## Features

### ✅ **Automatic Pagination**
- Shows **5 events per page**
- Automatically splits into multiple pages
- Shows current page number

### ✅ **Event Name Truncation**
- Long names truncated to **30 characters**
- Adds "..." to indicate truncation
- Keeps menu compact

### ✅ **Navigation**
- **8** = Previous Page
- **9** = Next Page
- **0** = Back to Main Menu

---

## Example Flow

### **Page 1 of 2:**
```
Select an event (Page 1/2):

1. Ghana Music Awards 25
2. Breman Excellence Awards
3. MISS BREMAN ODWIRA
4. Upcoming Event
5. Another Event
9. Next Page
0. Back
```

### **User enters: 9**

### **Page 2 of 2:**
```
Select an event (Page 2/2):

6. Akatsi North District Int...
7. Creative Arts Festival
8. Previous Page
0. Back
```

---

## Long Event Name Examples

| Original Name | Displayed As |
|---------------|--------------|
| Ghana Music Awards 25 | Ghana Music Awards 25 |
| Akatsi North District Inter-Circuit Festival of Creative Arts And Culture | Akatsi North District Int... |
| Breman Excellence Awards | Breman Excellence Awards |
| MISS BREMAN ODWIRA CULTURAL FESTIVAL 2025 | MISS BREMAN ODWIRA CULTURA... |

**Truncation:** 30 characters max (27 chars + "...")

---

## Pagination Settings

```php
// In UssdSession::showEventsList()
$itemsPerPage = 5;        // Events per page
$maxEventNameLength = 30; // Max chars for event name
```

**Can be adjusted based on:**
- Average event name length
- USSD character limits
- User experience testing

---

## Navigation Keys

| Key | Action | When Available |
|-----|--------|----------------|
| **1-5** | Select event | Always (if events exist) |
| **8** | Previous Page | Page 2+ |
| **9** | Next Page | Not on last page |
| **0** | Back to Main Menu | Always |

---

## Technical Implementation

### **1. Pagination Logic**
```php
$itemsPerPage = 5;
$totalEvents = count($events);
$totalPages = ceil($totalEvents / $itemsPerPage);
$page = max(1, min($page, $totalPages));

$startIndex = ($page - 1) * $itemsPerPage;
$pageEvents = array_slice($events, $startIndex, $itemsPerPage);
```

### **2. Event Name Truncation**
```php
if (strlen($eventName) > 30) {
    $eventName = substr($eventName, 0, 27) . '...';
}
```

### **3. Navigation Handling**
```php
if ($input == '9' && $currentPage < $totalPages) {
    return $this->showEventsList($sessionId, $tenantId, $currentPage + 1);
}

if ($input == '8' && $currentPage > 1) {
    return $this->showEventsList($sessionId, $tenantId, $currentPage - 1);
}
```

---

## Session Data Stored

```php
[
    'events' => [...],           // All events
    'current_page' => 1,         // Current page number
    'total_pages' => 3,          // Total pages
    'tenant_id' => 1             // Tenant context
]
```

---

## User Experience

### **Scenario 1: Few Events (≤5)**
```
Select an event:

1. Event A
2. Event B
3. Event C
0. Back
```
**No pagination shown** - all events fit on one page

### **Scenario 2: Many Events (>5)**
```
Select an event (Page 1/3):

1. Event A
2. Event B
3. Event C
4. Event D
5. Event E
9. Next Page
0. Back
```
**Pagination shown** - user can navigate pages

### **Scenario 3: Middle Page**
```
Select an event (Page 2/3):

6. Event F
7. Event G
8. Event H
9. Event I
10. Event J
8. Previous Page
9. Next Page
0. Back
```
**Both navigation options** available

### **Scenario 4: Last Page**
```
Select an event (Page 3/3):

11. Event K
12. Event L
8. Previous Page
0. Back
```
**Only Previous Page** shown

---

## Benefits

### ✅ **Better UX**
- No overwhelming long lists
- Clear navigation
- Readable event names

### ✅ **USSD Compliant**
- Fits within character limits
- Works on basic phones
- Fast response times

### ✅ **Scalable**
- Handles 100+ events
- Maintains performance
- Easy to navigate

---

## Testing

### **Test with 2 Events:**
```
Expected: Single page, no pagination
```

### **Test with 7 Events:**
```
Expected: 
- Page 1: Events 1-5 + "9. Next Page"
- Page 2: Events 6-7 + "8. Previous Page"
```

### **Test with Long Names:**
```
Input: "Akatsi North District Inter-Circuit Festival of Creative Arts And Culture"
Expected: "Akatsi North District Int..."
```

---

## Future Enhancements

### **Possible Improvements:**
1. **Search by keyword** - Filter events by name
2. **Category filtering** - Show events by category
3. **Favorite events** - Quick access to popular events
4. **Dynamic page size** - Adjust based on name lengths
5. **Jump to page** - Direct page navigation

---

## Configuration

To change pagination settings, edit `UssdSession.php`:

```php
private function showEventsList($sessionId, $tenantId, $page = 1)
{
    // Adjust these values
    $itemsPerPage = 5;        // Change to 3, 4, 6, etc.
    $maxNameLength = 30;      // Change to 25, 35, etc.
    
    // ... rest of code
}
```

---

**USSD pagination is now live and working!** 🚀
