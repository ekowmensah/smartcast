# ✅ Correct USSD URLs

## 🔗 Your USSD Callback URLs

### **Local Development:**
```
http://localhost/smartcast/api/ussd/callback
```

### **Production (when deployed):**
```
https://yourdomain.com/api/ussd/callback
```

---

## 🧪 Testing

### **Test Page:**
Open in browser:
```
http://localhost/smartcast/test_ussd_endpoint.php
```

This will now show the **correct** URL: `http://localhost/smartcast/api/ussd/callback`

### **Test with cURL:**
```bash
curl -X POST http://localhost/smartcast/api/ussd/callback \
  -d "sessionId=test123" \
  -d "serviceCode=*920*01#" \
  -d "phoneNumber=233545644749" \
  -d "text="
```

### **Test with Postman:**
- **URL:** `http://localhost/smartcast/api/ussd/callback`
- **Method:** POST
- **Body (form-data):**
  - sessionId: test123
  - serviceCode: *920*01#
  - phoneNumber: 233545644749
  - text: (empty)

---

## 📝 For Hubtel Dashboard

When you deploy to production, use:

**Service Interaction URL:**
```
https://yourdomain.com/api/ussd/callback
```

**Fulfilment URL:**
```
https://yourdomain.com/api/ussd/callback
```

---

## ⚠️ Common Mistakes

### ❌ Wrong:
```
http://localhost/api/ussd/callback  (missing /smartcast)
```

### ✅ Correct:
```
http://localhost/smartcast/api/ussd/callback
```

---

## 🔍 URL Structure

```
[Protocol]://[Host]/[App Path]/[Route]
   http   ://localhost/smartcast/api/ussd/callback

Production:
   https  ://yourdomain.com/api/ussd/callback
```

---

**Now test again with the correct URL!** 🚀
