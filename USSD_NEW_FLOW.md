# 🎯 New USSD Flow - SmartCastGH

## Main Menu Flow

```
User dials: *920*01#
         ↓
┌────────────────────────────────┐
│ Welcome to SmartCastGH!        │
│                                │
│ 1. Vote for Nominee            │
│ 2. Vote on an Event            │
│ 3. Create an Event             │
│ 4. Exit                        │
└────────────────────────────────┘
```

---

## Flow 1: Vote for Nominee (Quick Vote by Shortcode)

```
User selects: 1
         ↓
┌────────────────────────────────┐
│ Enter nominee shortcode:       │
│ _____                          │
└────────────────────────────────┘
         ↓
User enters: AB12
         ↓
┌────────────────────────────────┐
│ Vote for: John Doe             │
│ Select vote package:           │
│                                │
│ 1. 1 Vote - GHS 1.00          │
│ 2. 5 Votes - GHS 5.00         │
│ 3. 10 Votes - GHS 10.00       │
│ 0. Back                        │
└────────────────────────────────┘
         ↓
User selects: 1
         ↓
┌────────────────────────────────┐
│ Confirm your vote:             │
│ Event: Ghana Music Awards      │
│ Contestant: John Doe           │
│ Votes: 1                       │
│ Amount: GHS 1.00               │
│                                │
│ 1. Confirm                     │
│ 0. Cancel                      │
└────────────────────────────────┘
         ↓
User selects: 1
         ↓
┌────────────────────────────────┐
│ Payment initiated!             │
│ Please approve the payment     │
│ on your phone.                 │
│ Amount: GHS 1.00               │
│ Your vote will be recorded     │
│ after payment approval.        │
│ Thank you!                     │
└────────────────────────────────┘
```

**Benefits:**
- ✅ Fastest voting method
- ✅ User just needs shortcode
- ✅ Skips event/category selection
- ✅ Direct to vote packages

---

## Flow 2: Vote on an Event (Browse Events)

```
User selects: 2
         ↓
┌────────────────────────────────┐
│ Select an event:               │
│                                │
│ 1. Ghana Music Awards 25       │
│ 2. Breman Excellence Awards    │
│ 3. MISS BREMAN ODWIRA         │
│ 0. Back                        │
└────────────────────────────────┘
         ↓
User selects: 1
         ↓
┌────────────────────────────────┐
│ Select a category:             │
│                                │
│ 1. Best Artist                 │
│ 2. Best Song                   │
│ 0. Back                        │
└────────────────────────────────┘
         ↓
User selects: 1
         ↓
┌────────────────────────────────┐
│ Select a contestant:           │
│                                │
│ 1. John Doe (AB12)            │
│ 2. Jane Smith (CD34)          │
│ 0. Back                        │
└────────────────────────────────┘
         ↓
[Continue with vote packages and confirmation as in Flow 1]
```

**Benefits:**
- ✅ Browse all events
- ✅ See all categories
- ✅ See all contestants
- ✅ Good for discovery

---

## Flow 3: Create an Event (Registration Link)

```
User selects: 3
         ↓
┌────────────────────────────────┐
│ Registration link sent to      │
│ 233545644749                   │
│                                │
│ Visit:                         │
│ http://localhost/smartcast/    │
│ register                       │
│                                │
│ Thank you!                     │
└────────────────────────────────┘
```

**Benefits:**
- ✅ Quick event organizer onboarding
- ✅ SMS with registration link
- ✅ User can register on web

---

## Flow 4: Exit

```
User selects: 4
         ↓
┌────────────────────────────────┐
│ Thank you for using            │
│ SmartCastGH!                   │
└────────────────────────────────┘
```

---

## Error Handling

### Invalid Shortcode:
```
User enters: XYZ99
         ↓
┌────────────────────────────────┐
│ Shortcode 'XYZ99' not found.   │
│                                │
│ 1. Try again                   │
│ 0. Main menu                   │
└────────────────────────────────┘
```

### No Active Events:
```
User selects: 2
         ↓
┌────────────────────────────────┐
│ No active events available.    │
└────────────────────────────────┘
```

---

## Navigation Map

```
Main Menu
├── 1. Vote for Nominee
│   ├── Enter Shortcode
│   ├── Select Bundle
│   ├── Confirm Vote
│   └── Payment
│
├── 2. Vote on Event
│   ├── Select Event
│   ├── Select Category
│   ├── Select Contestant
│   ├── Select Bundle
│   ├── Confirm Vote
│   └── Payment
│
├── 3. Create Event
│   └── Send Registration Link (END)
│
└── 4. Exit
    └── Thank You (END)
```

---

## States Used

| State | Purpose |
|-------|---------|
| `STATE_WELCOME` | Show main menu |
| `STATE_MAIN_MENU` | Process main menu selection |
| `STATE_ENTER_SHORTCODE` | Get shortcode from user |
| `STATE_SELECT_EVENT` | Show events list |
| `STATE_SELECT_CATEGORY` | Show categories |
| `STATE_SELECT_CONTESTANT` | Show contestants |
| `STATE_SELECT_BUNDLE` | Show vote packages |
| `STATE_CONFIRM_VOTE` | Confirm vote details |
| `STATE_PAYMENT` | Payment initiated |

---

## Key Features

### ✅ Shortcode Voting
- Direct voting using contestant shortcode
- Fastest path to vote
- No need to browse events

### ✅ Event Browsing
- Traditional flow
- Browse events → categories → contestants
- Good for discovery

### ✅ Event Creation
- SMS registration link
- Quick organizer onboarding
- Drives web registration

### ✅ Back Navigation
- All menus have "0. Back"
- Returns to previous menu
- Main menu is home base

---

## Testing Examples

### Test 1: Quick Vote
```
Input: *920*01#
Response: Main menu

Input: 1
Response: Enter nominee shortcode:

Input: AB12
Response: Vote for John Doe, select package

Input: 1
Response: Confirm vote

Input: 1
Response: Payment initiated (END)
```

### Test 2: Browse Events
```
Input: *920*01#
Response: Main menu

Input: 2
Response: Select an event

Input: 1
Response: Select a category

Input: 1
Response: Select a contestant

Input: 1
Response: Select vote package

[Continue...]
```

### Test 3: Create Event
```
Input: *920*01#
Response: Main menu

Input: 3
Response: Registration link sent (END)
```

---

## Implementation Status

✅ **Completed:**
- Main menu structure
- Shortcode voting flow
- Event browsing flow
- Registration link flow
- Error handling
- Back navigation

🔧 **To Configure:**
- SMS service for registration links
- Tenant-specific welcome messages
- Custom branding per tenant

---

**The new USSD flow is ready for testing!** 🚀
