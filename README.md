🔐 Fingerprint Biometric Attendance Device
An IoT-based fingerprint attendance system that records attendance securely and efficiently.
The device uses a fingerprint sensor connected to an ESP32 and displays status on an OLED display while logging data to a database.

📌 Features
Fingerprint Authentication – Each user’s fingerprint is registered and stored for quick matching.

Real-Time Attendance Logging – Automatically updates attendance records in the database.

OLED Display Feedback – 16x32 OLED shows prompts and success/failure messages.

Wi-Fi Connectivity – Sends attendance data directly to a remote server/database.

Compact & Low-Cost – Portable design with minimal hardware requirements.

🛠️ Tech Stack
Hardware
ESP32 microcontroller

Fingerprint sensor (R307 / R503 / compatible module)

16x32 OLED display

Jumper wires & breadboard (or PCB for final version)

Software
Arduino IDE for ESP32 firmware

MySQL / Firebase for attendance data storage

HTTP/MQTT for communication
