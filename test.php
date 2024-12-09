<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إرسال رسالة واتساب</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        input, textarea {
            display: block;
            margin-bottom: 10px;
            padding: 10px;
            width: 100%;
        }
        button {
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
        .status {
            margin-top: 20px;
            padding: 10px;
            border: 1px solid #ccc;
        }
    </style>
</head>
<body>

    <h1>إرسال رسالة واتساب</h1>
    
    <label for="phone">رقم الهاتف:</label>
    <input type="text" id="phone" placeholder="مثال: 96872516814" required>

    <label for="message">الرسالة:</label>
    <textarea id="message" placeholder="اكتب رسالتك هنا" required></textarea>

    <button onclick="sendMessage()">إرسال</button>

    <div id="status" class="status"></div>

    <script>
        function sendMessage() {
            const phone = document.getElementById('phone').value;
            const message = document.getElementById('message').value;
            const statusDiv = document.getElementById('status');

            if (!phone || !message) {
                statusDiv.innerHTML = 'الرجاء إدخال رقم الهاتف والرسالة.';
                return;
            }

            statusDiv.innerHTML = 'جارٍ إرسال الرسالة...';

            fetch('https://corecoode.com/wapi/sendMessageAPI.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'api_key': 'a32b8ed9f9c24aceab6e9265935bfdeb22c4274bb26d4c6797',
                    'to': phone,
                    'msg': message,
                    'file': 'no'
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    statusDiv.innerHTML = 'تم إرسال الرسالة بنجاح!';
                } else {
                    statusDiv.innerHTML = 'فشل في إرسال الرسالة: ' + data.message;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                statusDiv.innerHTML = 'حدث خطأ أثناء إرسال الرسالة.';
            });
        }
    </script>

</body>
</html>
